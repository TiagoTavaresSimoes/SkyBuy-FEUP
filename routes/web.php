<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/home');

Route::get('/', [HomeController::class, 'show'])->name('home');
Route::post('/search', [HomeController::class, 'search'])->name('search');

Route::get('/profile', 'UserController@profile')->name('profile');

Route::get('/product/{id_product}/{name}/{price}/{size}/{stock}/{brand}/{rating}/{description}', function($id_product, $name, $price, $size, $stock, $brand, $rating, $description){
    return view('product',[
        'id_product' => $id_product,
        'name' => $name,
        'price' => $price,
        'size' => $size,
        'stock' => $stock,
        'brand' => $brand,
        'rating' => $rating,
        'description' => $description
    ]);
});

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/users/{id}', [UserProfileController::class, 'showProfile'])->name('users.showProfile');
Route::get('/home', [HomeController::class, 'show'])->name('home');
Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
Route::get('/home', [HomeController::class, 'show'])->name('home');
Route::get('/login', 'AuthController@login');

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/account', [AccountController::class, 'index'])->middleware('auth')->name('account');

Route::get('/account', [AccountController::class, 'index'])->middleware('auth')->name('account');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

