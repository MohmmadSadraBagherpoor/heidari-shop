<?php

namespace App\Http\Controllers;

use App\Jobs\SendConfirmedOrderNotification;
use App\Models\Order;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\Response
    {
        \Log::info('TELEGRAM_RAW', ['body' => $request->getContent()]);

        $data = $request->all();

        if (!isset($data['callback_query'])) {
            return response('ok');
        }

        $callbackQuery  = $data['callback_query'];
        $callbackData   = $callbackQuery['data'];
        $messageId      = $callbackQuery['message']['message_id'];
        $chatId         = $callbackQuery['message']['chat']['id'];
        $callbackQueryId = $callbackQuery['id'];

        $telegram = app(TelegramService::class);

        if (str_starts_with($callbackData, 'approve_')) {
            $orderId = str_replace('approve_', '', $callbackData);
            $order   = Order::find($orderId);

            if ($order) {
                $order->update([
                    'status'                => 'confirmed',
                    'confirmed_rejected_at' => now(),
                ]);

                // ادیت همون پیام اصلی: حذف دکمه‌ها + نوشتن وضعیت
                $telegram->editMessageAfterAction(
                    (string) $chatId,
                    (string) $messageId,
                    "✅ این سفارش تایید شد"
                );

                $telegram->answerCallback($callbackQueryId, 'سفارش تایید شد ✅');

                SendConfirmedOrderNotification::dispatch($order);
            }
        }

        if (str_starts_with($callbackData, 'reject_')) {
            $orderId = str_replace('reject_', '', $callbackData);
            $order   = Order::find($orderId);

            if ($order) {
                $order->update([
                    'status'                => 'rejected',
                    'confirmed_rejected_at' => now(),
                ]);

                $telegram->editMessageAfterAction(
                    (string) $chatId,
                    (string) $messageId,
                    "❌ این سفارش رد شد"
                );

                $telegram->answerCallback($callbackQueryId, 'سفارش رد شد ❌');
            }
        }

        return response('ok');
    }
}
