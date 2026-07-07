@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col md:flex-row items-center justify-between gap-4 w-full">
            
            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-surface-800/50 rounded-lg cursor-not-allowed opacity-50 border border-white/5">
                        &laquo; Попередня
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-surface-800 hover:bg-surface-700 hover:text-white rounded-lg transition-colors border border-white/5">
                        &laquo; Попередня
                    </button>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-surface-800 hover:bg-surface-700 hover:text-white rounded-lg transition-colors border border-white/5">
                        Наступна &raquo;
                    </button>
                @else
                    <span class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-surface-800/50 rounded-lg cursor-not-allowed opacity-50 border border-white/5">
                        Наступна &raquo;
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
