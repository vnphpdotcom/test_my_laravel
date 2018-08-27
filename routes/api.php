<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace'=>'Restful'],function() {
    Route::get('v1.0/connect', function (){return response()->json(['success'=>''], 200);});
    Route::get('v1.0/config', 'ConfigController@getList');
    Route::get('v1.0/category', 'CategoryController@getList');
    Route::get('v1.0/document/{action}', 'DocumentController@getList')->where('action', '[a-z]+');
    Route::get('v1.0/document/{id}/{name}', 'DocumentController@getDetails')->where(['id' => '[0-9]+', 'name' => '[a-zA-Z0-9\-]+']);
    Route::get('v1.0/document/preview/{id}/{name}', 'StorageController@getDocumentPreview')->where(['id' => '[0-9]+', 'name' => '[a-zA-Z0-9\-]+']);
    Route::post('v1.0/user/login', 'UserController@Login');
    Route::post('v1.0/checkCart', 'DocumentController@checkCart');
    Route::get('v1.0/coupons/{code}', 'CouponsCodeController@getCoupons')->where(['code' => '[a-zA-Z0-9]+']);
});

Route::group(['middleware' => 'auth:api','namespace'=>'Restful'], function(){
    Route::get('v1.0/user/logged', 'UserController@Logged');
    Route::get('v1.0/user/logout', 'UserController@Logout');
    Route::get('v1.0/document/attachment/{id}/{name}', 'StorageController@getDocumentAttachment')->where(['id' => '[0-9]+', 'name' => '[a-zA-Z0-9\-]+']);
    Route::get('v1.0/checkPurchase/{id}/{name}', 'PurchaseController@checkPurchase')->where(['id' => '[0-9]+', 'name' => '[a-zA-Z0-9\-]+']);
    Route::post('v1.0/checkUserCart', 'DocumentController@checkUserCart');
    Route::post('v1.0/checkout', 'PurchaseController@checkout');
});
