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

Route::get('todos', 'TodoController@listing');
Route::post('todo', 'TodoController@add');
Route::put('todo/{id}', 'TodoController@update');
Route::delete('todo/{id}', 'TodoController@remove');
Route::put('todo/{id}/lock', 'TodoController@lock');
Route::put('todo/{id}/unlock', 'TodoController@unlock');

Route::get('users', 'UserController@listing');
Route::get('user', 'UserController@getOne');
