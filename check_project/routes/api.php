<?php

use App\Http\Controllers\API\ApiLoginController;
use App\Http\Controllers\API\ApiLogoutController;
use App\Http\Controllers\API\ApiRegistrationController;
use App\Http\Controllers\API\ApiRoleController;
use App\Http\Controllers\API\ApiTaskController;
use App\Http\Controllers\API\ApiUserController;
use App\Http\Controllers\API\ApiUserIsLoggedInController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [ApiRegistrationController::class, 'register']);
Route::post('/login', [ApiLoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/logout', [ApiLogoutController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/testUser', [ApiUserIsLoggedInController::class, 'testUser']);

Route::middleware('auth:sanctum')->get('/userTask', [ApiTaskController::class, 'userTask']);
Route::middleware('auth:sanctum')->get('/allTask', [ApiTaskController::class, 'allTask']);
Route::middleware('auth:sanctum')->get('/taskStatus', [ApiTaskController::class, 'taskStatus']);

//task
Route::middleware('auth:sanctum')->post('/createApiTask', [ApiTaskController::class, 'createApiTask']);
Route::middleware('auth:sanctum')->post('/updateApiTask', [ApiTaskController::class, 'updateApiTask']);
Route::middleware('auth:sanctum')->post('/closeApiTask', [ApiTaskController::class, 'closeApiTask']);

//user
Route::middleware('auth:sanctum')->get('/userInfo', [ApiUserController::class, 'userInfo']);

//roles
Route::middleware('auth:sanctum')->get('/roles', [ApiRoleController::class, 'roles']);
Route::middleware('auth:sanctum')->post('/assignRole', [ApiRoleController::class, 'assignRole']);
Route::middleware('auth:sanctum')->post('/removeRole', [ApiRoleController::class, 'removeRole']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//remove after :)

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
