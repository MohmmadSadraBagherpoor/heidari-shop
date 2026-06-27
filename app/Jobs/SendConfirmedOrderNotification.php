<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\TelegramService;
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
        $isTehran = (int) $this->order->province_id === 8;

        if ($isTehran) {
            $smsText = "سفارش شما با موفقیت ثبت شد ✅\n"
                . "ممنون از اعتمادتون 💚\n"
                . "بزودی جهت ارسال باهاتون تماس میگیریم 📞\n"
                . "کد سفارش: {$this->order->order_code}\n"
                . "تجهیزات پی آر پی 🧪";
        } else {
            $smsText = "سفارش شما با موفقیت ثبت شد ✅\n"
                . "ممنون از اعتمادتون 💚\n"
                . "کد سفارش: {$this->order->order_code}\n"
                . "تجهیزات پی آر پی 🧪";
        }

        app(SmsService::class)->send($this->order->phone, $smsText);

        app(TelegramService::class)->sendToConfirmedChannel($this->order);
    }

    public function failed(\Throwable $e): void
    {
        \Log::error('SendConfirmedOrderNotification failed', [
            'order_id' => $this->order->id,
            'error'    => $e->getMessage(),
        ]);
    }
}
