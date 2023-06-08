<?php

use Illuminate\Http\Request;
use App\Models\Todo;
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

Route::get('todos', 'TodoController@listing');
Route::post('todo', 'TodoController@add');
Route::put('todo/{id}', 'TodoController@update');
Route::delete('todo/{id}', 'TodoController@remove');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
