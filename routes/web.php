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

Route::get('/login','HomeController@login')->name('login');
Route::get('/register','HomeController@register')->name('register');
Route::get('/logout','HomeController@logout')->name('logout');
Route::get('/logout','HomeController@logout')->name('logout');
Route::get('/refresh_token','HomeController@refresh_token')->name('refresh_token');

Route::post('/signin','HomeController@signin')->name('signin');
Route::post('/signup','HomeController@signup')->name('signup');

Route::get('/home','HomeController@home')->name('home');
