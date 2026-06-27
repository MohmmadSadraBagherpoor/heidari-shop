<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use App\Services\CityService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'سفارشات';

    protected static ?string $navigationGroup = 'فروشگاه';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات کاربر')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('chat_id')
                                    ->label('Chat ID')
                                    ->required(false)
                                    ->placeholder('Telegram Chat ID')
                                    ->disabled(fn($state) => is_null($state))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('full_name')
                                    ->label('نام و نام خانوادگی')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                    ->label('تلفن')
                                    ->tel()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('province_id')
                                    ->label('استان')
                                    ->options(fn() => CityService::provinces())  // از cache
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                                    ->required(),

                                Forms\Components\Select::make('city_id')
                                    ->label('شهر')
                                    ->options(function (Get $get) {
                                        if (!$get('province_id')) return [];
                                        return CityService::citiesByProvince((int)$get('province_id'));  // از cache
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label('آدرس')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('محصول اصلی')
                    ->schema([
                        Forms\Components\Select::make('prd_id')
                            ->label('محصول')
                            ->options(
                                Product::query()
                                    ->where('type', 'main')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('prd_price', $product->package_price);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('prd_qty')
                            ->label('تعداد')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $qty = (int)($get('prd_qty') ?? 0);
                                $price = (int)($get('prd_price') ?? 0);
                                $set('prd_total', $qty * $price);
                                self::calculateTotalPrice($get, $set);
                            }),

                        Forms\Components\TextInput::make('prd_price')
                            ->label('قیمت واحد')
                            ->numeric()
                            ->prefix('تومان')
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $qty = (int)($get('prd_qty') ?? 0);
                                $price = (int)($get('prd_price') ?? 0);
                                $set('prd_total', $qty * $price);
                                self::calculateTotalPrice($get, $set);
                            }),

                        Forms\Components\TextInput::make('prd_total')
                            ->label('جمع محصول اصلی')
                            ->numeric()
                            ->prefix('تومان')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Set $set, Get $get) {
                                $qty = (int)($get('prd_qty') ?? 0);
                                $price = (int)($get('prd_price') ?? 0);
                                $set('prd_total', $qty * $price);
                            }),
                    ])
                    ->columns(4),  // از 3 به 4 تغییر داد

                Forms\Components\Repeater::make('order_caption')
                    ->label('محصولات مکمل')
                    ->schema([

                        Forms\Components\Select::make('id')
                            ->label('محصول')
                            ->options(
                                Product::query()
                                    ->where('type', 'add_on')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {

                                $product = Product::find($state);

                                if (!$product) {
                                    return;
                                }

                                $qty = (int)($get('qty') ?? 1);

                                $set('name', $product->name);

                                $set('price', $product->price);

                                $set('total', $product->price * $qty);
                            }),

                        Forms\Components\Hidden::make('name'),

                        Forms\Components\TextInput::make('qty')
                            ->label('تعداد')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {

                                $qty = (int)($get('qty') ?? 1);

                                $price = (int)($get('price') ?? 0);

                                $set('total', $qty * $price);
                            }),

                        Forms\Components\TextInput::make('price')
                            ->label('قیمت واحد')
                            ->numeric()
                            ->prefix('تومان')
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {

                                $qty = (int)($get('qty') ?? 1);

                                $price = (int)($get('price') ?? 0);

                                $set('total', $qty * $price);
                            }),

                        Forms\Components\TextInput::make('total')
                            ->label('جمع')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix('تومان'),

                    ])
                    ->columns(4)
                    ->collapsible()
                    ->cloneable()
                    ->reorderable()
                    ->defaultItems(0)
                    ->columnSpanFull(),

                Forms\Components\Section::make('رسید پرداخت')
                    ->schema([
                        Forms\Components\FileUpload::make('receipt_images')
                            ->label('تصاویر رسید')
                            ->image()
                            ->multiple()
                            ->directory('receipts')
                            ->disk('public')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable()
                            ->reorderable()
                            ->panelLayout('grid')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('قیمت کل')
                    ->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->label('قیمت نهایی')
                            ->numeric()
                            ->prefix('تومان')
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'text-2xl font-bold']),
                    ]),

