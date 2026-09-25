<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});


Route::get('/', [App\Http\Controllers\SiteController::class, 'index'])
    ->name('home');


// Admin redirect to user dashboard
Route::get('/admin', function () {
    return redirect()->route('user.home');
});

// Your existing routes below...
Route::get('/user/dashboard', [App\Http\Controllers\User\UserController::class, 'home'])->name('user.home');


// Akpay IPN
Route::post('/ipn/akpay', [App\Http\Controllers\Gateway\Akpay\ProcessController::class, 'ipn'])->name('ipn.akpay')
;

// Akpay IPN
Route::post('/ipn/akpay', [App\Http\Controllers\Gateway\Akpay\ProcessController::class, 'ipn'])->name('ipn.Akpay');


Route::post('/user/game/launch', [App\Http\Controllers\GameProviders\Api\ApiGameController::class, 'launch'])->name('user.game.launch');




Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    Route::get('/pwa/configuration', 'pwaConfiguration')->name('pwa.configuration');
    Route::get('/download-apk', 'downloadApk')->name('download.apk');
    Route::get('/app.apk', 'downloadApk');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::post('/subscribe', 'subscribe')->name('subscribe.post');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('games', 'games')->name('games');
    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

// এটি আপনার নতুন গেটওয়ের জন্য (Cowpay)
Route::post('ipn/cowpay', 'Gateway\Cowpay\ProcessController@ipn')->name('ipn.Cowpay');

// Bangladesh PayIn Return Route
Route::get('/bdpayin/return', 'Gateway\BdPayIn\ProcessController@returnSuccess')->name('bdpayin.return.success');
// Nagad Pay Return Route
Route::get('/bdpayinnagad/return', 'Gateway\BdPayInNagad\ProcessController@returnSuccess')->name('bdpayinnagad.return.success');
