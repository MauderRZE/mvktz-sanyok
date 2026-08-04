<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\EmployeePhoneForm;
use App\Models\Employee;
use App\Models\EmployeePhone;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EmployeePhoneManager extends Component
{
    use WithPagination;

    public EmployeePhoneForm $form;

    public $employees;

    public $isOpen = false;

    public $search = '';

    public $filterPhoneType = [];

    public $filterEmployee = [];

    public function mount()
    {
        $this->employees = Employee::orderBy('last_name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPhoneType()
    {
        $this->resetPage();
    }

    public function updatingFilterEmployee()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EmployeePhone::with('employee')
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('phone_number', 'like', $search)
                        ->orWhereHas('employee', function ($emp) use ($search) {
                            $emp->where('first_name', 'like', $search)
                                ->orWhere('last_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterPhoneType), function ($q) {
                $hasNull = in_array('null', $this->filterPhoneType, true) || in_array(null, $this->filterPhoneType, true);
                $types = array_filter($this->filterPhoneType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($types, $hasNull) {
                    if (! empty($types)) {
                        $sub->whereIn('phone_type', $types);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('phone_type');
                    }
                });
            })
            ->when(! empty($this->filterEmployee), function ($q) {
                $hasNull = in_array('null', $this->filterEmployee, true) || in_array(null, $this->filterEmployee, true);
                $emps = array_filter($this->filterEmployee, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($emps, $hasNull) {
                    if (! empty($emps)) {
                        $sub->whereIn('employee_id', $emps);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('employee_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        return view('livewire.admin.employee-phone-manager', [
            'phones' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterPhoneType = [];
        $this->filterEmployee = [];
        $this->resetPage();
    }

    public function create()
    {
        $this->form->reset();
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

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message',
            $isUpdate ? 'Телефон оновлено.' : 'Телефон додано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $phone = EmployeePhone::findOrFail($id);
        $this->form->setPhone($phone);
        $this->openModal();
    }

    public function delete($id)
    {
        EmployeePhone::findOrFail($id)->delete();
        session()->flash('message', 'Телефон видалено.');
    }
}
