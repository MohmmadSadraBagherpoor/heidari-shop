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
        $this->token           = config('services.telegram.token');
        $this->chatId          = config('services.telegram.chat_id');
        $this->confirmedChatId = config('services.telegram.confirmed_chat_id');
    }

    // ==========================================
    // ۱. ارسال سفارش جدید
    // ==========================================
    public function sendOrder(array $data): array
    {
        // --- کپشن فاکتور ---
        $unitPrice  = number_format($data['unit_price']);
        $totalPrice = number_format($data['total_price']);
        $qty        = $data['qty'];
        $productName = $data['product'];

        $caption = "🧾 فاکتور سفارش\n";
        $caption .= "🧪 {$qty} بسته {$productName}\n";
        $caption .= "💸 قیمت هر بسته: {$unitPrice} تومان\n";
        $caption .= "💰 مبلغ نهایی کل سفارش: {$totalPrice} تومان";

        // --- متن آدرس ---
        $isTehran = (int)$data['city_id'] === 360;

        if ($isTehran) {
            $addressText = "❌ تهران ❌\n";
            $addressText .= "روش ارسال: {$data['shipping']}\n";
            if (!empty($data['shipping_time'])) {
                $addressText .= "بازه زمانی ارسال: {$data['shipping_time']}\n";
            }
        } else {
            $addressText = "{$data['province']} {$data['city']}\n";
        }

        $addressText .= "\n{$data['address']}\n";
        $addressText .= "{$data['full_name']}\n";
        $addressText .= "{$data['phone']}\n\n";
        $addressText .= "{$qty} بسته {$productName}\n";

        if (!empty($data['addons'])) {
            foreach ($data['addons'] as $addon) {
                $addressText .= "{$addon['qty']} عدد {$addon['name']}\n";
            }
        }

        $keyboard = [
            'inline_keyboard' => [[
                ['text' => '✅ تایید', 'callback_data' => 'approve_' . $data['order_id']],
                ['text' => '❌ رد',    'callback_data' => 'reject_'  . $data['order_id']],
            ]]
        ];

        // --- ارسال عکس(ها) بدون دکمه ---
        if (count($data['images']) === 1) {
            Http::attach(
                'photo',
                file_get_contents(storage_path('app/public/' . $data['images'][0])),
                'receipt.jpg'
            )->post("https://api.telegram.org/bot{$this->token}/sendPhoto", [
                'chat_id' => $this->chatId,
                'caption' => $caption,
            ]);

        } else {
            $multipart = [];
            $media     = [];

            foreach ($data['images'] as $index => $imagePath) {
                $fileKey = 'file' . $index;
                $isLast  = $index === count($data['images']) - 1;

                $mediaItem = ['type' => 'photo', 'media' => 'attach://' . $fileKey];
                if ($isLast) $mediaItem['caption'] = $caption;

                $media[] = $mediaItem;
                $multipart[] = [
                    'name'     => $fileKey,
                    'contents' => file_get_contents(storage_path('app/public/' . $imagePath)),
                    'filename' => 'receipt_' . $index . '.jpg',
                ];
            }

            $multipart[] = ['name' => 'chat_id', 'contents' => $this->chatId];
            $multipart[] = ['name' => 'media',   'contents' => json_encode($media)];

            Http::asMultipart()->post(
                "https://api.telegram.org/bot{$this->token}/sendMediaGroup",
                $multipart
            );
        }

        // --- ارسال پیام آدرس با دکمه تایید/رد ---
        $msgResponse = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id'      => $this->chatId,
            'text'         => $addressText,
            'reply_markup' => json_encode($keyboard),
        ]);

        return [
            'message_id' => $msgResponse->json('result.message_id'),
            'chat_id'    => $this->chatId,
        ];
    }

    // ==========================================
    // ۲. ارسال به چنل تایید شده‌ها
    // ==========================================
    public function sendToConfirmedChannel(Order $order): void
    {
        $isTehran = (int)$order->city_id === 360;

        if ($isTehran) {
            $text  = "❌ تهران ❌\n";
            $text .= "روش ارسال: {$order->shipping_method}\n";
            if (!empty($order->shipping_time)) {
                $text .= "بازه زمانی ارسال: {$order->shipping_time}\n";
            }
        } else {
            $provinceName = optional($order->province)->title ?? '';
            $cityName     = optional($order->city)->title    ?? '';
            $text = "{$provinceName} {$cityName}\n";
        }

        $text .= "\n{$order->address}\n";
        $text .= "{$order->full_name}\n";
        $text .= "{$order->phone}\n\n";
        $text .= "{$order->prd_qty} بسته " . (optional($order->product)->name ?? 'محصول') . "\n";

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
            'text'    => $text,
        ]);
    }

    // ==========================================
    // ۳. تایید سفارش: پیام جدید + حذف دکمه‌ها
    // ==========================================
    public function handleApprove(string $chatId, string $messageId, string $callbackQueryId, Order $order): void
    {
        // حذف دکمه‌های پیام آدرس
        Http::post("https://api.telegram.org/bot{$this->token}/editMessageReplyMarkup", [
            'chat_id'      => $chatId,
            'message_id'   => $messageId,
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);

        // پیام تایید جدید
        $approvedBy = "@Prphairheidari_bot";
        Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $chatId,
            'text'    => "سفارش شماره {$order->order_code} توسط {$approvedBy} تایید شد. ✅",
        ]);

        $this->answerCallback($callbackQueryId, 'سفارش تایید شد ✅');
    }

    // ==========================================
    // ۴. رد سفارش: فقط حذف دکمه‌ها
    // ==========================================
    public function handleReject(string $chatId, string $messageId, string $callbackQueryId): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/editMessageReplyMarkup", [
            'chat_id'      => $chatId,
            'message_id'   => $messageId,
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);

        $this->answerCallback($callbackQueryId, 'سفارش رد شد ❌');
    }

    public function answerCallback(string $callbackQueryId, string $text): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/answerCallbackQuery", [
            'callback_query_id' => $callbackQueryId,
            'text'              => $text,
        ]);
    }

    public function setWebhook(string $url): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/setWebhook", [
            'url' => $url,
        ]);
    }
}
