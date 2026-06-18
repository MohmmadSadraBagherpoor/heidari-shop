<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayOrdersStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // محاسبات امروز
        $today = now()->startOfDay();
        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('status', 'confirmed')
            ->sum('total_price');

        // محاسبات هفته جاری (از شنبه تا جمعه)
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weekOrdersCount = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $weekRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'confirmed')
            ->sum('total_price');
        $weekConfirmedCount = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'confirmed')
            ->count();

        // محاسبات ماه جاری
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $monthOrdersCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $monthRevenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'confirmed')
            ->sum('total_price');
        $monthConfirmedCount = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'confirmed')
            ->count();

        // محاسبات هفته قبل (برای مقایسه)
        $startOfLastWeek = now()->subWeek()->startOfWeek();
        $endOfLastWeek = now()->subWeek()->endOfWeek();
        $lastWeekOrdersCount = Order::whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])->count();

        // محاسبات ماه قبل (برای مقایسه)
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();
        $lastMonthOrdersCount = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        // محاسبه درصد تغییر
        $weekChange = $lastWeekOrdersCount > 0
            ? round((($weekOrdersCount - $lastWeekOrdersCount) / $lastWeekOrdersCount) * 100, 1)
            : 0;

        $monthChange = $lastMonthOrdersCount > 0
            ? round((($monthOrdersCount - $lastMonthOrdersCount) / $lastMonthOrdersCount) * 100, 1)
            : 0;

        return [
            // درآمد امروز
            Stat::make('درآمد امروز', number_format($todayRevenue) . ' تومان')
                ->description('از ' . $todayOrdersCount . ' سفارش ثبت شده')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([5, 3, 6, 8, 4, 7, 9, 6]),

            // درآمد هفته جاری
            Stat::make('درآمد این هفته', number_format($weekRevenue) . ' تومان')
                ->description('از ' . $weekConfirmedCount . ' سفارش تایید شده')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([5, 8, 12, 15, 10, 14, 18]),

            // درآمد ماه جاری
            Stat::make('درآمد این ماه', number_format($monthRevenue) . ' تومان')
                ->description('از ' . $monthConfirmedCount . ' سفارش تایید شده')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success')
                ->chart([20, 25, 30, 35, 32, 40, 45, 42, 48, 50, 55, 60]),

            // آمار امروز
            Stat::make('سفارشات امروز', $todayOrdersCount)
                ->description('تعداد کل سفارشات ثبت شده امروز')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            // آمار هفته جاری
            Stat::make('سفارشات این هفته', $weekOrdersCount)
                ->description($weekConfirmedCount . ' تایید شده | ' .
                    ($weekChange >= 0 ? '+' : '') . $weekChange . '% نسبت به هفته قبل')
                ->descriptionIcon($weekChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($weekChange >= 0 ? 'success' : 'danger')
                ->chart([3, 5, 8, 10, 6, 4, 7]),

            // آمار ماه جاری
            Stat::make('سفارشات این ماه', $monthOrdersCount)
                ->description($monthConfirmedCount . ' تایید شده | ' .
                    ($monthChange >= 0 ? '+' : '') . $monthChange . '% نسبت به ماه قبل')
                ->descriptionIcon($monthChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthChange >= 0 ? 'success' : 'danger')
                ->chart([12, 15, 18, 22, 20, 25, 28, 24, 30, 28, 32, 35]),
        ];
    }

    protected static ?int $sort = 1;

    // بروزرسانی خودکار هر 30 ثانیه
    protected static ?string $pollingInterval = '30s';
}
