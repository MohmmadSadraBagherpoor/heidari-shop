<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\BaleService;
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
        $baleResult = app(BaleService::class)->sendOrder([
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'full_name' => $this->order->full_name,
            'phone' => $this->order->phone,
            'address' => $this->order->address,
            'product' => $this->productName,
            'qty' => $this->order->prd_qty,
            'total' => number_format($this->order->total_price),
            'shipping' => $this->order->shipping_method,
            'addons' => $this->addons,
            'images' => $this->images,
        ]);

        if (!empty($baleResult['message_id'])) {
            $this->order->update([
                'bale_message_id' => $baleResult['message_id'],
                'bale_chat_id' => $baleResult['chat_id'] ?? null,
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
