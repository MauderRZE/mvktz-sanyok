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
        $totalContracts = Contract::count();

        // Ліцензії ПЗ
        $totalLicenses = SoftwareLicense::count();

        // Останні ТО
        $recentMaintenance = MaintenanceLog::with(['asset.equipment', 'asset.model'])
            ->orderBy('sent_date', 'desc')
            ->limit(5)
            ->get();

        // Переміщення (статистика)
        $totalMovements = EquipmentMovement::count();

        return view('livewire.admin.dashboard-manager', [
            'stats' => [
                'totalEquipment' => $totalEquipment,
                'equipmentInUse' => $equipmentInUse,
                'equipmentStored' => $equipmentStored,
                'equipmentWrittenOff' => $equipmentWrittenOff,
                'totalEmployees' => $totalEmployees,
                'totalContracts' => $totalContracts,
                'totalLicenses' => $totalLicenses,
                'totalMovements' => $totalMovements,
            ],
            'recentMaintenance' => $recentMaintenance
        ]);
    }
}
