<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Contract;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentMovement;
use App\Models\EquipmentRetirementAct;
use App\Models\Location;
use App\Models\LowValueMaterial;
use App\Models\MaintenanceLog;
use App\Models\SoftwareLicense;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    /**
     * Час життя кешу у хвилинах (за замовчуванням 30)
     */
    protected int $cacheTtl = 30;

    /**
     * Отримати базову статистику з кешу
     */
    public function getStats(): array
    {
        return Cache::remember('dashboard_stats', now()->addMinutes($this->cacheTtl), function () {
            return [
                'totalEquipment' => Equipment::count(),
                'equipmentInUse' => Equipment::where('status', 'в експлуатації')->count(),
                'equipmentStored' => Equipment::where('status', 'в аренді')->count(),
                'equipmentWrittenOff' => Equipment::where('status', 'списано')->count(),

                'totalAssets' => Asset::count(),
                'assetsWithSerial' => Asset::whereNotNull('serial_number')->where('serial_number', '!=', '')->count(),
                'assetsWrittenOff' => Asset::whereNotNull('write_off_act_id')->count(),

                'totalLVM' => LowValueMaterial::count(),
                'lvmCountSum' => LowValueMaterial::sum('count'),

                'totalEmployees' => Employee::count(),
                'employeesWorking' => Employee::where('status', 'Працює')->count(),
                'employeesWithPhones' => Employee::has('phones')->count(),

                'totalContracts' => Contract::count(),
                'contractsWithLink' => Contract::whereNotNull('contract_link')->count(),

                'totalLicenses' => SoftwareLicense::count(),
                'licensesWithVendor' => SoftwareLicense::whereNotNull('vendor_id')->count(),

                'totalMovements' => EquipmentMovement::count(),
                'movementsThisMonth' => EquipmentMovement::whereMonth('action_date', now()->month)
                    ->whereYear('action_date', now()->year)->count(),

                'totalLocations' => Location::count(),
                'locationsInUse' => Location::has('assets')->count(),

                'totalDepartments' => Department::count(),
                'departmentsWithEmployees' => Department::has('employees')->count(),

                'totalSuppliers' => Supplier::count(),
                'tovSuppliers' => Supplier::where('supplier_type_id', 1)->count(),
                'fopSuppliers' => Supplier::where('supplier_type_id', 2)->count(),

                'totalWriteOffs' => EquipmentRetirementAct::count(),
                'writeOffsThisYear' => EquipmentRetirementAct::whereYear('act_date', now()->year)->count(),

                'totalMaintenance' => MaintenanceLog::count(),
                'maintenanceActive' => MaintenanceLog::whereNull('return_date')->count(),
                'maintenanceCompleted' => MaintenanceLog::whereNotNull('return_date')->count(),
            ];
        });
    }

    /**
     * Отримати останні 5 ТО
     * (Не кешуємо, щоб бачити актуальний журнал)
     */
    public function getRecentMaintenance()
    {
        return MaintenanceLog::with(['asset.equipment', 'asset.model'])
            ->orderBy('sent_date', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Отримати дані для графіків (нове обладнання та нові активи) з кешу
     */
    public function getChartData(): array
    {
        return Cache::remember('dashboard_charts', now()->addMinutes($this->cacheTtl), function () {
            $chartLabels = [];
            $chartData = [];
            $assetsChartData = [];
            $equipmentChartData = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $chartLabels[] = $date->format('m.Y');

                $chartData[] = EquipmentMovement::whereMonth('action_date', $date->month)
                    ->whereYear('action_date', $date->year)
                    ->count();

                $assetsChartData[] = Asset::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $equipmentChartData[] = Equipment::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
            }

            return [
                'chartLabels' => $chartLabels,
                'chartData' => $chartData,
                'assetsChartData' => $assetsChartData,
                'equipmentChartData' => $equipmentChartData,
            ];
        });
    }
}
