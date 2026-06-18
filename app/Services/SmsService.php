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
        $response = Http::asForm()->post('https://rest.payamak-panel.com/api/SendSMS/SendSMS', [
            'username' => $this->username,
            'password' => $this->password,
            'to' => $phone,
            'from' => $this->sender,
            'text' => $message,
        ]);

        $result = $response->json();

        // اگه Value عدد مثبت بود یعنی موفق بوده
        return isset($result['Value']) && (int)$result['Value'] > 0;
    }
}
