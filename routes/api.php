<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DaysCombinationController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoomclassController;
use App\Http\Controllers\RoomController;
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

Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});

Route::apiResource('rooms', RoomController::class);
Route::apiResource('days', DaysCombinationController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('professors', ProfessorController::class);
Route::apiResource('classes', RoomclassController::class);

Route::prefix('/classes/{id}')->group(function ()
{
    Route::get('/students', [RoomclassController::class, 'listStudents']);
});
