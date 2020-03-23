<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'WelcomeController@index')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'videos',
    'as' => 'videos.'
], function () {
    Route::get('/', 'VideoController@index')->name('index');
    Route::get('{id}', 'VideoController@show')->name('show');
    Route::get('{id}/stream', 'VideoController@stream')->name('stream');

    Route::get('{id}/download/video', 'DownloadFileController@video')->name('download.video');
    Route::get('{id}/download/audio', 'DownloadFileController@audio')->name('download.audio');
});
