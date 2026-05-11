<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CompanyController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Home (Public Landing)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    // لو مش مسجل دخول → Landing
    if (!Auth::check()) {
        return view('landing');
    }

    $user = Auth::user();
    $company = $user->company;

    // لو مفيش شركة → يرجع للـ landing
    if (!$company) {
        return view('landing');
    }

    // لو عنده شركة → يوديه للشركة
    return redirect()->route('company.show', $company->slug);
})->name('home');


/*
|--------------------------------------------------------------------------
| Product Details (Public)
|--------------------------------------------------------------------------
*/

Route::get('/product/{product}', function (Product $product) {
    return view('frontend.product.show', compact('product'));
})->name('product.show');


/*
|--------------------------------------------------------------------------
| Company Page (Protected)
|--------------------------------------------------------------------------
*/

Route::get('/company/{slug}', [CompanyController::class, 'show'])
    ->name('company.show')
    ->middleware(['auth', 'subscription']);

Route::get('/subscription-expired', function () {
    return view('subscription.expired');
})->name('subscription.expired');
/*
|--------------------------------------------------------------------------
| Protected System (Cart / Checkout / Profile)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // 🛒 Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // 💳 Checkout
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Dashboard (Basic)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
