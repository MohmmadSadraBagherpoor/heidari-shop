<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'کاربر';
    protected static ?string $pluralLabel = 'کاربران';
    protected static ?string $navigationLabel = 'کاربران';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('اطلاعات تلگرام')
                    ->icon('heroicon-o-paper-airplane')
                    ->description('اطلاعات کاربری مرتبط با بات تلگرام')
                    ->schema([
                        Forms\Components\TextInput::make('chat_id')
                            ->label('Chat ID')
                            ->placeholder('مثال: 123456789')
                            ->suffixIcon('heroicon-m-chat-bubble-left-right')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('username')
                            ->label('نام کاربری')
                            ->placeholder('@username')
                            ->prefix('@')
                            ->required()
                            ->suffixIcon('heroicon-m-at-symbol')
                            ->maxLength(120),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('اطلاعات شخصی')
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('نام و نام خانوادگی')
                            ->placeholder('مثال: علی رضایی')
                            ->suffixIcon('heroicon-m-identification')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('شماره تماس')
                            ->placeholder('مثال: 09123456789')
                            ->tel()
                            ->mask('99999999999')
                            ->suffixIcon('heroicon-m-phone')
                            ->maxLength(11),

                        Forms\Components\TextInput::make('city')
                            ->label('شهر')
                            ->placeholder('مثال: تهران')
                            ->suffixIcon('heroicon-m-map-pin')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('province')
                            ->label('استان')
                            ->placeholder('مثال: تهران')
                            ->suffixIcon('heroicon-m-map')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label('آدرس کامل')
                            ->placeholder('نشانی دقیق محل سکونت...')
                            ->autosize()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('اطلاعات حساب')
                    ->icon('heroicon-o-lock-closed')
                    ->description('اطلاعات ورود و لاگین کاربر')
                    ->collapsible()
                    ->visible(fn ($record) => filled($record?->email))   // ← این خط مهمه
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام ورود')
                            ->placeholder('یوزرنیم ورود')
                            ->suffixIcon('heroicon-m-user-circle')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->placeholder('example@gmail.com')
                            ->suffixIcon('heroicon-m-envelope')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('رمز عبور')
                            ->password()
                            ->placeholder('********')
                            ->suffixIcon('heroicon-m-key')
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
//            ->modifyQueryUsing(function (Builder $query){
//                return $query->whereNull('email');
//            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('نام و نام خانوادگی')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('username')
                    ->label('نام کاربری')
                    ->searchable()
                    ->copyable()
                    ->default('-')
                    ->icon('heroicon-m-at-symbol'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->copyable()
                    ->default('-')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('city')
                    ->label('شهر')
                    ->searchable()
                    ->default('-')
                    ->icon('heroicon-m-map-pin'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ بروزرسانی')
                    ->dateTime('Y/m/d H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('شهر')
                    ->options(fn () => User::query()
                        ->whereNotNull('city')
                        ->distinct()
                        ->pluck('city', 'city')
                        ->toArray()
                    )
                    ->searchable()
                    ->native(false)
                    ->placeholder('انتخاب شهر'),
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
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation(),

                    Tables\Actions\Action::make('sendTelegram')
                        ->label('ارسال پیام تلگرام')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->form([
                            Forms\Components\Textarea::make('message')
                                ->label('متن پیام')
                                ->required()
                                ->rows(5),
                        ])
                        ->action(function ($record, array $data) {

                            $botToken = config('services.telegram.bot_token');

                            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                                'chat_id' => $record->chat_id,
                                'text' => $data['message'],
                                'parse_mode' => 'HTML',
                            ]);
                        })
                        ->modalHeading('ارسال پیام به کاربر')
                        ->modalSubmitActionLabel('ارسال')
                        ->successNotificationTitle('پیام با موفقیت ارسال شد'),
                ]),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف انتخاب‌شده‌ها'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
