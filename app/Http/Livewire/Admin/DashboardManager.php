<?php

namespace App\Http\Livewire\Admin;

use App\Services\DashboardStatsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class DashboardManager extends Component
{
    public function render(DashboardStatsService $statsService)
    {
        $chartData = $statsService->getChartData();

        return view('livewire.admin.dashboard-manager', [
            'stats' => $statsService->getStats(),
            'chartLabels' => $chartData['chartLabels'],
            'chartData' => $chartData['chartData'],
            'assetsChartData' => $chartData['assetsChartData'],
            'equipmentChartData' => $chartData['equipmentChartData'],
            'recentMaintenance' => $statsService->getRecentMaintenance(),
        ]);
    }
}
