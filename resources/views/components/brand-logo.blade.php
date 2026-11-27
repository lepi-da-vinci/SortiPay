@props([
    'size' => 'md',
    'showText' => true,
    'subtitle' => null,
    'dark' => false,
])

@php
    $boxSize = match ($size) {
        'sm' => 'w-8 h-8 rounded-lg',
        'lg' => 'w-12 h-12 rounded-2xl',
        default => 'w-10 h-10 rounded-xl',
    };
    $svgSize = match ($size) {
        'sm' => 'w-5 h-5',
        'lg' => 'w-7 h-7',
        default => 'w-6 h-6',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <div class="{{ $boxSize }} bg-emerald-600 bg-linear-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center shadow-sm ring-1 ring-emerald-400/30 shrink-0 relative overflow-hidden">
        {{-- Custom Recycling Bin + Leaf + Coin SVG Logo --}}
        <svg class="{{ $svgSize }}" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            {{-- Recycling Bin Lid --}}
            <path d="M8 10.5H24M12.5 10.5V8.5C12.5 7.67157 13.1716 7 14 7H18C18.8284 7 19.5 7.67157 19.5 8.5V10.5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
            {{-- Bin Body --}}
            <path d="M9.5 11L10.7 24.5C10.8 25.6 11.7 26.5 12.8 26.5H19.2C20.3 26.5 21.2 25.6 21.3 24.5L22.5 11" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
            {{-- Eco Leaf inside Bin --}}
            <path d="M16 22V17.5M16 17.5C16 15.3 17.8 13.8 20 14C19.8 16.2 18.2 17.5 16 17.5ZM16 19C16 17.2 14.5 16 12.8 16.2C13 17.9 14.3 19 16 19Z" stroke="#FDE68A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            {{-- Sparkle Coin Dot --}}
            <circle cx="24" cy="7.5" r="2.2" fill="#FBBF24"/>
        </svg>
    </div>

    @if ($showText)
        <div class="leading-tight">
            <span class="font-extrabold tracking-tight {{ $size === 'lg' ? 'text-2xl' : ($size === 'sm' ? 'text-base' : 'text-lg') }} {{ $dark ? 'text-white' : 'text-slate-900' }} block">
                Sorti<span class="{{ $dark ? 'text-amber-300' : 'text-emerald-600' }}">Pay</span>
            </span>
            @if ($subtitle)
                <span class="text-[11px] font-semibold {{ $dark ? 'text-emerald-200' : 'text-emerald-600' }} block">{{ $subtitle }}</span>
            @endif
        </div>
    @endif
</div>
