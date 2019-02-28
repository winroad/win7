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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('photo/view/{id}', 'HomeController@photoView');
Route::get('photo/delete/{id}', 'HomeController@photoDelete');
Route::get('photo/turn/{id}', 'HomeController@photoTurn');

Route::post('/photo/upload', 'HomeController@upload');
