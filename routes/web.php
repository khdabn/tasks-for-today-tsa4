<?php
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/',[TaskController::class,'welcome'])->name('home');
Route::get('/tasks',[TaskController::class,'index'])->name('tasks.index');
Route::get('/profile',[PublicPageController::class,'profile'])->name('profile');
Route::get('/about',[PublicPageController::class,'about'])->name('about');
Route::middleware('auth')->group(function(){
 Route::redirect('/dashboard','/tasks')->name('dashboard');
 Route::resource('tasks',TaskController::class)->only(['create','store','edit','update','destroy']);
});
require __DIR__.'/auth.php';
