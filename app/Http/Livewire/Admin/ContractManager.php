<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\ContractForm;
use App\Models\Contract;
use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ContractManager extends Component
{
    use WithPagination;

    public ContractForm $form;

    public $suppliersList = [];

    public $isOpen = 0;

    public $search = '';

    public $filterSupplier = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterSupplier()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Contract::with('supplier')
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('contract_number', 'like', $search)
                        ->orWhereHas('supplier', function ($sup) use ($search) {
                            $sup->where('supplier_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterSupplier), function ($q) {
                $hasNull = in_array('null', $this->filterSupplier, true) || in_array(null, $this->filterSupplier, true);
                $ids = array_filter($this->filterSupplier, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($ids, $hasNull) {
                    if (! empty($ids)) {
                        $sub->whereIn('supplier_id', $ids);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('supplier_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $this->suppliersList = Supplier::all();

        return view('livewire.admin.contract-manager', [
            'contracts' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSupplier = [];
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
            $isUpdate ? 'Договір оновлено.' : 'Договір створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $this->form->setContract($contract);
        $this->openModal();
    }

    public function delete($id)
    {
        Contract::find($id)->delete();
        session()->flash('message', 'Договір видалено.');
    }
}
