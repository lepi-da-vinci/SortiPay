@props(['category'])

@php
    $value = $category instanceof \App\Enums\WasteCategory ? $category->value : (string) $category;
@endphp

@if ($value === 'ORGANIK')
    {{-- Organic Leaf Icon --}}
    <div class="w-10 h-10 rounded-2xl bg-lime-100 text-lime-700 border border-lime-200 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 21c.5-4.5 2.5-8 7-10 4.5-2 7-5 7-8-3 0-7 1-10 4-3 3-4 7-4 14z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 21c2-2 5-4 8-5"/>
        </svg>
    </div>
@elseif ($value === 'ANORGANIK')
    {{-- Bottle / Can Icon --}}
    <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 border border-sky-200 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 3h4v3l2 3v10a2 2 0 01-2 2h-4a2 2 0 01-2-2V9l2-3V3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 13h8"/>
        </svg>
    </div>
@elseif ($value === 'DAUR_ULANG')
    {{-- Recycle Arrows Icon --}}
    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
@else
    {{-- Special / B3 / Oil Droplet Icon --}}
    <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5c-3.5 5-6 8-6 11a6 6 0 1012 0c0-3-2.5-6-6-11z"/>
        </svg>
    </div>
@endif
