<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\EquipmentComplaint;
use App\Models\Equipment;
use App\Models\Employee;

class EquipmentComplaintManager extends Component
{
    public $complaints, $complaintId, $equipment_id, $complaint_date, $reported_by_employee_id, $issue_description, $resolution_status = 'Відкрито', $resolution_date;
    public $equipmentList = [], $employeesList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->complaints = EquipmentComplaint::with(['equipment', 'employee'])->get();
        $this->equipmentList = Equipment::all();
        $this->employeesList = Employee::all();
        return view('livewire.admin.equipment-complaint-manager')->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->complaint_date = date('Y-m-d');
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

    private function resetInputFields(){
        $this->complaintId = null;
        $this->equipment_id = null;
        $this->complaint_date = '';
        $this->reported_by_employee_id = null;
        $this->issue_description = '';
        $this->resolution_status = 'Відкрито';
        $this->resolution_date = '';
    }

    public function store()
    {
        $this->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'complaint_date' => 'required|date',
            'reported_by_employee_id' => 'nullable|exists:employees,id',
            'issue_description' => 'required|string',
            'resolution_status' => 'required|string|max:50',
            'resolution_date' => 'nullable|date',
        ]);

        EquipmentComplaint::updateOrCreate(['id' => $this->complaintId], [
            'equipment_id' => $this->equipment_id,
            'complaint_date' => $this->complaint_date,
            'reported_by_employee_id' => $this->reported_by_employee_id ?: null,
            'issue_description' => $this->issue_description,
            'resolution_status' => $this->resolution_status,
            'resolution_date' => $this->resolution_status === 'Вирішено' ? ($this->resolution_date ?: date('Y-m-d')) : null,
        ]);

        session()->flash('message', 
            $this->complaintId ? 'Скаргу оновлено.' : 'Скаргу зареєстровано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $complaint = EquipmentComplaint::findOrFail($id);
        $this->complaintId = $id;
        $this->equipment_id = $complaint->equipment_id;
        $this->complaint_date = $complaint->complaint_date;
        $this->reported_by_employee_id = $complaint->reported_by_employee_id;
        $this->issue_description = $complaint->issue_description;
        $this->resolution_status = $complaint->resolution_status;
        $this->resolution_date = $complaint->resolution_date;
        $this->openModal();
    }

    public function delete($id)
    {
        EquipmentComplaint::find($id)->delete();
        session()->flash('message', 'Скаргу видалено.');
    }
}
