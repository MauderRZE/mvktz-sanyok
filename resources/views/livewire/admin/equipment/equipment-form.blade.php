<div>
@if($isOpen)
<x-ui.modal title="{{ $equipmentId ? 'Редагувати' : 'Додати' }} обладнання" maxWidth="lg">
    <div class="space-y-4" x-data x-init="document.body.style.overflow='hidden'" x-destroy="document.body.style.overflow=''">
        <div>
            <x-form.input label="Інвентарний номер" model="inventory_number" type="text" />
        </div>
        <div>
            <x-form.input label="Назва (бухгалтерська)" model="accounting_name" type="text" />
        </div>
        <div>
            <x-form.input label="Ціна (грн)" model="buy_price" type="number" step="0.01" />
        </div>
        <div>
            <x-form.select label="Договір (Закупівля)" model="purchase_id">
                <option value="" class="bg-surface-800">— Оберіть договір —</option>
                @foreach($purchasesList as $p)
                    <option value="{{ $p->id }}" class="bg-surface-800">
                        № {{ $p->contract_number ?? $p->id }} (від {{ $p->contract_date ?? '—' }})
                    </option>
                @endforeach
            </x-form.select>
        </div>
        <div>
            <x-form.select label="Статус" model="status">
                <option value="В експлуатації" class="bg-surface-800">В експлуатації</option>
                <option value="На складі" class="bg-surface-800">На складі</option>
                <option value="Списано" class="bg-surface-800">Списано</option>
            </x-form.select>
        </div>
        <div>
            <x-form.select label="Акт списання" model="retirement_act_id">
                <option value="" class="bg-surface-800">— Оберіть акт —</option>
                @foreach($retirementActsList as $act)
                    <option value="{{ $act->id }}" class="bg-surface-800">
                        № {{ $act->act_number ?? $act->id }} (від {{ $act->act_date ?? '—' }})
                    </option>
                @endforeach
            </x-form.select>
        </div>
        <div>
            <x-form.input label="Примітка" model="notes" type="text" />
        </div>
    </div>
</x-ui.modal>
@endif
</div>
