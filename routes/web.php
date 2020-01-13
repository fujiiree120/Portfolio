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
//ItemContorollerを操作するRoute
Route::get('/', 'ItemController@index');
Route::get('/items', 'ItemController@index');
Route::get('/items/search', 'ItemController@search_items');
Route::get('/items/orderby', 'ItemController@order_by');
Route::get('/items/{item}/create', 'ItemController@show_create_index');
Route::post('/items', 'ItemController@store_item');
Route::patch('/items/{item}/status', 'ItemController@update_status');
Route::patch('/items/{item}/stock', 'ItemController@update_stock');
Route::delete('/items/{item}', 'ItemController@destroy_item');

//ItemDetailControllerを操作するRoute
Route::get('/items/{item}/detail', 'ItemDetailController@show_detail');
Route::post('/items/create/review', 'ItemDetailController@create_review');

//ItemCommentContorollerを操作するRoute
Route::get('/item_comments/{item_comment}/create', 'ItemCommentController@comment_index');
Route::patch('/item_comments/{item_comment}/store', 'ItemCommentController@store_comment');
Route::patch('/item_comments/{item_comment}/update_item_comment', 'ItemCommentController@update_item_comment');
Route::delete('/item_comments/{item_comment}/delete', 'ItemCommentController@destroy_comment');

//CartContorollerを操作するRoute
Route::resource('carts', 'CartController');
Route::patch('/carts/{cart}/stock', 'CartController@update_amount');
Route::delete('/carts/{cart}/delete', 'CartController@destroy_cart');
Route::post('/carts/{cart}/purchase', 'CartController@purchase');

//OrderContorollerを操作するRoute
Route::get('/carts/{cart}/orderlogs', 'OrderController@index_order_log');
Route::get('/carts/{cart}/orderdetail', 'OrderController@show_order_detail');

//UserController管理者権限を変更するroute
Route::get('/users/admin', 'UserController@change_admin');