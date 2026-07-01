<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'محصولات';

    protected static ?string $modelLabel = 'محصول';

    protected static ?string $pluralModelLabel = 'محصولات';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات اصلی محصول')
                    ->description('مشخصات و اطلاعات کلی محصول را وارد کنید')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('نام محصول')
                                            ->placeholder('مثال: پکیج طلایی')
                                            ->prefixIcon('heroicon-o-cube')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),

                                        Forms\Components\TextInput::make('price')
                                            ->label('قیمت بسته')
                                            ->placeholder('مثال: 500,000 تومان')
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->suffixIcon('heroicon-o-banknotes')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('stock')
                                            ->label('موجودی')
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(0)
                                            ->prefixIcon('heroicon-o-archive-box')
                                            ->helperText('تعداد موجود در انبار')
                                            ->required(),
                                    ]),
                                Forms\Components\TextInput::make('off_price')
                                    ->label('قیمت تخفیف خورده بسته')
                                    ->reactive()
                                    ->placeholder('مثال: 500,000 تومان')
                                    ->helperText('درصورت عدم تخفیف برای محصول فیلد را خالی بگذارید.')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->suffixIcon('heroicon-o-banknotes')
                                    ->maxLength(255),

                                Forms\Components\DateTimePicker::make('discount_ends_at')
                                    ->jalali()
                                    ->label('زمان پایان تخفیف')
                                    ->placeholder('اختیاری - فقط اگر تایمر میخواید')
                                    ->helperText('درصورت عدم نیاز به تایمر، خالی بگذارید.')
                                    ->prefixIcon('heroicon-o-clock')
                                    ->native(false)
                                    ->visible(fn(Forms\Get $get) => filled($get('off_price'))),

                                Forms\Components\Select::make('type')
                                    ->label('نوع محصول')
                                    ->options([
                                        'main' => 'اصلی',
                                        'add_on' => 'مکمل'
                                    ])
                                    ->native(false),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات محصول')
                            ->placeholder('توضیحات کامل و جزئیات محصول را بنویسید...')
                            ->rows(5)
                            ->columnSpanFull()
                            ->maxLength(1000),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('تصویر و وضعیت')
                    ->description('تصویر محصول و وضعیت نمایش آن')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('تصویر محصول')
                            ->image()
//                            ->formatStateUsing(fn ($record)=> dd($record->image))
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(4096)
                            ->helperText('حداکثر حجم: 4 مگابایت - فرمت‌های مجاز: JPG, PNG')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('status')
                            ->label('وضعیت نمایش')
                            ->helperText('محصول در سایت نمایش داده شود؟')
                            ->default(true)
                            ->inline(false)
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('تصویر')
                    ->circular()
                    ->disk('public')
                    ->size(60)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام محصول')
                    ->icon('heroicon-o-cube')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Product $record): string => $record->description ? \Str::limit($record->description, 50) : 'بدون توضیحات'
                    ),

                Tables\Columns\TextColumn::make('price')
                    ->label('قیمت بسته')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->searchable()
                    ->placeholder('تعیین نشده'),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع محصول')
                    ->formatStateUsing(function ($state) {
                        return $state == 'main' ? 'اصلی' : 'مکمل';
                    })
                    ->badge(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('موجودی')
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state > 10 ? 'success' :
                        ($state > 0 ? 'warning' : 'danger')
                    )
                    ->formatStateUsing(fn($state) => $state > 0 ? $state . ' عدد' : 'ناموجود'
                    ),


                Tables\Columns\ToggleColumn::make('status')
                    ->label('وضعیت')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->icon('heroicon-o-calendar')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ بروزرسانی')
                    ->icon('heroicon-o-clock')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('وضعیت نمایش')
                    ->placeholder('همه محصولات')
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال')
                    ->native(false),
                Tables\Filters\TernaryFilter::make('type')
                    ->label('نوع محصول')
                    ->placeholder('همه محصولات')
                    ->trueLabel('مکمل')
                    ->falseLabel('اصلی')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('مشاهده')
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->label('ویرایش')
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->label('حذف')
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('addStock')
                        ->label('افزودن موجودی')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('quantity')
                                ->label('تعداد اضافه شده')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {

                            $record->increment('stock', $data['quantity']);

                        })
                        ->modalHeading('افزودن موجودی به محصول')
                        ->modalSubmitActionLabel('افزودن')
                        ->successNotificationTitle('موجودی با موفقیت افزایش یافت'),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف انتخاب شده‌ها')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('هیچ محصولی یافت نشد!')
            ->emptyStateDescription('برای شروع، اولین محصول خود را ایجاد کنید.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('ایجاد محصول جدید')
                    ->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'warning';
    }
}
