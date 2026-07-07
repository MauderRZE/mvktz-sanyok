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
            
            <div class="text-sm text-gray-400">
                <span>Показано</span>
                <span class="font-bold text-white">{{ $paginator->firstItem() }}</span>
                <span>-</span>
                <span class="font-bold text-white">{{ $paginator->lastItem() }}</span>
                <span>із</span>
                <span class="font-bold text-white">{{ $paginator->total() }}</span>
            </div>

            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-surface-800/50 rounded-lg cursor-not-allowed opacity-50 border border-white/5">
                        &laquo;
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-surface-800 hover:bg-surface-700 hover:text-white rounded-lg transition-colors border border-white/5">
                        &laquo;
                    </button>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-transparent cursor-default">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-1.5 text-sm font-bold text-white bg-brand-600 rounded-lg shadow-lg shadow-brand-500/20 cursor-default">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="px-3 py-1.5 text-sm font-medium text-gray-400 bg-surface-800 hover:bg-surface-700 hover:text-white rounded-lg transition-colors border border-white/5">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-surface-800 hover:bg-surface-700 hover:text-white rounded-lg transition-colors border border-white/5">
                        &raquo;
                    </button>
                @else
                    <span class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-surface-800/50 rounded-lg cursor-not-allowed opacity-50 border border-white/5">
                        &raquo;
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
