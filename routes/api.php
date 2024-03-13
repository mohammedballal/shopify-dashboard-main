<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RoleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// create role api post type
//Route::post('/role/store',[RoleController::class,'store'])->name('role.store');
//Route::get('/users',[RegisteredUserController::class,'list'])->name('users.list');

