<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// 商品一覧画面を表示
Route::get('/', 'ProductController@showProductList')->name('products');

// 商品詳細画面を表示
Route::get('/product/{id}', 'ProductController@showDetail')->name('show');
