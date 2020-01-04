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
Route::get('/', 'ItemController@index');

Route::get('/items', 'ItemController@index');
Route::get('/items/orderby', 'ItemController@order_by');
Route::get('/items/{item}/create', 'ItemController@create');
Route::post('/items', 'ItemController@store');
Route::patch('/items/{item}/status', 'ItemController@update_status');
Route::patch('/items/{item}/stock', 'ItemController@update_stock');
Route::delete('/items/{item}', 'ItemController@destroy');
Route::resource('carts', 'CartController');
Route::patch('/carts/{cart}/stock', 'CartController@update_amount');
Route::delete('/carts/{cart}/delete', 'CartController@destroy');
Route::post('/carts/{cart}/purchase', 'CartController@purchase');
Route::get('/carts/{cart}/orderlogs', 'OrderController@index');
Route::get('/carts/{cart}/orderdetail', 'OrderController@show');
Route::get('/users/admin', 'UserController@change_admin');
