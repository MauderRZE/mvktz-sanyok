<?php

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

Route::get('/admin', function () {
    return redirect()->route('admin.equipment');
});

Route::get('/login', \App\Http\Livewire\Auth\Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/login');
})->name('logout');

Route::group(['prefix' => 'admin', 'layout' => 'layouts.admin', 'middleware' => 'auth'], function () {
    Route::get('/equipment/report', [\App\Http\Controllers\Admin\EquipmentReportController::class, 'index'])->name('admin.equipment.report');
    Route::get('/equipment', \App\Http\Livewire\Admin\EquipmentManager::class)->name('admin.equipment');
    Route::get('/employees', \App\Http\Livewire\Admin\EmployeeManager::class)->name('admin.employees');
    Route::get('/categories', \App\Http\Livewire\Admin\CategoryManager::class)->name('admin.categories');
    Route::get('/types', \App\Http\Livewire\Admin\TypeManager::class)->name('admin.types');
    Route::get('/users', \App\Http\Livewire\Admin\UserManager::class)->name('admin.users');
    
    // Нові маршрути для решти таблиць
    Route::get('/base-components', \App\Http\Livewire\Admin\BaseComponentManager::class)->name('admin.base-components');
    Route::get('/suppliers', \App\Http\Livewire\Admin\SupplierManager::class)->name('admin.suppliers');
    Route::get('/locations', \App\Http\Livewire\Admin\LocationManager::class)->name('admin.locations');
    Route::get('/contracts', \App\Http\Livewire\Admin\ContractManager::class)->name('admin.contracts');
    Route::get('/assets', \App\Http\Livewire\Admin\AssetManager::class)->name('admin.assets');
    Route::get('/movements', \App\Http\Livewire\Admin\EquipmentMovementManager::class)->name('admin.movements');
    Route::get('/low-value-materials', \App\Http\Livewire\Admin\LowValueMaterialManager::class)->name('admin.low-value-materials');
    Route::get('/maintenance-logs', \App\Http\Livewire\Admin\MaintenanceLogManager::class)->name('admin.maintenance-logs');
    Route::get('/software-licenses', \App\Http\Livewire\Admin\SoftwareLicenseManager::class)->name('admin.software-licenses');
    Route::get('/brands', \App\Http\Livewire\Admin\BrandManager::class)->name('admin.brands');
    Route::get('/departments', \App\Http\Livewire\Admin\DepartmentManager::class)->name('admin.departments');
    Route::get('/organizations', \App\Http\Livewire\Admin\OrganizationManager::class)->name('admin.organizations');
    Route::get('/retirement-acts', \App\Http\Livewire\Admin\RetirementActManager::class)->name('admin.retirement-acts');
    Route::get('/write-off-acts', \App\Http\Livewire\Admin\WriteOffActManager::class)->name('admin.write-off-acts');

    Route::get('/attribute-dictionaries', \App\Http\Livewire\Admin\AttributeDictionaryManager::class)->name('admin.attribute-dictionaries');
    Route::get('/supplier-types', \App\Http\Livewire\Admin\SupplierTypeManager::class)->name('admin.supplier-types');
    Route::get('/computer-software', \App\Http\Livewire\Admin\ComputerSoftwareManager::class)->name('admin.computer-software');
    Route::get('/employee-phones', \App\Http\Livewire\Admin\EmployeePhoneManager::class)->name('admin.employee-phones');
    Route::get('/item-properties', \App\Http\Livewire\Admin\ItemPropertyManager::class)->name('admin.item-properties');
    Route::get('/system-errors', \App\Http\Livewire\Admin\SystemErrorManager::class)->name('admin.system-errors');
});
