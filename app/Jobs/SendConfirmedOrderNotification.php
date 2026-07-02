<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\SmsService;
use App\Services\TelegramService;
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
        \Log::info('SendConfirmedOrderNotification START', [
            'order_id' => $this->order->id,
            'phone' => $this->order->phone,
            'city_id' => $this->order->city_id,
        ]);

        $isTehran = (int)$this->order->city_id === 360;

        \Log::info('City check', [
            'order_id' => $this->order->id,
            'city_id' => $this->order->city_id,
            'is_tehran' => $isTehran,
        ]);

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

        \Log::info('SMS text generated', [
            'order_id' => $this->order->id,
            'sms_text' => $smsText,
        ]);

        $smsResult = app(SmsService::class)
            ->send($this->order->phone, $smsText);

        \Log::info('SMS send result', [
            'order_id' => $this->order->id,
            'result' => $smsResult,
        ]);

        try {
            app(TelegramService::class)->sendToConfirmedChannel($this->order);

            \Log::info('Telegram sent successfully', [
                'order_id' => $this->order->id,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Telegram failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);
        }

        \Log::info('SendConfirmedOrderNotification END', [
            'order_id' => $this->order->id,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        \Log::error('SendConfirmedOrderNotification failed', [
            'order_id' => $this->order->id,
            'error' => $e->getMessage(),
        ]);
    }
}
