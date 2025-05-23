<?php

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')
    ->middleware('auth_and_guest:api')
    ->group(function () {
        Route::group([
            'prefix' => 'videos',
            'as' => 'videos.'
        ], function () {
            Route::get('/', 'VideoController@index')->name('index');

            Route::post('/', 'VideoController@runUploading')->name('uploading.run');
            Route::get('/video', 'VideoController@getUrl')->name('url.video');

            Route::post('/audio', 'AudioController@runUploading')->name('uploading.run.audio');
            Route::get('/audio', 'AudioController@getUrl')->name('url.audio');
        });
    });

Route::get('/video/links', 'LinksController@index')->name('links.index');
Route::get('/audio/upload', 'Api\AudioController@upload')->name('upload.audio');