<?php

namespace App\Http\Controllers;

use App\DTOs\OrderDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\SendOrderNotification;
use App\Models\City;
use App\Models\Product;
use App\Services\CityService;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function create()
    {
        $mainProduct = Product::query()
            ->where('type', 'main')
            ->where('status', true)
            ->first();

        $addOns = Product::query()
            ->where('type', 'add_on')
            ->where('status', true)
            ->get();

        $provinces = CityService::provinces();

        $allCities = City::orderBy('title')
            ->get()
            ->groupBy('parent')
            ->map(fn($cities) => $cities->values()->toArray());

        return view('checkout.index', compact('mainProduct', 'addOns', 'provinces', 'allCities'));
    }

    public function store(StoreOrderRequest $request)
    {
        $product = Product::findOrFail($request->prd_id);

        $images = $this->orderService->uploadImages($request->file('images', []));

        [$addons, $addonsTotal] = $this->orderService->buildAddons(
            $request->input('addons', [])
        );

        $dto = OrderDTO::fromRequest($request, $addons, $images);

        $totalPrice = ($product->price * $dto->productQty) + $addonsTotal;
        $orderCode = $this->orderService->generateOrderCode();

        $order = $this->orderService->createOrder($dto, $product, $totalPrice, $orderCode);

        SendOrderNotification::dispatch($order, $addons, $images, $product->name);

        $supportUsername = 'Prp_hair'; 
        $preFilledText = "سلام، من سفارشم رو ثبت کردم\nبا کد: {$orderCode}\nاطلاعاتش رو میخوام";
        $telegramLink = "https://t.me/{$supportUsername}?text=" . urlencode($preFilledText);
        // ---------------------------------------------

        return back()->with([
            'success' => true,
            'order_id' => $orderCode,
            'order_total' => $totalPrice,
            'order_date' => \Hekmatinasser\Verta\Verta::now()->format('j F Y'),
            'telegram_link' => $telegramLink,
        ]);
    }
}
