<?php

use App\Http\Controllers\BaleWebhookController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('checkout.index'));
});

Route::get('foo', function () {
    return Artisan::call('storage:link');
});

Route::get('/checkout', [OrderController::class, 'create'])
    ->name('checkout.index');

Route::post('/checkout', [OrderController::class, 'store'])
    ->name('order.store');

Route::get('/get-cities/{province}', function ($province) {
    $cities = \App\Models\City::where('parent', $province)
        ->select('id', 'title')
        ->get();

    return $cities;
});

Route::get('/telegram/set-webhook', function () {
    app(\App\Services\TelegramService::class)->setWebhook(url('/telegram/webhook'));
    return 'webhook set!';
});

Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramWebhookController::class, 'handle']);

Route::get('/test-sms', function () {
    $result = app(\App\Services\SmsService::class)->send('+989034624472', 'تست پیامک');
    dd(response()->json(['result' => $result]));
});

Route::get('/test-sms-delivery', function () {
    $recId = '4731885462082896494';

    $response = Http::asForm()->post('https://rest.payamak-panel.com/api/SendSMS/GetDeliveries2', [
        'username' => config('services.sms.username'),
        'password' => config('services.sms.password'),
        'recId' => $recId,
    ]);

    dd($response->json());
});

