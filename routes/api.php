<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsOwner;

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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\v1'], function () {
	Route::post('/register', 'AuthController@register');
	Route::post('/login', 'AuthController@login');

    Route::group([], function () {
	    Route::get('/profile', 'AuthController@profile')->middleware(['auth:sanctum']);

		Route::post('/kost/{id}/comment', 'KostController@commentKost');

		Route::apiResources([
			'kost' => KostController::class
		]);

		Route::get('/kost-search', 'KostController@searchKost')->withoutMiddleware(['auth:sanctum']);
    });
});
