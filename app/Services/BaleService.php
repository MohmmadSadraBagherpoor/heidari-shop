<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class BaleService
{
    private string $token;
    private string $chatId;
    private string $confirmedChatId;

    public function __construct()
    {
        $this->token = config('services.bale.token');
        $this->chatId = config('services.bale.chat_id');
        $this->confirmedChatId = config('services.bale.confirmed_chat_id');
    }

    // متد اول: ارسال سفارش جدید با دکمه تایید/رد
    public function sendOrder(array $data): array
    {
        $caption = "🛒 سفارش جدید\n\n";
        $caption .= "👤 نام: {$data['full_name']}\n";
        $caption .= "📱 موبایل: {$data['phone']}\n";
        $caption .= "📍 آدرس: {$data['address']}\n";
        $caption .= "📦 محصول اصلی: {$data['product']} × {$data['qty']}\n";

        if (!empty($data['addons'])) {
            $caption .= "\n🔸 محصولات مکمل:\n";
            foreach ($data['addons'] as $addon) {
                $caption .= "  - {$addon['name']} × {$addon['qty']}\n";
            }
        }

        $caption .= "\n💰 مبلغ کل: {$data['total']} تومان\n";
        $caption .= "🚚 روش ارسال: {$data['shipping']}\n";
        $caption .= "🔑 کد سفارش: {$data['order_code']}\n";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ تایید', 'callback_data' => 'approve_' . $data['order_id']],
                    ['text' => '❌ رد', 'callback_data' => 'reject_' . $data['order_id']],
                ]
            ]
        ];

        if (count($data['images']) === 1) {
            $response = Http::attach(
                'photo',
                file_get_contents(storage_path('app/public/' . $data['images'][0])),
                'receipt.jpg'
            )->post("https://tapi.bale.ai/bot{$this->token}/sendPhoto", [
                'chat_id' => $this->chatId,
                'caption' => $caption,
                'reply_markup' => json_encode($keyboard),
            ]);

            return [
                'message_id' => $response->json('result.message_id'),
                'chat_id' => $this->chatId,
            ];

        } else {
            $multipart = [];
            $media = [];

            foreach ($data['images'] as $index => $imagePath) {
                $fileKey = 'file' . $index;
                $isLast = $index === count($data['images']) - 1;

                $mediaItem = [
                    'type' => 'photo',
                    'media' => 'attach://' . $fileKey,
                ];

                if ($isLast) {
                    $mediaItem['caption'] = $caption;
                }

                $media[] = $mediaItem;

                $multipart[] = [
                    'name' => $fileKey,
                    'contents' => file_get_contents(storage_path('app/public/' . $imagePath)),
                    'filename' => 'receipt_' . $index . '.jpg',
                ];
            }

            $multipart[] = ['name' => 'chat_id', 'contents' => $this->chatId];
            $multipart[] = ['name' => 'media', 'contents' => json_encode($media)];

            $response = Http::asMultipart()->post(
                "https://tapi.bale.ai/bot{$this->token}/sendMediaGroup",
                $multipart
            );

            $messages = $response->json('result');
            $lastMessageId = end($messages)['message_id'] ?? null;

            if ($lastMessageId) {
                Http::post("https://tapi.bale.ai/bot{$this->token}/editMessageReplyMarkup", [
                    'chat_id' => $this->chatId,
                    'message_id' => $lastMessageId,
                    'reply_markup' => json_encode($keyboard),
                ]);
            }

            return [
                'message_id' => $lastMessageId,
                'chat_id' => $this->chatId,
            ];
        }
    }

    // متد دوم: ارسال به چنل تایید شده‌ها بدون دکمه
    public function sendToConfirmedChannel(Order $order): void
    {
        $text = "✅ سفارش تایید شده\n\n";
        $text .= "👤 نام: {$order->full_name}\n";
        $text .= "📱 موبایل: {$order->phone}\n";
        $text .= "📍 آدرس: {$order->address}\n";
        $text .= "🔑 کد سفارش: {$order->order_code}\n";
        $text .= "💰 مبلغ: " . number_format($order->total_price) . " تومان\n";
        $text .= "🚚 روش ارسال: {$order->shipping_method}\n";

        Http::post("https://tapi.bale.ai/bot{$this->token}/sendMessage", [
            'chat_id' => $this->confirmedChatId,
            'text' => $text,
        ]);
    }

    public function editCaption(string $chatId, string $messageId, string $text): void
    {
        Http::post("https://tapi.bale.ai/bot{$this->token}/editMessageReplyMarkup", [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);

        Http::post("https://tapi.bale.ai/bot{$this->token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_to_message_id' => $messageId,
        ]);
    }

    public function answerCallback(string $callbackQueryId, string $text): void
    {
        Http::post("https://tapi.bale.ai/bot{$this->token}/answerCallbackQuery", [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
        ]);
    }

    public function setWebhook(string $url): void
    {
        Http::post("https://tapi.bale.ai/bot{$this->token}/setWebhook", [
            'url' => $url,
        ]);
    }
}
