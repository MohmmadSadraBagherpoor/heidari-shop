<?php

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Models\Order;
use App\Models\Product;

class OrderService
{
    public function buildAddons(array $rawAddons): array
    {
        $addons = [];
        $addonsTotal = 0;

        foreach ($rawAddons as $id => $qty) {
            if ((int)$qty <= 0) continue;

            $addon = Product::find($id);
            if (!$addon) continue;

            $lineTotal = $addon->price * (int)$qty;
            $addons[] = [
                'id' => $addon->id,
                'name' => $addon->name,
                'qty' => (int)$qty,
                'price' => $addon->price,
                'total' => $lineTotal,
            ];
            $addonsTotal += $lineTotal;
        }

        return [$addons, $addonsTotal];
    }

    public function uploadImages(array $files): array
    {
        return collect($files)
            ->map(fn($file) => $file->store('receipts', 'public'))
            ->toArray();
    }

    public function createOrder(OrderDTO $dto, Product $product, int $totalPrice, string $orderCode): Order
    {
        return Order::create([
            'city_id' => $dto->cityId,
            'province_id' => $dto->provinceId,
            'status' => 'processing',
            'prd_id' => $product->id,
            'phone' => $dto->phone,
            'total_price' => $totalPrice,
            'prd_qty' => $dto->productQty,
            'prd_price' => $product->price,
            'address' => $dto->address,
            'full_name' => $dto->fullName,
            'receipt_images' => $dto->images,
            'order_caption' => $dto->addons,
            'order_code' => $orderCode,
            'shipping_method' => $dto->shippingMethod,
            'shipping_time' => $dto->shippingTime,
            'shipping_day' => $dto->shippingDay,
        ]);
    }

    public function generateOrderCode(): string
    {
        do {
            $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
