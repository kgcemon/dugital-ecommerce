<?php

use App\Http\Controllers\admin\CategoriesController;
use App\Http\Controllers\admin\CodesController;
use App\Http\Controllers\admin\PaymentMethodSettingController;
use App\Http\Controllers\admin\PaymentSMSController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\VariantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\user\DepositController;
use App\Http\Controllers\user\OrderController;
use App\Http\Controllers\user\ReviewController;
use App\Http\Controllers\user\SiteHomeScreenController;
use App\Http\Controllers\user\SiteProductsScreenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\OrdersController;
use App\Http\Controllers\ProfileController;

Route::get('auto-top-up-cron',[CronJobController::class,'freeFireAutoTopUpJob']);

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/review/{slug}', [ReviewController::class, 'reviewByProduct'])->name('review');
Route::get('/add-review/{slug}', [ReviewController::class, 'show']);
Route::post('/add-review', [ReviewController::class, 'store'])->name('review.store');

//admin
Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('adminLogin');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::get('/', [SiteHomeScreenController::class, 'index'])->name('home');
Route::get('/product/{slug}', [SiteProductsScreenController::class, 'index'] )->name('product');
Route::post('add-order', [OrderController::class, 'addOrder'])->name('addOrder');
Route::get('thank-you/{uid}', [OrderController::class, 'thankYouPage'])->name('thankYouPage');

// Profile Page
Route::get('profile', [ProfileController::class, 'show'])->middleware('auth:web')->name('profile');
Route::get('my-orders', [OrderController::class, 'myOrders'])->middleware('auth:web')->name('myOrders');
Route::get('order/{id}', [OrderController::class, 'orderView'])->middleware('auth:web')->name('orderView');
Route::get('deposit',[DepositController::class, 'deposit'])->middleware('auth:web')->name('deposit');
Route::post('add-money',[DepositController::class, 'depositStore'])->middleware('auth:web');

// Authenticated Admin Routes
Route::middleware('auth:admin')->prefix('admin')->as('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);


    // Orders
    Route::get('orders', [\App\Http\Controllers\admin\OrdersController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'update'])->name('orders.update');
    Route::get('admin/orders/{order}', [\App\Http\Controllers\admin\OrdersController::class, 'show'])->name('admin.orders.show');
    Route::post('admin/orders/update/{id}', [\App\Http\Controllers\admin\OrdersController::class, 'edit'])->name('orders.edits');
    Route::get('admin/orders/edit/{id}', [\App\Http\Controllers\admin\OrdersController::class, 'editFrom'])->name('orders.edit');
    Route::post('/admin/orders/bulk-action', [OrdersController::class, 'bulkAction'])->name('orders.bulkAction');


    Route::resource('variant', VariantController::class);
    Route::resource('categories', CategoriesController::class);
    Route::get('variants/{id}', [VariantController::class, 'variant'])->name('variant');


    Route::resource('sliders', SliderController::class);
    Route::resource('users', UsersController::class);


    //codes
    Route::resource('codes', CodesController::class);
    Route::get('code/{id}', [CodesController::class, 'singleCode']);


    //payment Setting
    Route::resource('payment-methods', PaymentMethodSettingController::class);
    Route::post('payment-methods/{id}/toggle-status', [PaymentMethodSettingController::class, 'toggleStatus'])->name('payment-methods.toggleStatus');
    Route::post('payment-methods/{id}/copy', [PaymentMethodSettingController::class, 'copyNumber'])->name('payment-methods.copy');


    //paymentSMS
    Route::get('/payment-sms', [PaymentSmsController::class, 'index'])->name('sms');
    Route::post('/sms/add', [PaymentSmsController::class, 'addSms'])->name('sms.add');
    Route::put('/sms/update-status', [PaymentSmsController::class, 'updateStatus'])->name('sms.update-status');
    Route::delete('/sms/{id}', [PaymentSmsController::class, 'delete'])->name('sms.delete');


    //offer
    Route::get('/send-offer', [OfferController::class, 'index'])->name('offer.index');
    Route::post('/send-offer', [OfferController::class, 'send'])->name('offer.sends');


});

// Fallback Route for 404
//Route::fallback(function () {
//    return redirect()->route('admin.dashboard')->with('error', 'Page not found.');
//});


Route::get('/{any}', function(){
    return view('user.master');
})->where('any','^(?!css|js|images|manifest\.json|service-worker\.js).*$');


//user Route