// در form() - Section ارسال
                Forms\Components\Section::make('ارسال')
                    ->schema([
                        Forms\Components\Select::make('shipping_method')
                            ->label('روش ارسال')
                            ->live()
                            ->options([
                                'snapp_today' => 'اسنپ امروز',
                                'snapp_tomorrow' => 'اسنپ فردا',
                                'tipax' => 'تیپاکس',
                                'express' => 'پیک فوری',
                            ])
                            ->native(false),

                        Forms\Components\Select::make('shipping_day')
                            ->native(false)
                            ->options([
                                'شنبه' => 'شنبه',
                                'یکشنبه' => 'یکشنبه',
                                'دوشنبه' => 'دوشنبه',
                                'سه شنبه' => 'سه شنبه',
                                'چهارشنبه' => 'چهارشنبه',
                                'پنجشنبه' => 'پنجشنبه',
                                'جمعه' => 'جمعه',
                            ])
                            ->label('روز ارسال')
                            ->hidden(fn(Get $get) => $get('shipping_method') === 'tipax'),

                        Forms\Components\Select::make('shipping_time')
                            ->label('ساعت ارسال')
                            ->hidden(fn(Get $get) => $get('shipping_method') === 'tipax')
                            ->options([
                                '11_13' => '11 تا 13',
                                '13_15' => '13 تا 15',
                                '15_17' => '15 تا 17',
                            ])
                            ->native(false),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('وضعیت سفارش')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'processing' => 'در حال پردازش',
                                'received' => 'دریافت شده',
                                'confirmed' => 'تایید شده',
                                'rejected' => 'رد شده',
                            ])
                            ->default('processing')
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('confirmed_rejected_at')
                            ->jalali()
                            ->label('تاریخ تایید/رد'),

                        Forms\Components\Select::make('user_feedback')
                            ->label('بازخورد کاربر')
                            ->options([
                                'good' => 'خوب',
                                'bad' => 'بد',
                                'no_comment' => 'بدون نظر',
                            ])
                            ->native(false),
                    ])
                    ->columns(3),

//                Forms\Components\Section::make('اطلاعات تکمیلی')
//                    ->schema([
//                        Forms\Components\TextInput::make('msg_id')
//                            ->label('Message ID')
//                            ->numeric(),
//
//                        Forms\Components\Textarea::make('order_caption')
//                            ->label('توضیحات سفارش')
//                            ->rows(3)
//                            ->columnSpanFull(),
//
//                        Forms\Components\Textarea::make('receipt_images')
//                            ->label('تصاویر رسید (JSON)')
//                            ->rows(3)
//                            ->helperText('آرایه JSON از آیدی تصاویر')
//                            ->columnSpanFull(),
//                    ])
//                    ->collapsible(),
            ]);
    }

    protected static function calculateTotalPrice(Get $get, Set $set): void
    {
        $prdQty = (int)($get('prd_qty') ?? 0);

        $prdPrice = (int)($get('prd_price') ?? 0);

        $mainTotal = $prdQty * $prdPrice;

        $addons = $get('order_caption') ?? [];

        $addonsTotal = collect($addons)
            ->sum(function ($item) {

                return
                    ((int)($item['qty'] ?? 0))
                    *
                    ((int)($item['price'] ?? 0));
            });

        $total = $mainTotal + $addonsTotal;

        $set('total_price', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('receipt_images')
                    ->label('رسید')
//                    ->disk('public')        // ← این رو اضافه کن
                    ->stacked()
                    ->circular(true)
                    ->limit(3),

                Tables\Columns\TextColumn::make('id')
                    ->label('شماره')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('نام مشتری')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('province_id')
                    ->label('استان')
                    ->formatStateUsing(fn($state) => CityService::getProvinceName($state))
                    ->default('-'),

                Tables\Columns\TextColumn::make('city_id')
                    ->label('شهر')
                    ->formatStateUsing(fn($state) => CityService::getCityName($state))
                    ->searchable(false)  // چون ID هست searchable رو بریم
                    ->default('-'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('محصول')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return '-';
                        $qty = $record->prd_qty ?? 1;
                        return "{$state} × {$qty}";
                    })
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) return null;
                        return $state;
                    }),

// ستون order_caption - fix نمایش JSON
                Tables\Columns\TextColumn::make('order_caption')
                    ->label('محصولات مکمل')
                    ->placeholder('موردی موجود نیست')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '-';

                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                                return '-';
                            }
                            $state = $decoded;
                        }

                        if (!is_array($state) || empty($state)) return '-';  // اینجا [] رو هم catch می‌کنه

                        $items = isset($state[0]) ? $state : [$state];

                        $result = collect($items)
                            ->filter(fn($item) => is_array($item) && isset($item['name']))
                            ->map(fn($item) => "{$item['name']} × {$item['qty']}")
                            ->implode(' | ');

                        return $result ?: '-';
                    })
                    ->wrap(),

//                Tables\Columns\TextColumn::make('prd_qty')
//                    ->label('تعداد')
//                    ->alignCenter()
//                    ->default('-'),
////
//                Tables\Columns\TextColumn::make('addOnProduct.name')
//                    ->label('محصول اضافی')
//                    ->searchable()
//                    ->limit(15)
//                    ->default('-')
//                    ->toggleable(),

