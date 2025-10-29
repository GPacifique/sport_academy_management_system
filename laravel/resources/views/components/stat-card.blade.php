@props(['title', 'value', 'icon' => null, 'trend' => null, 'color' => 'slate'])

@php
$colors = [
    'slate' => 'from-slate-500 to-slate-600',
    'blue' => 'from-blue-500 to-blue-600',
    'emerald' => 'from-emerald-500 to-emerald-600',
    'amber' => 'from-amber-500 to-amber-600',
    'fuchsia' => 'from-fuchsia-500 to-fuchsia-600',
];
$bgClass = $colors[$color] ?? $colors['slate'];
@endphp

<div class="bg-white rounded-lg shadow-md border border-slate-200 p-6 hover:shadow-lg transition-shadow">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="text-sm font-semibold text-slate-600 mb-1">{{ $title }}</div>
            <div class="text-3xl font-bold text-slate-900">{{ $value }}</div>
            @if($trend)
                <div class="mt-2 text-xs font-medium text-slate-600">{{ $trend }}</div>
            @endif
        </div>
        @if($icon)
        <div class="ml-4">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br {{ $bgClass }} flex items-center justify-center text-white text-xl shadow-md">
                {{ $icon }}
            </div>
        </div>
        @endif
    </div>
</div>
