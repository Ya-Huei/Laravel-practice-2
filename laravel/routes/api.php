<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('menu', 'MenuController@index');

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::get('menu/getAllMenu', 'MenuController@getAllMenu');

    Route::group(['middleware' => 'permissions'], function ($router) {
        Route::resource('users', UsersController::class)->except(['show']);
        Route::resource('roles', RolesController::class)->except(['show']);
        Route::get('devices/{id}/repair', 'DevicesController@repair');
        Route::post('devices/{id}/saveRepair', 'DevicesController@saveRepair');
        Route::resource('devices', DevicesController::class)->except(['create', 'store', 'show', 'destroy']);
        Route::resource('firmware', FirmWareController::class);
        Route::resource('ota', OtaController::class);
        Route::resource('recipes', RecipesController::class);
        Route::resource('repairs', RepairsController::class);
    });
});
