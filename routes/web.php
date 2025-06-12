<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Storage;

Route::get('/', [ItemController::class, 'index'])->name('home');

// Маршруты для категорий
Route::resource('categories', CategoryController::class);

// Маршруты для предметов
Route::resource('items', ItemController::class);
Route::get('items/category/{category}', [ItemController::class, 'category'])->name('items.category');
Route::get('search', [ItemController::class, 'search'])->name('items.search');
Route::post('items/{item}/remove-image', [ItemController::class, 'removeImage'])->name('items.remove-image');

// Маршруты для обменов
Route::get('exchanges/create', [ExchangeController::class, 'create'])->name('exchanges.create');
Route::resource('exchanges', ExchangeController::class)->except(['create']);
Route::post('exchanges/{exchange}/accept', [ExchangeController::class, 'accept'])->name('exchanges.accept');
Route::post('exchanges/{exchange}/reject', [ExchangeController::class, 'reject'])->name('exchanges.reject');
Route::post('exchanges/{exchange}/complete', [ExchangeController::class, 'complete'])->name('exchanges.complete');

// Маршруты для сообщений
Route::resource('messages', MessageController::class);
Route::get('messages/exchange/{exchange}', [MessageController::class, 'exchange'])->name('messages.exchange');
Route::post('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

// Маршруты для аутентификации
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Маршруты для профиля
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('items', ItemController::class);
    Route::delete('items/{item}/remove-image', [ItemController::class, 'removeImage'])->name('items.removeImage');
});

Route::get('/debug/images', function() {
    $items = \App\Models\Item::all();
    foreach ($items as $item) {
        echo "Item ID: " . $item->id . "<br>";
        echo "Title: " . $item->title . "<br>";
        echo "Images: " . print_r($item->images, true) . "<br>";
        echo "Storage URL: " . \Illuminate\Support\Facades\Storage::url($item->images[0] ?? '') . "<br>";
        echo "<hr>";
    }
});

Route::get('/test/image', function() {
    $path = 'items/iphone12.jpg';
    $url = Storage::url($path);
    $exists = Storage::disk('public')->exists($path);
    
    return response()->json([
        'path' => $path,
        'url' => $url,
        'exists' => $exists,
        'full_path' => storage_path('app/public/' . $path),
        'public_path' => public_path('storage/' . $path)
    ]);
});

Route::get('/test-images', function() {
    $items = \App\Models\Item::all();
    foreach ($items as $item) {
        echo "Item ID: {$item->id}<br>";
        echo "Title: {$item->title}<br>";
        echo "Images: " . json_encode($item->images) . "<br>";
        echo "First image path: " . ($item->images ? asset('storage/' . $item->images[0]) : 'No image') . "<br>";
        echo "<hr>";
    }
});

Route::get('/test-file-access', function() {
    $files = Storage::disk('public')->files('items');
    foreach ($files as $file) {
        echo "File: " . $file . "<br>";
        echo "URL: " . asset('storage/' . $file) . "<br>";
        echo "Exists: " . (Storage::disk('public')->exists($file) ? 'Yes' : 'No') . "<br>";
        echo "Size: " . Storage::disk('public')->size($file) . " bytes<br>";
        echo "<hr>";
    }
});

Route::get('/test-image-path', function() {
    $item = \App\Models\Item::find(1); // Замените 1 на ID вашего товара
    if ($item) {
        echo "Item ID: {$item->id}<br>";
        echo "Title: {$item->title}<br>";
        echo "Images: " . json_encode($item->images) . "<br>";
        echo "Storage path: " . storage_path('app/public/' . $item->images[0]) . "<br>";
        echo "Public path: " . public_path('storage/' . $item->images[0]) . "<br>";
        echo "URL: " . asset('public/storage/' . $item->images[0]) . "<br>";
        echo "File exists: " . (file_exists(storage_path('app/public/' . $item->images[0])) ? 'Yes' : 'No') . "<br>";
    }
});
