<?php

namespace App\Http\Controllers;

use App\Jobs\SendConfirmedOrderNotification;
use App\Models\Order;
use App\Services\BaleService;
use Illuminate\Http\Request;

class BaleWebhookController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\Response
    {
        \Log::info('BALE_RAW', ['body' => $request->getContent()]);

        $data = $request->all();

        if (!isset($data['callback_query'])) {
            return response('ok');
        }

        $callbackQuery = $data['callback_query'];
        $callbackData = $callbackQuery['data'];
        $messageId = $callbackQuery['message']['message_id'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $callbackQueryId = $callbackQuery['id'];

        $bale = app(BaleService::class);

        if (str_starts_with($callbackData, 'approve_')) {
            $orderId = str_replace('approve_', '', $callbackData);
            $order = Order::find($orderId);

            if ($order) {
                $order->update([
                    'status' => 'confirmed',
                    'confirmed_rejected_at' => now(),
                ]);

                // ادیت پیام در چنل ثبت سفارشات
                if ($order->bale_message_id) {
                    $bale->editCaption(
                        $order->bale_chat_id,
                        $order->bale_message_id,
                        '✅ این سفارش تایید شد'
                    );
                }

                $bale->answerCallback($callbackQueryId, 'سفارش تایید شد');

                SendConfirmedOrderNotification::dispatch($order);
            }
        }

        if (str_starts_with($callbackData, 'reject_')) {
            $orderId = str_replace('reject_', '', $callbackData);
            $order = Order::find($orderId);

            if ($order) {
                $order->update([
                    'status' => 'rejected',
                    'confirmed_rejected_at' => now(),
                ]);

                if ($order->bale_message_id) {
                    $bale->editCaption(
                        $order->bale_chat_id,
                        $order->bale_message_id,
                        '❌ این سفارش رد شد'
                    );
                }

                $bale->answerCallback($callbackQueryId, 'سفارش رد شد');
            }
        }

        return response('ok');
    }
}
