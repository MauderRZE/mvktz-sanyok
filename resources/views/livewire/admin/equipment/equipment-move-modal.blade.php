<div>
    @if($isOpen)
    <x-ui.modal title="Переміщення комплектуючої">
        <form wire:submit.prevent="store" id="moveForm" class="space-y-4">
            
            <div class="px-4 py-3 bg-brand-500/10 rounded-xl border border-brand-500/20">
                <p class="text-sm text-brand-300">
                    <span class="font-medium text-brand-200">Вибрано:</span> {{ $targetName }}
                </p>
            </div>
            
            @error('general')
                <div class="px-4 py-3 bg-red-500/10 text-red-400 rounded-xl border border-red-500/20 text-sm">
                    {{ $message }}
                </div>
            @enderror

            <x-form.select model="location_id" label="Нове розташування (Кабінет)" required>
                <option value="">Оберіть кабінет...</option>
                @foreach($locationsList as $loc)
                    <option value="{{ $loc->id }}">Каб. {{ $loc->room_number }}</option>
                @endforeach
            </x-form.select>

            <x-form.select model="holder_id" label="Новий утримувач">
                <option value="">Без утримувача...</option>
                @foreach($holdersList as $h)
                    @php
                        $empName = $h->employee ? $h->employee->last_name . ' ' . mb_substr($h->employee->first_name, 0, 1) . '.' : null;
                        $orgName = $h->organization->org_name ?? null;
                        if ($empName && $orgName) {
                            $displayName = $empName . ' (' . $orgName . ')';
                        } elseif ($empName) {
                            $displayName = $empName;
                        } elseif ($orgName) {
                            $displayName = $orgName;
                        } else {
                            $displayName = 'Невідомий утримувач';
                        }
                    @endphp
                    <option value="{{ $h->id }}">{{ $displayName }}</option>
                @endforeach
            </x-form.select>

            <x-form.input type="datetime-local" step="1" model="action_date" label="Дата переміщення" required />

        </form>

        <x-slot name="footer">
            <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-400 bg-surface-800 border border-white/10 rounded-xl hover:text-white hover:bg-surface-700 transition-colors">
                Скасувати
            </button>
            <button type="submit" form="moveForm" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 rounded-xl hover:bg-brand-500 transition-colors">
                Підтвердити переміщення
            </button>
        </x-slot>
    </x-ui.modal>
    @endif
</div>