//                Tables\Columns\TextColumn::make('add_on_prd_qty')
//                    ->label('تعداد اضافی')
//                    ->alignCenter()
//                    ->default('-')
//                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('قیمت کل')
                    ->formatStateUsing(fn($state) => $state ? number_format($state) . ' تومان' : '-')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->colors([
                        'warning' => 'processing',
                        'info' => 'received',
                        'success' => 'confirmed',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'processing' => 'در حال پردازش',
                        'received' => 'دریافت شده',
                        'confirmed' => 'تایید شده',
                        'rejected' => 'رد شده',
                        default => $state ?? '-',
                    }),

                Tables\Columns\TextColumn::make('shipping_method')
                    ->label('روش ارسال')
                    ->badge()
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'snapp_today' => 'اسنپ امروز',
                        'snapp_tomorrow' => 'اسنپ فردا',
                        'tipax' => 'تیپاکس',
                        'express' => 'پیک فوری',
                        default => '-',
                    })
                    ->color(fn(?string $state): string => match ($state) {
                        'snapp_today' => 'success',
                        'snapp_tomorrow' => 'warning',
                        'tipax' => 'primary',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('shipping_day')
                    ->label('روز ارسال')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('shipping_time')
                    ->label('ساعت ارسال')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user_feedback')
                    ->label('بازخورد')
                    ->badge()
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'good' => 'خوب',
                        'bad' => 'بد',
                        'no_comment' => 'بدون نظر',
                        default => '-',
                    })
                    ->color(fn(?string $state): string => match ($state) {
                        'good' => 'success',
                        'bad' => 'danger',
                        'no_comment' => 'gray',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->jalaliDateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('confirmed_rejected_at')
                    ->label('تاریخ تایید/رد')
                    ->jalaliDateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'processing' => 'در حال پردازش',
                        'received' => 'دریافت شده',
                        'confirmed' => 'تایید شده',
                        'rejected' => 'رد شده',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('shipping_method')
                    ->label('روش ارسال')
                    ->options([
                        'snapp_today' => 'اسنپ امروز',
                        'snapp_tomorrow' => 'اسنپ فردا',
                        'tipax' => 'تیپاکس',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('province_id')
                    ->label('استان')
                    ->options(fn() => CityService::provinces())  // از cache
                    ->native(false),

                Tables\Filters\SelectFilter::make('city_id')
                    ->label('شهر')
                    ->options(fn() => CityService::allCities())  // از cache
                    ->searchable()
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->jalali()
                            ->label('از تاریخ'),
                        Forms\Components\DatePicker::make('created_until')
                            ->jalali()
                            ->label('تا تاریخ'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('confirm')
                    ->label('تایید')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {

                        $record->update([
                            'status' => 'confirmed',
                            'confirmed_rejected_at' => now(),
                        ]);

                        $product = Product::find($record->prd_id);

                        $telegramResult = app(\App\Services\TelegramService::class)->sendOrder([
                            'order_id' => $record->id,
                            'order_code' => $record->order_code,
                            'full_name' => $record->full_name,
                            'phone' => $record->phone,
                            'address' => $record->address,
                            'product' => $product?->name,
                            'qty' => $record->prd_qty,
                            'total' => number_format($record->total_price),
                            'shipping' => $record->shipping_method,
                            'addons' => is_string($record->order_caption)
                                ? json_decode($record->order_caption, true) ?? []
                                : ($record->order_caption ?? []),
                            'images' => $record->receipt_images ?? [],
                        ]);

                        if (!empty($telegramResult['message_id'])) {
                            $record->update([
                                'msg_id' => $telegramResult['message_id'],
                                'chat_id' => $telegramResult['chat_id'] ?? null,
                            ]);
                        }

                        $isTehran = (int)$record->province_id === 8;

                        if ($isTehran) {
                            $smsText = "سفارش شما با موفقیت ثبت شد ✅\n"
                                . "ممنون از اعتمادتون 💚\n"
                                . "بزودی جهت ارسال باهاتون تماس میگیریم 📞\n"
                                . "کد سفارش: {$record->order_code}\n"
                                . "تجهیزات پی آر پی 🧪";
                        } else {
                            $smsText = "سفارش شما با موفقیت ثبت شد ✅\n"
                                . "ممنون از اعتمادتون 💚\n"
                                . "کد سفارش: {$record->order_code}\n"
                                . "تجهیزات پی آر پی 🧪";
                        }

                        app(\App\Services\SmsService::class)->send($record->phone, $smsText);

                        app(\App\Services\TelegramService::class)->sendToConfirmedChannel($record);
                    })
                    ->visible(fn(Order $record) => $record->status === 'processing'),

                Tables\Actions\Action::make('reject')
                    ->label('رد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'rejected',
                            'confirmed_rejected_at' => now(),
                        ]);
                    })
                    ->visible(fn(Order $record) => $record->status === 'processing'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('confirm_orders')
                        ->label('تایید سفارشات')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update([
                            'status' => 'confirmed',
                            'confirmed_rejected_at' => now(),
                        ]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('reject_orders')
                        ->label('رد سفارشات')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update([
                            'status' => 'rejected',
                            'confirmed_rejected_at' => now(),
                        ]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string)Order::query()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
