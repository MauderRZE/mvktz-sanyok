<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SystemError;

#[Layout('layouts.admin')]
class SystemErrorManager extends Component
{
    public $errorsList;
    public $errorId, $page_type, $error_type, $error_text, $is_resolved = false;
    public $isOpen = 0;

    public $search = '';
    public $filterResolved = '';
    public $filterPageType = [];
    public $filterErrorType = [];

    public function render()
    {
        $query = SystemError::query()
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('page_type', 'like', $search)
                        ->orWhere('error_type', 'like', $search)
                        ->orWhere('error_text', 'like', $search);
                });
            })
            ->when($this->filterResolved !== '', function($q) {
                $q->where('is_resolved', $this->filterResolved);
            })
            ->when(!empty($this->filterPageType), function($q) {
                $q->whereIn('page_type', $this->filterPageType);
            })
            ->when(!empty($this->filterErrorType), function($q) {
                $q->whereIn('error_type', $this->filterErrorType);
            })
            ->orderBy('created_at', 'desc');

        $this->errorsList = $query->get();
        return view('livewire.admin.system-error-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterResolved = '';
        $this->filterPageType = [];
        $this->filterErrorType = [];
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->errorId = null;
        $this->page_type = '';
        $this->error_type = '';
        $this->error_text = '';
        $this->is_resolved = false;
    }

    public function store()
    {
        $this->validate([
            'page_type' => 'nullable|string|max:255',
            'error_type' => 'nullable|string|max:255',
            'error_text' => 'nullable|string',
            'is_resolved' => 'boolean',
        ]);

        $data = [
            'page_type' => $this->page_type,
            'error_type' => $this->error_type,
            'error_text' => $this->error_text,
        ];
        
        if (!$this->errorId) {
            $data['is_resolved'] = 0;
        } else {
            $data['is_resolved'] = $this->is_resolved ? 1 : 0;
        }

        SystemError::updateOrCreate(['id' => $this->errorId], $data);

        session()->flash('message', 
            $this->errorId ? 'Помилку оновлено.' : 'Помилку додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $error = SystemError::findOrFail($id);
        $this->errorId = $id;
        $this->page_type = $error->page_type;
        $this->error_type = $error->error_type;
        $this->error_text = $error->error_text;
        $this->is_resolved = $error->is_resolved;
        
        $this->openModal();
    }

    public function toggleResolved($id)
    {
        $error = SystemError::find($id);
        if ($error) {
            $error->is_resolved = !$error->is_resolved;
            $error->save();
        }
    }

    public function delete($id)
    {
        SystemError::find($id)->delete();
        session()->flash('message', 'Помилку видалено.');
    }
}
