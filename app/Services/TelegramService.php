<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    private string $token;
    private string $chatId;
    private string $confirmedChatId;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->chatId = config('services.telegram.chat_id');
        $this->confirmedChatId = config('services.telegram.confirmed_chat_id');
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
            )->post("https://api.telegram.org/bot{$this->token}/sendPhoto", [
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
                "https://api.telegram.org/bot{$this->token}/sendMediaGroup",
                $multipart
            );

            $messages = $response->json('result');
            $lastMessageId = end($messages)['message_id'] ?? null;

            if ($lastMessageId) {
                Http::post("https://api.telegram.org/bot{$this->token}/editMessageReplyMarkup", [
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

    // متد دوم: ارسال به چنل تایید شده‌ها
    public function sendToConfirmedChannel(Order $order): void
    {
        $isTehran = (int)$order->province_id === 8;

        if ($isTehran) {
            $text = "❌ تهران ❌\n";
            $text .= "روش ارسال: {$order->shipping_method}\n";

            if (!empty($order->shipping_time)) {
                $text .= "بازه زمانی ارسال: {$order->shipping_time}\n";
            }

            $text .= "\n{$order->address}\n";
            $text .= "{$order->full_name}\n";
            $text .= "{$order->phone}\n";
        } else {
            // نام استان و شهر از رابطه
            $provinceName = optional($order->province)->title ?? '';
            $cityName = optional($order->city)->title ?? '';

            $text = "{$provinceName} {$cityName}\n";
            $text .= "{$order->address}\n";
            $text .= "{$order->full_name}\n";
            $text .= "{$order->phone}\n";
        }

        // محصول اصلی
        $productLine = optional($order->product)->name ?? 'محصول';
        $text .= "\n{$order->prd_qty} بسته {$productLine}\n";

        // محصولات مکمل
        if (!empty($order->order_caption)) {
            $addons = is_string($order->order_caption)
                ? json_decode($order->order_caption, true)
                : $order->order_caption;

            if (is_array($addons)) {
                foreach ($addons as $addon) {
                    $text .= "{$addon['qty']} عدد {$addon['name']}\n";
                }
            }
        }

        Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $this->confirmedChatId,
            'text' => $text,
        ]);
    }

    // ادیت پیام اصلی: حذف دکمه‌ها و ادیت کپشن/متن
    public function editMessageAfterAction(string $chatId, string $messageId, string $statusText): void
    {
        // حذف inline keyboard
        Http::post("https://api.telegram.org/bot{$this->token}/editMessageReplyMarkup", [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);

        // ادیت کپشن (برای پیام‌های با عکس)
        $captionResponse = Http::post("https://api.telegram.org/bot{$this->token}/editMessageCaption", [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'caption' => $statusText,
        ]);

        // اگه پیام متنی بود (نه عکس)، editMessageText رو امتحان کن
        if (!$captionResponse->json('ok')) {
            Http::post("https://api.telegram.org/bot{$this->token}/editMessageText", [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $statusText,
            ]);
        }
    }

    public function answerCallback(string $callbackQueryId, string $text): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/answerCallbackQuery", [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
        ]);
    }

    public function setWebhook(string $url): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/setWebhook", [
            'url' => $url,
        ]);
    }
}
