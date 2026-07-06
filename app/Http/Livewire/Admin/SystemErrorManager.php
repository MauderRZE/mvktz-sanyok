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

    public function render()
    {
        $this->errorsList = SystemError::orderBy('created_at', 'desc')->get();
        return view('livewire.admin.system-error-manager');
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
