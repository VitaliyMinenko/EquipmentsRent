<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/api/getRentPeriod/', 'ApiController@getRentPeriod');
Route::post('api/getRentPeriod/', 'ApiController@getRentPeriod');
Route::post('api/addRentPeriod/', 'ApiController@addRentPeriod');
Route::get('api/addRentPeriod/', 'ApiController@addRentPeriod');

