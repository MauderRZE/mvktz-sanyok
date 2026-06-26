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
    Route::get('/users', \App\Http\Livewire\Admin\UserManager::class)->name('admin.users');
    
    // Нові маршрути для решти таблиць
    Route::get('/base-components', \App\Http\Livewire\Admin\BaseComponentManager::class)->name('admin.base-components');
    Route::get('/base-materials', \App\Http\Livewire\Admin\BaseMaterialManager::class)->name('admin.base-materials');
    Route::get('/suppliers', \App\Http\Livewire\Admin\SupplierManager::class)->name('admin.suppliers');
    Route::get('/locations', \App\Http\Livewire\Admin\LocationManager::class)->name('admin.locations');
    Route::get('/maintenance-types', \App\Http\Livewire\Admin\MaintenanceTypeManager::class)->name('admin.maintenance-types');
    Route::get('/contracts', \App\Http\Livewire\Admin\ContractManager::class)->name('admin.contracts');
    Route::get('/components', \App\Http\Livewire\Admin\EquipmentComponentManager::class)->name('admin.components');
    Route::get('/complaints', \App\Http\Livewire\Admin\EquipmentComplaintManager::class)->name('admin.complaints');
    Route::get('/movements', \App\Http\Livewire\Admin\EquipmentMovementManager::class)->name('admin.movements');
    Route::get('/low-value-materials', \App\Http\Livewire\Admin\LowValueMaterialManager::class)->name('admin.low-value-materials');
    Route::get('/maintenance-logs', \App\Http\Livewire\Admin\MaintenanceLogManager::class)->name('admin.maintenance-logs');
    Route::get('/software-licenses', \App\Http\Livewire\Admin\SoftwareLicenseManager::class)->name('admin.software-licenses');
    Route::get('/type-requirements', \App\Http\Livewire\Admin\TypeRequirementManager::class)->name('admin.type-requirements');
    Route::get('/system-errors', \App\Http\Livewire\Admin\SystemErrorManager::class)->name('admin.system-errors');
});

