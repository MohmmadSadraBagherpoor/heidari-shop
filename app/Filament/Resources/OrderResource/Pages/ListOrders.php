<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('همه')
                ->badgeColor(Color::Cyan)
                ->icon('heroicon-o-bars-3')
                ->badge(fn () => \App\Models\Order::count()),
            'processing' => Tab::make('در حال پردازش')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'processing'))
                ->icon('heroicon-o-clock')
                ->badge(fn () => \App\Models\Order::where('status', 'processing')->count()),

            'confirmed' => Tab::make('تایید شده')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'confirmed'))
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success')
                ->badge(fn () => \App\Models\Order::where('status', 'confirmed')->count()),

            'rejected' => Tab::make('رد شده')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected'))
                ->badgeColor('danger')
                ->icon('heroicon-o-x-circle')
                ->badge(fn () => \App\Models\Order::where('status', 'rejected')->count()),

            'received' => Tab::make('دریافت شده')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'received'))
                ->badgeColor('info')
                ->icon('heroicon-o-inbox')
                ->badge(fn () => \App\Models\Order::where('status', 'received')->count()),
        ];
    }

}
