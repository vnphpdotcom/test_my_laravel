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
Route::get('stream/attachment/{time}/{file}/token/{token}', 'Restful\StorageController@downloadDocumentAttachment')->where(['time'=>'[0-9]+','file' => '[a-zA-Z0-9\.\-\_]+','token'=>'[a-zA-Z0-9\=]+']);
Route::get('stream/preview/{id}/{name}','Restful\StorageController@downloadPreviewAttachment')->where(['id'=>'[0-9]+'],['name' => '[a-zA-Z0-9\-\_]+']);
