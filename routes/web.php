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

Route::get('/', 'MainController@index')->name('index');
Route::get('/info', 'MainController@info')->name('info');

Route::middleware('auth.vk')->group(function () {
    Route::get('/questions/default', 'DefaultQuestionController@index')->name('defaultQuestions');

    Route::get('/test', 'TestController@index')->name('yourTest');
    Route::get('/test/{user:vkid}', 'TestController@view')->name('test');
    Route::post('/test', 'TestController@store')->name('createTest');
    Route::delete('/test', 'TestController@destroy')->name('deleteTest');
});
