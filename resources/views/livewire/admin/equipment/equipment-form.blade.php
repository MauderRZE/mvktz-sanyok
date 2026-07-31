<div>
@if($isOpen)
<x-ui.modal title="{{ $form->equipmentId ? 'Редагувати' : 'Додати' }} обладнання" maxWidth="lg">
    <div class="space-y-4" x-data x-init="document.body.style.overflow='hidden'" x-destroy="document.body.style.overflow=''">
        <div>
            <x-form.input label="Інвентарний номер" model="form.inv_number" type="text" />
        </div>
        <div>
            <x-form.input label="Назва (бухгалтерська)" model="form.account_name" type="text" />
        </div>
        <div>
            <x-form.input label="Ціна (грн)" model="form.buy_price" type="number" step="0.01" />
        </div>
        <div>
            <x-form.select 
                label="Договір (Закупівля)" 
                model="form.purchase_id"
                placeholder="— Оберіть договір —"
                :options="$purchasesList->mapWithKeys(fn($p) => [$p->id => '№ ' . ($p->contract_number ?? $p->id) . ' (від ' . ($p->contract_date ?? '—') . ')'])->toArray()"
            />
        </div>
        <div>
            <x-form.select label="Статус" model="form.status">
                <option value="в експлуатації" class="bg-surface-800">в експлуатації</option>
                <option value="на списання" class="bg-surface-800">на списання</option>
                <option value="списано" class="bg-surface-800">списано</option>
                <option value="утилізовано" class="bg-surface-800">утилізовано</option>
            </x-form.select>
        </div>
        <div>
            <x-form.select 
                label="Акт списання" 
                model="form.retirement_act_id"
                placeholder="— Оберіть акт —"
                :options="$retirementActsList->mapWithKeys(fn($act) => [$act->id => '№ ' . ($act->act_number ?? $act->id) . ' (від ' . ($act->act_date ?? '—') . ')'])->toArray()"
            />
        </div>
        <div>
            <x-form.input label="Примітка" model="form.notes" type="text" />
        </div>
    </div>
</x-ui.modal>
@endif
</div>
