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
Route::get('/newly', 'HomeController@newly');
Route::get('/home/{num}', 'HomeController@index');
Route::get('photo/view/{id}', 'HomeController@photoView');
Route::get('photo/delete/{id}', 'HomeController@photoDelete');
Route::get('photo/turn/{id}/{angle}', 'HomeController@photoTurn');
Route::get('register/activate/{token}', 'Auth\RegisterController@activate');


Route::post('photo/upload', 'HomeController@upload');
Route::post('register/pre_check', 'Auth\RegisterController@pre_check')->name('register.pre_check');
