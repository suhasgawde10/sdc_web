<?php

use App\Http\Controllers\CardActionController;
use App\Http\Controllers\CardAjaxController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/password', function () {
    // dd(Hash::make('Jwebmaker@775533'));
// });


Route::controller(WebsiteController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/about-us', 'aboutUs');
    Route::get('/themes', 'themes');
    Route::get('/pricing', 'pricing');
    Route::get('/contact-us', 'contactUs');
    
    Route::get('/terms-of-dealership', 'termsOfDealership');
    Route::get('/terms-and-conditions', 'termsAndConditions');
    Route::get('/privacy-policy', 'privacyPolicy');
    Route::get('/terms-of-use', 'termsOfUse');
    Route::get('/refund-and-return-policy', 'refundAndReturnPolicy');
    Route::get('/site-map', 'siteMap');
});





Route::get('/account/{any}', function ($any) {
    // Define the base path to your existing-projects directory
    $basePath = public_path('account');

    // Build the full path to the requested file
    $filePath = $basePath . '/' . $any;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Serve the requested PHP file
        return response()->file($filePath);
    } else {
        // Return a 404 response if the file doesn't exist
        abort(404);
    }
})->where('any', '.*');
Route::controller(CardController::class)->group(function () {
    Route::get('/home/{userId}', 'showHome');
    Route::get('/company/{userId}', 'showCompany');
    Route::get('/gallery/{userId}', 'showGallery');
    Route::get('/clients/{userId}', 'showClients');
    Route::get('/payments/{userId}', 'showPayments');
    Route::get('/products/{userId}', 'showProducts');
    Route::get('/services/{userId}', 'showServices');
});

Route::controller(CardAjaxController::class)->group(function () {
    Route::get('/get-product-details/{id}', 'getProductDetails');
});

Route::controller(CardActionController::class)->group(function () {
    Route::post('/send-enquiry', 'sendEnquiry');
    Route::get('/save-contact/{id}', 'saveContact');
});



Route::get('/all-cache', function () {
    Artisan::call('route:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return 'All cache cleared';
});

Route::get('/{any}', function ($any) {
    // Define the base path to your existing-projects directory
    $basePath = public_path('account');

    // Build the full path to the requested file
    $filePath = $basePath . '/' . $any;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Serve the requested PHP file
        return response()->file($filePath);
    } else {
        // Return a 404 response if the file doesn't exist
        abort(404);
    }
})->where('any', '.*');