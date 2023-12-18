<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminController;
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

//Route::get('/profile', [UserController::class, 'profile'])->name('profile');

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
//Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
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

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->middleware('auth')->name('checkout.index');

//Route::get('/categories', 'CategoryController@index')->name('categories.index');

Route::post('/order-process', [OrderController::class, 'process'])->name('order.process');



Route::get('/categories', [ProductController::class, 'index'])->name('categories.index');
Route::get('/categories/men', [ProductController::class, 'men'])->name('categories.men');
Route::get('/categories/women', [ProductController::class, 'women'])->name('categories.women');

Route::get('/men', [ProductController::class, 'men']);
Route::get('/women', [ProductController::class, 'women']);

Route::get('/admin', [AdminController::class, 'dashboard'])->middleware('is_admin');
Route::post('admin/user/block', [AdminController::class, 'blockUser'])->name('admin.block_user')->middleware('is_admin');
Route::post('admin/user/unblock', [AdminController::class, 'unblockUser'])->name('admin.unblock_user')->middleware('is_admin');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit')->middleware('auth');
Route::put('/account', [AccountController::class, 'update'])->name('account.update')->middleware('auth');

Route::get('/account/orders-history', [AccountController::class, 'ordersHistory'])->middleware('auth')->name('account.ordersHistory');

Route::post('/checkout/process-order', [CheckoutController::class, 'processOrder'])->name('checkout.processOrder');

//Route::get('/account/orders-history', [OrdersHistoryController::class, 'index'])->middleware('auth')->name('account.ordersHistory');

Route::get('/admin/product/{id}/edit', [AdminController::class, 'editProduct'])->middleware('is_admin')->name('admin.edit_product');
Route::put('/admin/product/{id}/update', [AdminController::class, 'updateProduct'])->middleware('is_admin')->name('admin.update_product');

Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.showProducts')->middleware('is_admin');
Route::get('/admin/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.editProduct')->middleware('is_admin');

Route::put('/admin/product/{id}', [AdminController::class, 'updateProduct'])->name('admin.updateProduct')->middleware('is_admin');