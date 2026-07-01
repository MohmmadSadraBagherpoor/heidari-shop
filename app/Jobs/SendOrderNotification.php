<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public Order  $order,
        public array  $addons,
        public array  $images,
        public string $productName,
    )
    {
    }

    public function handle(): void
    {
        $telegramResult = app(TelegramService::class)->sendOrder([
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'full_name' => $this->order->full_name,
            'phone' => $this->order->phone,
            'address' => $this->order->address,
            'product' => $this->productName,
            'qty' => $this->order->prd_qty,
            'total_price' => number_format($this->order->total_price),
            'unit_price' => number_format($this->order->prd_price),
            'shipping' => $this->order->shipping_method,
            'addons' => $this->addons,
            'images' => $this->images,
            'city_id' => $this->order->city_id,
            'province' => optional($this->order->province)->title ?? '',
            'city' => optional($this->order->city)->title ?? '',
        ]);

        if (!empty($telegramResult['message_id'])) {
            $this->order->update([
                'msg_id' => $telegramResult['message_id'],
                'chat_id' => $telegramResult['chat_id'] ?? null,
            ]);
        }
    }

    public function failed(\Throwable $e): void
    {
        \Log::error('SendOrderNotification failed', [
            'order_id' => $this->order->id,
            'error' => $e->getMessage(),
        ]);
    }
}
