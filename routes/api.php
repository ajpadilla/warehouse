<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('warehouse/v1')->group(function () {
    Route::middleware(['throttle:60,5'])->group(function () {
        Route::get('/ingredients', 'IngredientController@index');
        Route::get('/ingredients/{name}', 'IngredientController@show');
        Route::post('/ingredients/list', 'IngredientController@listIngredients');
        Route::post('/ingredients/increase', 'IngredientController@increase');
        Route::post('/ingredients/decrease', 'IngredientController@decrease');
        Route::get('/ingredients/buy/{ingredient}', 'IngredientController@buyIngredientInWareHouse');
        Route::get('/purchases', 'PurchaseController@index');
        Route::post('/purchases/create', 'PurchaseController@store');
    });
});

