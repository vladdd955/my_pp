<?php

use App\Http\Controllers\AllTaskController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Services\TaskService;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/task', [TaskController::class, 'index'])->name('task');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::get('/myTask', [TaskController::class, 'myTask'])->name('myTask');

    Route::get('/show/{id}', [TaskController::class, 'show'])->name('show');


    Route::get('/allTask', [AllTaskController::class, 'index'])->name('allTask');

    //Service
    Route::post('/closeTask', [TaskService::class, 'closeTask'])->name('closeTask');
    Route::post('/updateTask', [TaskService::class, 'updateTask'])->name('updateTask');


    Route::post('/confirmRole', [HomeController::class, 'confirmRole'])->name('confirmRole');
    Route::post('/deleteRole', [HomeController::class, 'deleteRole'])->name('deleteRole');
    Route::get('/showRole', [HomeController::class, 'userRole'])->name('userRole');

});

Route::post('/getCountryJson', [RegisteredUserController::class, 'getCountryJson'])->name('getCountryJson');

require __DIR__.'/auth.php';
