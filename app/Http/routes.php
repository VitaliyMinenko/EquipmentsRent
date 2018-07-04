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

/**
 * Endpoint for getting rent equipment for the next N days
 */
Route::post('api/getRentPeriod/', 'ApiController@getRentPeriod');

/**
 * Endpoint set new rent period or set new equipment.
 */
Route::post('api/addRentPeriod/', 'ApiController@addRentPeriod');



