<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    private string $username;
    private string $password;
    private string $sender;

    public function __construct()
    {
        $this->username = config('services.sms.username');
        $this->password = config('services.sms.password');
        $this->sender = config('services.sms.sender');
    }

    public function send(string $phone, string $message): bool
    {
        \Log::info('SMS REQUEST START', [
            'phone' => $phone,
            'message' => $message,
        ]);

        try {
            $response = Http::asForm()->post(
                'https://rest.payamak-panel.com/api/SendSMS/SendSMS',
                [
                    'username' => $this->username,
                    'password' => $this->password,
                    'to' => $phone,
                    'from' => $this->sender,
                    'text' => $message,
                ]
            );

            \Log::info('SMS API RESPONSE RAW', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $result = $response->json();

            \Log::info('SMS API RESPONSE JSON', [
                'result' => $result,
            ]);

            $success = isset($result['Value']) && (int)$result['Value'] > 0;

            \Log::info('SMS FINAL RESULT', [
                'success' => $success,
            ]);

            return $success;

        } catch (\Throwable $e) {
            \Log::error('SMS EXCEPTION', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
