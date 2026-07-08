<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Employee;
use App\Livewire\Forms\EmployeeForm;

#[Layout('layouts.admin')]
class EmployeeManager extends Component
{
    use WithPagination;

    public EmployeeForm $form;
    public $departmentsList = [];
    
    public $isOpen = false;

    public $search = '';
    public $filterDepartment = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->departmentsList = \App\Models\Department::all();

        $query = Employee::with('department');

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', '%'.$search.'%')
                  ->orWhere('first_name', 'like', '%'.$search.'%')
                  ->orWhere('middle_name', 'like', '%'.$search.'%')
                  ->orWhere('position', 'like', '%'.$search.'%');
            });
        }

        if (!empty($this->filterDepartment)) {
            $query->whereIn('department_id', $this->filterDepartment);
        }

        $employees = $query->orderBy('id', 'desc')->paginate(15);

        return view('livewire.admin.employee-manager', [
            'employees' => $employees
        ]);
    }

    public function create()
    {
        $this->form->reset();
        $this->isOpen = true;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterDepartment = [];
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message', 
            $isUpdate ? 'Співробітника оновлено успішно.' : 'Співробітника створено успішно.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->form->setEmployee($employee);
    
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Employee::find($id)->delete();
        session()->flash('message', 'Співробітника видалено успішно.');
    }
}
