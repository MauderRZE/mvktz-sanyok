<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\Contract;
use App\Models\SoftwareLicense;
use App\Models\MaintenanceLog;
use App\Models\EquipmentMovement;
use App\Models\Location;
use App\Models\Department;
use App\Models\Supplier;
use App\Models\EquipmentRetirementAct;
use App\Models\Asset;
use App\Models\LowValueMaterial;

#[Layout('layouts.admin')]
class DashboardManager extends Component
{
    public function render()
    {
        // Базова статистика
        $totalEquipment = Equipment::count();
        $equipmentInUse = Equipment::where('status', 'в експлуатації')->count();
        $equipmentStored = Equipment::where('status', 'в аренді')->count();
        $equipmentWrittenOff = Equipment::where('status', 'списано')->count();

        $totalEmployees = Employee::count();
        // Деталі для співробітників
        $employeesWorking = Employee::where('status', 'Працює')->count();
        $employeesWithPhones = Employee::has('phones')->count();

        // Деталі для договорів
        $totalContracts = Contract::count();
        $contractsWithLink = Contract::whereNotNull('contract_link')->count();

        // Деталі для ліцензій
        $totalLicenses = SoftwareLicense::count();
        $licensesWithVendor = SoftwareLicense::whereNotNull('vendor_id')->count();

        // Останні ТО (для списку)
        $recentMaintenance = MaintenanceLog::with(['asset.equipment', 'asset.model'])
            ->orderBy('sent_date', 'desc')
            ->limit(5)
            ->get();

        // Деталі для переміщень
        $totalMovements = EquipmentMovement::count();
        $movementsThisMonth = EquipmentMovement::whereMonth('action_date', now()->month)
            ->whereYear('action_date', now()->year)->count();

        // Деталі для локацій
        $totalLocations = Location::count();
        $locationsInUse = Location::has('assets')->count();

        // Деталі для відділів
        $totalDepartments = Department::count();
        $departmentsWithEmployees = Department::has('employees')->count();

        // Деталі для постачальників
        $totalSuppliers = Supplier::count();
        $tovSuppliers = Supplier::where('supplier_type_id', 1)->count();
        $fopSuppliers = Supplier::where('supplier_type_id', 2)->count();

        // Деталі для актів списання
        $totalWriteOffs = EquipmentRetirementAct::count();
        $writeOffsThisYear = EquipmentRetirementAct::whereYear('act_date', now()->year)->count();

        // Деталі для ТО
        $totalMaintenance = MaintenanceLog::count();
        $maintenanceActive = MaintenanceLog::whereNull('return_date')->count();
        $maintenanceCompleted = MaintenanceLog::whereNotNull('return_date')->count();

        // Деталі для активів
        $totalAssets = Asset::count();
        $assetsWithSerial = Asset::whereNotNull('serial_number')->where('serial_number', '!=', '')->count();
        $assetsWrittenOff = Asset::whereNotNull('write_off_act_id')->count();

        // Деталі для МШП
        $totalLVM = LowValueMaterial::count();
        $lvmCountSum = LowValueMaterial::sum('count');

        return view('livewire.admin.dashboard-manager', [
            'stats' => [
                'totalEquipment' => $totalEquipment,
                'equipmentInUse' => $equipmentInUse,
                'equipmentStored' => $equipmentStored,
                'equipmentWrittenOff' => $equipmentWrittenOff,
                'totalAssets' => $totalAssets,
                'assetsWithSerial' => $assetsWithSerial,
                'assetsWrittenOff' => $assetsWrittenOff,
                'totalLVM' => $totalLVM,
                'lvmCountSum' => $lvmCountSum,
                'totalEmployees' => $totalEmployees,
                'employeesWorking' => $employeesWorking,
                'employeesWithPhones' => $employeesWithPhones,
                'totalContracts' => $totalContracts,
                'contractsWithLink' => $contractsWithLink,
                'totalLicenses' => $totalLicenses,
                'licensesWithVendor' => $licensesWithVendor,
                'totalMovements' => $totalMovements,
                'movementsThisMonth' => $movementsThisMonth,
                'totalLocations' => $totalLocations,
                'locationsInUse' => $locationsInUse,
                'totalDepartments' => $totalDepartments,
                'departmentsWithEmployees' => $departmentsWithEmployees,
                'totalSuppliers' => $totalSuppliers,
                'tovSuppliers' => $tovSuppliers,
                'fopSuppliers' => $fopSuppliers,
                'totalWriteOffs' => $totalWriteOffs,
                'writeOffsThisYear' => $writeOffsThisYear,
                'totalMaintenance' => $totalMaintenance,
                'maintenanceActive' => $maintenanceActive,
                'maintenanceCompleted' => $maintenanceCompleted,
            ],
            'recentMaintenance' => $recentMaintenance
        ]);
    }
}
