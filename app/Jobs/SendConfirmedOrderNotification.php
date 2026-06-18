<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\BaleService;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendConfirmedOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Order $order)
    {
    }

    public function handle(): void
    {
        // ارسال پیامک به کاربر
        app(SmsService::class)->send(
            $this->order->phone,
            "سفارش شما با موفقیت ثبت شد ✅\n\n"
            . "ممنون از اعتمادتون 💚\n\n"
            . "کد سفارش: {$this->order->order_code}\n\n"
            . "تجهیزات پی آر پی 🧪"
        );

        app(BaleService::class)->sendToConfirmedChannel($this->order);
    }

    public function failed(\Throwable $e): void
    {
        \Log::error('SendConfirmedOrderNotification failed', [
            'order_id' => $this->order->id,
            'error' => $e->getMessage(),
        ]);
    }
}
