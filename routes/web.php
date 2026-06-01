<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuyRequestController;
use App\Http\Controllers\FarmerAuthController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('products.index');
});

// AI Crop Price Prediction Page
Route::get('/price-prediction', function () {
    return view('price_prediction');
})->name('price.prediction');


Route::get('/my-orders', function () {

    $query = DB::table('buy_requests')
        ->where('user_id', auth()->id());

    // 🔹 Date filter
    if (request('date')) {
        $query->whereDate('created_at', request('date'));
    }

    $orders = $query->orderBy('id', 'desc')->get();

    return view('my_orders', compact('orders'));

})->name('my_orders');

    
// View products (Everyone)
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

/* IMPORTANT — CREATE MUST COME FIRST */
Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');

/* THEN SHOW ROUTE */
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->name('products.show');


/*
|--------------------------------------------------------------------------
| USER AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/user/login', [UserAuthController::class, 'showLogin'])
    ->name('user.login');

Route::post('/user/login', [UserAuthController::class, 'login'])
    ->name('user.login.submit');

Route::get('/user/register', [UserAuthController::class, 'showRegister'])
    ->name('user.register');

Route::post('/user/register', [UserAuthController::class, 'register'])
    ->name('user.register.submit');

Route::get('/user/logout', [UserAuthController::class, 'logout'])
    ->name('user.logout');


/*
|--------------------------------------------------------------------------
| USER PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('user.auth')->group(function () {

    
   

    Route::get('/my-requests', [BuyRequestController::class, 'myRequests'])
        ->name('user.requests');

    Route::get('/buy-request/{id}', [BuyRequestController::class, 'create'])
    ->name('buy.request');

    Route::post('/buy-request/{id}', [BuyRequestController::class, 'store'])
    ->name('buy.request.store');
});


/*
|--------------------------------------------------------------------------
| FARMER AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/farmer/login', [FarmerAuthController::class, 'showLogin'])
    ->name('farmer.login');

Route::post('/farmer/login', [FarmerAuthController::class, 'login'])
    ->name('farmer.login.submit');

Route::get('/farmer/register', [FarmerAuthController::class, 'showRegister'])
    ->name('farmer.register');

Route::post('/farmer/register', [FarmerAuthController::class, 'register'])
    ->name('farmer.register.submit');

Route::get('/farmer/logout', [FarmerAuthController::class, 'logout'])
    ->name('farmer.logout');


/*
|--------------------------------------------------------------------------
| FARMER PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('farmer.auth')->group(function () {

    // Dashboard
    Route::get('/farmer/dashboard', [FarmerAuthController::class, 'dashboard'])
        ->name('farmer.dashboard');
    Route::get('/farmer/orders', [BuyRequestController::class, 'farmerOrders'])
    ->name('farmer.orders');
    
    
    // Product Management
   Route::middleware('farmer.auth')->group(function () {

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/{id}/edit',[ProductController::class,'edit'])->name('products.edit');

    Route::put('/products/{id}',[ProductController::class,'update'])->name('products.update');

    Route::delete('/products/{id}',[ProductController::class,'destroy'])->name('products.destroy');
    Route::get('/ai-price',[ProductController::class,'aiPriceSuggestion']);

});
    // Buy Requests
    Route::get('/buy-requests', [BuyRequestController::class, 'farmerRequests'])
    ->name('buy.requests.index'); 

    Route::post('/buy-request/{id}/accept', [BuyRequestController::class, 'accept'])
        ->name('buy.request.accept');

    Route::post('/buy-request/{id}/reject', [BuyRequestController::class, 'reject'])
        ->name('buy.request.reject');
});


/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::get('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('admin.auth')->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/farmers', [AdminController::class, 'farmers'])
        ->name('admin.farmers');

    Route::get('/admin/products', [AdminController::class, 'products'])
        ->name('admin.products');

    Route::get('/admin/requests', [AdminController::class, 'requests'])
        ->name('admin.requests');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/admin/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');

    Route::post('/admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    // Block / Unblock Farmer
    Route::get('/admin/farmer/{id}/block', function ($id) {
        $farmer = \App\Models\Farmer::findOrFail($id);
        $farmer->is_blocked = 1;
        $farmer->save();
        return back()->with('success', 'Farmer blocked successfully');
    })->name('admin.farmer.block');

    Route::get('/admin/farmer/{id}/unblock', function ($id) {
        $farmer = \App\Models\Farmer::findOrFail($id);
        $farmer->is_blocked = 0;
        $farmer->save();
        return back()->with('success', 'Farmer unblocked successfully');
    })->name('admin.farmer.unblock');

    // Block / Unblock Product
    Route::get('/admin/product/{id}/block', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    $product->status = 'Blocked';
    $product->save();

    return back()->with('success', 'Product blocked');
})->name('admin.product.block');

    Route::get('/admin/product/{id}/unblock', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    $product->status = 'Available';
    $product->save();

    return back()->with('success', 'Product unblocked');
})->name('admin.product.unblock');
});

Route::post('/chatbot',[App\Http\Controllers\ChatbotController::class,'ask']);
Route::get('/admin/verify-farmer/{id}', [AdminController::class, 'verifyFarmer']);
Route::post('/admin/update-availability/{id}', [AdminController::class, 'updateAvailability']);
Route::post('/cancel-request/{id}', [BuyRequestController::class, 'cancel']);
Route::get('/health', function () {
    return response('OK', 200);
});