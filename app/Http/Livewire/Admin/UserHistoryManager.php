<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\AuthLog;
use App\Models\AuditLog;
use App\Models\AccessLog;

#[Layout('layouts.admin')]
class UserHistoryManager extends Component
{
    use WithPagination;

    public $tab = 'auth'; // auth, audit, access
    public $search = '';

    public $filterUser = '';
    public $filterMethod = '';
    public $filterStatus = '';
    public $filterController = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public $isClearModalOpen = false;
    public $clearLogType = 'all';
    public $clearUserId = '';
    public $clearTimeframe = '90_days';
    public $clearBeforeDate = '';

    protected $queryString = [
        'tab', 'search',
        'filterUser', 'filterMethod', 'filterStatus', 'filterController', 'filterDateFrom', 'filterDateTo'
    ];

    public function openClearModal()
    {
        $this->clearLogType = 'all';
        $this->clearUserId = '';
        $this->clearTimeframe = '90_days';
        $this->clearBeforeDate = '';
        $this->isClearModalOpen = true;
    }

    public function closeModal()
    {
        $this->isClearModalOpen = false;
    }

    public function clearLogs()
    {
        $thresholdDate = null;
        if ($this->clearTimeframe === '30_days') {
            $thresholdDate = now()->subDays(30);
        } elseif ($this->clearTimeframe === '90_days') {
            $thresholdDate = now()->subDays(90);
        } elseif ($this->clearTimeframe === '180_days') {
            $thresholdDate = now()->subDays(180);
        } elseif ($this->clearTimeframe === '365_days') {
            $thresholdDate = now()->subDays(365);
        } elseif ($this->clearTimeframe === 'custom') {
            $this->validate([
                'clearBeforeDate' => 'required|date',
            ]);
            $thresholdDate = \Carbon\Carbon::parse($this->clearBeforeDate);
        }

        $models = [];
        if ($this->clearLogType === 'all') {
            $models = [AuditLog::class, AuthLog::class, AccessLog::class];
        } elseif ($this->clearLogType === 'audit') {
            $models = [AuditLog::class];
        } elseif ($this->clearLogType === 'auth') {
            $models = [AuthLog::class];
        } elseif ($this->clearLogType === 'access') {
            $models = [AccessLog::class];
        }

        $deletedCount = 0;
        foreach ($models as $modelClass) {
            $query = $modelClass::query();
            
            if ($this->clearUserId) {
                if ($this->clearUserId === 'guest') {
                    $query->whereNull('user_id');
                } else {
                    $query->where('user_id', $this->clearUserId);
                }
            }
            
            if ($thresholdDate) {
                $query->where('created_at', '<', $thresholdDate);
            }
            
            $deletedCount += $query->delete();
        }

        session()->flash('message', "Успішно видалено {$deletedCount} записів.");
        $this->closeModal();
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterUser', 'filterMethod', 'filterStatus', 'filterController', 'filterDateFrom', 'filterDateTo'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $data = null;
        $users = \App\Models\User::orderBy('name')->get();

        if ($this->tab === 'auth') {
            $data = AuthLog::with('user')
                ->when($this->search, function ($q) {
                    $q->where('ip_address', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function ($u) {
                          $u->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('login', 'like', '%'.$this->search.'%');
                      });
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
        } elseif ($this->tab === 'audit') {
            $data = AuditLog::with('user')
                ->when($this->search, function ($q) {
                    $q->where('auditable_type', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function ($u) {
                          $u->where('name', 'like', '%'.$this->search.'%');
                      });
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
        } elseif ($this->tab === 'access') {
            $data = AccessLog::with('user')
                ->when($this->search, function ($q) {
                    $q->where('url', 'like', '%'.$this->search.'%')
                      ->orWhereHas('user', function ($u) {
                          $u->where('name', 'like', '%'.$this->search.'%');
                      });
                })
                ->when($this->filterUser, function ($q) {
                    $q->where('user_id', $this->filterUser);
                })
                ->when($this->filterMethod, function ($q) {
                    $q->where('method', $this->filterMethod);
                })
                ->when($this->filterStatus, function ($q) {
                    $q->where('status_code', $this->filterStatus);
                })
                ->when($this->filterController, function ($q) {
                    $q->where('url', 'like', '%'.$this->filterController.'%');
                })
                ->when($this->filterDateFrom, function ($q) {
                    $q->whereDate('created_at', '>=', $this->filterDateFrom);
                })
                ->when($this->filterDateTo, function ($q) {
                    $q->whereDate('created_at', '<=', $this->filterDateTo);
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
        } elseif ($this->tab === 'stats') {
            $data = [
                'db_size' => round(filesize(database_path('history.sqlite')) / 1024 / 1024, 2) . ' MB',
                'total_auth' => AuthLog::count(),
                'total_audit' => AuditLog::count(),
                'total_access' => AccessLog::count(),
                
                'top_users_access' => AccessLog::selectRaw('user_id, count(*) as count')
                    ->with('user')
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),

                'top_urls' => AccessLog::selectRaw('url, count(*) as count')
                    ->groupBy('url')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),

                'auth_stats' => AuthLog::selectRaw('event, count(*) as count')
                    ->groupBy('event')
                    ->get()
                    ->pluck('count', 'event')->toArray(),

                'status_stats' => AccessLog::selectRaw('status_code, count(*) as count')
                    ->whereNotNull('status_code')
                    ->groupBy('status_code')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),
            ];
        }

        return view('livewire.admin.user-history-manager', [
            'logs' => $data,
            'users' => $users
        ])->layout('layouts.admin');
    }
}
