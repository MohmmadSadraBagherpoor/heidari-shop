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

//Route::get('/test', function () {
//
//    $response = Http::get('https://portal.amootsms.com/rest/SendSimple', [
//        'Token' => 'A8621657EE183B36DF22C0ACEAC2E8D092F8A9C1',
//        'SendDateTime' => now('Asia/Tehran')->format('c'),
//        'SMSMessageText' => 'سلام من صدرام و این یک پیام کاملا تستی هستش',
//        'LineNumber' => 'public',
//        'Mobiles' => '9034624472',
//    ]);
//
//    dd($response->json());
//});
//
//Route::get('/test', function () {
//    $sendDateTime = Carbon::now('Asia/Tehran')->toIso8601String();
//
//    $response = Http::get('https://portal.amootsms.com/rest/SendSimple', [
//        'Token' => env('AMOOT_TOKEN'),
//        'SendDateTime' => $sendDateTime,
//        'SMSMessageText' => 'سلام من صدرام و این یک پیام کاملا تستی هستش',
//        'LineNumber' => 'public',
//        'Mobiles' => '9335106459'
//    ]);
//
//    return $response->json();
//});
//
//Route::get('/delivery', function () {
//
//    $response = Http::get(
//        'https://portal.amootsms.com/rest/GetDelivery',
//        [
//            'Token' => 'A8621657EE183B36DF22C0ACEAC2E8D092F8A9C1',
////            'MessageID' => 702722605, //1
//            'MessageID' => 702725902, //2
////            'MessageID' => 702725966, //3
////            'MessageID' => 702725994, //4
//        ]
//    );
//
//    dd($response->json());
//});
//
//Route::get('/sms-test', function () {

//    $curl = curl_init();
//
//    curl_setopt_array($curl, [
//        CURLOPT_URL => 'https://portal.amootsms.com/rest/SendSimple',
//        CURLOPT_RETURNTRANSFER => true,
//        CURLOPT_POST => true,
//        CURLOPT_POSTFIELDS => http_build_query([
//            'Token' => 'xxxxx',
//            'Mobiles' => '09034624472',
//            'SendDateTime' => 0,
//            'SMSMessageText' => 'تست پیامک لاراول',
//            'LineNumber' => 'public',
//        ]),
//        CURLOPT_HTTPHEADER => [
//            'Content-Type: application/x-www-form-urlencoded'
//        ]
//    ]);
//
//    $response = curl_exec($curl);
//
//    curl_close($curl);
//
//    dd(json_decode($response, true));
//});

//Route::get('/account-status', function () {
//
//    $response = Http::get(
//        'https://portal.amootsms.com/rest/AccountStatus',
//        [
//            'Token' => 'A8621657EE183B36DF22C0ACEAC2E8D092F8A9C1'
//        ]
//    );
//
//    dd($response->json());
//});

Route::post('/bale/webhook', [BaleWebhookController::class, 'handle']);

Route::get('/bale/set-webhook', function () {
    app(\App\Services\BaleService::class)->setWebhook(url('/bale/webhook'));
    return 'webhook set!';
});

Route::get('/test-sms', function () {
    $response = Http::asForm()->post('https://rest.payamak-panel.com/api/SendSMS/SendSMS', [
        'username' => config('services.sms.username'),
        'password' => config('services.sms.password'),
        'to' => '09034624472',
        'from' => config('services.sms.sender'),
        'text' => 'تست پیامک - سفارش شما تایید شد',
    ]);

    dd($response->json());
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


