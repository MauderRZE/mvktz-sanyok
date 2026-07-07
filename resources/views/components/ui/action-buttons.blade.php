@props(['id' => null, 'viewAction' => false, 'editAction' => null, 'deleteAction' => null, 'moveAction' => null])

@php
$view = ($viewAction === true) ? ($id ? "view({$id})" : "") : $viewAction;
$edit = $editAction ?? ($id ? "edit({$id})" : "");
$delete = $deleteAction ?? ($id ? "delete({$id})" : "");
$move = $moveAction ?? false;
@endphp

<div class="flex items-center justify-end gap-1">
    @if($moveAction !== false && $move)
    <button wire:click="{{ $move }}" class="p-2 rounded-lg text-gray-500 hover:text-green-400 hover:bg-green-500/10 transition-colors" title="Перемістити">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
    </button>
    @endif
    @if($viewAction !== false && $view)
    <button wire:click="{{ $view }}" class="p-2 rounded-lg text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 transition-colors" title="Переглянути">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    </button>
    @endif
    @if($editAction !== false && $edit)
    <button wire:click="{{ $edit }}" class="p-2 rounded-lg text-gray-500 hover:text-brand-400 hover:bg-brand-500/10 transition-colors" title="Редагувати">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    </button>
    @endif
    @if($deleteAction !== false && $delete)
    <button wire:confirm="Ви впевнені, що хочете видалити цей запис?" wire:click="{{ $delete }}" class="p-2 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Видалити">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </button>
    @endif
</div>
