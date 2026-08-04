<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\OrganizationForm;
use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class OrganizationManager extends Component
{
    use WithPagination;

    public OrganizationForm $form;

    public $isOpen = 0;

    public $search = '';

    public $filterType = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Organization::query()
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('org_name', 'like', $search)
                        ->orWhere('org_type', 'like', $search);
                });
            })
            ->when(! empty($this->filterType), function ($q) {
                $hasNull = in_array('null', $this->filterType, true) || in_array(null, $this->filterType, true);
                $types = array_filter($this->filterType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($types, $hasNull) {
                    if (! empty($types)) {
                        $sub->whereIn('org_type', $types);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('org_type');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $typesList = Organization::select('org_type')->distinct()->pluck('org_type')->filter()->values();

        return view('livewire.admin.organization-manager', [
            'organizations' => $query->paginate(15),
            'typesList' => $typesList,
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = [];
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
            $isUpdate ? 'Організацію оновлено.' : 'Організацію створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $org = Organization::findOrFail($id);
        $this->form->setOrganization($org);
        $this->openModal();
    }

    public function delete($id)
    {
        Organization::find($id)->delete();
        session()->flash('message', 'Організацію видалено.');
    }
}
