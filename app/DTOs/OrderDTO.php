<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class OrderDTO
{
    public function __construct(
        public readonly string  $fullName,
        public readonly string  $phone,
        public readonly int     $cityId,
        public readonly int     $provinceId,
        public readonly string  $address,
        public readonly int     $productId,
        public readonly int     $productQty,
        public readonly string  $shippingMethod,
        public readonly ?string $shippingDay,
        public readonly ?string $shippingTime,
        public readonly array   $addons,
        public readonly array   $images,
    )
    {
    }

    public static function fromRequest(Request $request, array $addons, array $images): self
    {
        $shippingDay = null;
        $shippingTime = null;

        if ($request->filled('shipping_time')) {
            $parts = array_map('trim', explode('-', $request->shipping_time, 2));
            $shippingDay = $parts[0] ?? null;
            $shippingTime = $parts[1] ?? null;
        }

        return new self(
            fullName: $request->full_name,
            phone: $request->phone,
            cityId: (int)$request->city_id,
            provinceId: (int)$request->province_id,
            address: $request->address,
            productId: (int)$request->prd_id,
            productQty: (int)$request->prd_qty,
            shippingMethod: $request->shipping_method,
            shippingDay: $shippingDay,
            shippingTime: $shippingTime,
            addons: $addons,
            images: $images,
        );
    }
}
