<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuthLog;
use App\Models\AuditLog;
use App\Models\AccessLog;

class UserHistoryManager extends Component
{
    use WithPagination;

    public $tab = 'auth'; // auth, audit, access
    public $search = '';

    protected $queryString = ['tab', 'search'];

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = null;

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
                ->orderBy('id', 'desc')
                ->paginate(20);
        }

        return view('livewire.admin.user-history-manager', [
            'logs' => $data
        ])->layout('layouts.admin');
    }
}
