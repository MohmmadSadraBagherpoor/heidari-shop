<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Http;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('sendMessageToAllUsers')
                ->label('ارسال پیام همگانی')
                ->icon('heroicon-o-megaphone')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Textarea::make('message')
                        ->label('متن پیام')
                        ->required()
                        ->rows(6),
                ])
                ->action(function (array $data) {
//Todo:add botToken to .Env
                    $botToken = config('services.telegram.bot_token');

                    $users = User::whereNotNull('chat_id')->get();

                    foreach ($users as $user) {

                        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                            'chat_id' => $user->chat_id,
                            'text' => $data['message'],
                            'parse_mode' => 'HTML',
                        ]);

                    }
                })
                ->modalHeading('ارسال پیام به همه کاربران')
                ->modalSubmitActionLabel('ارسال به همه')
                ->requiresConfirmation()
                ->successNotificationTitle('پیام برای همه کاربران ارسال شد'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('همه')
                ->badge(\App\Models\User::count()),

            'admins' => Tab::make('مدیران')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('email'))
                ->badge(\App\Models\User::whereNotNull('email')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-shield-check'),

            'users' => Tab::make('کاربران')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('email')->whereNotNull('chat_id'))
                ->badge(\App\Models\User::whereNull('email')->whereNotNull('chat_id')->count())
                ->badgeColor('info')
                ->icon('heroicon-o-user'),

            'customers' => Tab::make('مشتریان')
                ->modifyQueryUsing(fn (Builder $query) => $query->has('orders'))
                ->badge(\App\Models\User::has('orders')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-shopping-bag'),
        ];
    }
}
