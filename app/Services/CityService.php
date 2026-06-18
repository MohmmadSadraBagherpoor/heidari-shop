<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class CityService
{
    // یه هفته cache می‌کنیم چون تغییر نمی‌کنن
    private const TTL = 60 * 60 * 24 * 7;

    public static function provinces(): \Illuminate\Support\Collection
    {
        return Cache::remember('cities.provinces', self::TTL, function () {
            return City::query()
                ->where('parent', 0)
                ->pluck('title', 'id');
        });
    }

    public static function citiesByProvince(int $provinceId): \Illuminate\Support\Collection
    {
        return Cache::remember("cities.by_province.{$provinceId}", self::TTL, function () use ($provinceId) {
            return City::query()
                ->where('parent', $provinceId)
                ->pluck('title', 'id');
        });
    }

    // همه شهرها یکجا - برای lookup در table
    public static function allCities(): \Illuminate\Support\Collection
    {
        return Cache::remember('cities.all', self::TTL, function () {
            return City::query()
                ->where('parent', '!=', 0)
                ->pluck('title', 'id');
        });
    }

    public static function allProvinceNames(): \Illuminate\Support\Collection
    {
        return Cache::remember('cities.province_names', self::TTL, function () {
            return City::query()
                ->where('parent', 0)
                ->pluck('title', 'id');
        });
    }

    public static function getCityName(int|string|null $id): string
    {
        if (!$id) return '-';
        return static::allCities()->get((int)$id, '-');
    }

    public static function getProvinceName(int|string|null $id): string
    {
        if (!$id) return '-';
        return static::allProvinceNames()->get((int)$id, '-');
    }
}
