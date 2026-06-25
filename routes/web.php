<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.equipment');
});

Route::get('/login', \App\Http\Livewire\Auth\Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
})->name('logout');

Route::group(['prefix' => 'admin', 'layout' => 'layouts.admin', 'middleware' => 'auth'], function () {
    Route::get('/equipment', \App\Http\Livewire\Admin\EquipmentManager::class)->name('admin.equipment');
    Route::get('/employees', \App\Http\Livewire\Admin\EmployeeManager::class)->name('admin.employees');
    Route::get('/categories', \App\Http\Livewire\Admin\CategoryManager::class)->name('admin.categories');
    Route::get('/types', \App\Http\Livewire\Admin\TypeManager::class)->name('admin.types');
});
