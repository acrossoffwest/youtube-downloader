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

Route::get('/', 'VideoController@index');
Route::get('/videos/{id}', 'VideoController@video')->name('videos.show');
Route::get('/videos/{id}/play.mp4', 'VideoController@play')->name('videos.play');
Route::get('/progress', 'ProgressBarController@progress');
Route::get('/play.mp4', 'HomeController@play');

Route::get('/videos/{id}/video.mp4', 'DownloadFileController@video')->name('videos.video.load');
Route::get('/videos/{id}/audio.m4a', 'DownloadFileController@audio')->name('videos.audio.load');

Route::get('/trigger', 'HomeController@runTrigger');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
