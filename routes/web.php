<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Queuetutorial\ProjectFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix("/dashboard")->group(function(){
    Route::controller(HomeController::class)->group(function(){
        Route::get("/",'index')->name("dashboard");
        Route::post("/",'Store');
        Route::get("/send-mail","");
    });
})->middleware(['auth']);



Route::middleware('auth')->group(function () {


    Route::controller(ProjectFileController::class)->group(function(){
        Route::get("/process-file", "index");
        Route::post("/process-file",'store');

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
