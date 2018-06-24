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

Route::get('/', function (Symfony\Component\HttpFoundation\Response $respnce) {
    return response()->json([
        'status' => 'Error',
        'state' => 'Url is not found.'
    ])->header('Content-Type', 'json');
});
Route::get('api/getInfo/', 'ApiController@getInfo');
Route::post('api/getInfo/', 'ApiController@getInfo');

