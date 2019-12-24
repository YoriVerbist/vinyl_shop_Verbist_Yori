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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');
Route::view('/', 'home');
Route::view('/', 'home');
Route::get('shop', 'ShopController@index');
Route::get('shop_alt', 'ShopController@alt');
Route::get('shop/{id}', 'ShopController@show');
Route::get('contact-us', 'ContactUsController@show');
Route::post('contact-us', 'ContactUsController@sendEmail');
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::redirect('/', 'records');
    Route::get('records', 'Admin\RecordController@index');
});
