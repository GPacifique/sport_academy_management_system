@props([
    'type' => 'info', // success, error, warning, info
])

@php
    $base = 'rounded-md p-3 text-sm';
    $map = [
        'success' => 'bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-200',
        'error' => 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-200',
        'warning' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
        'info' => 'bg-slate-50 text-slate-800 dark:bg-slate-800/60 dark:text-slate-200',
    ];
    $classes = $base.' '.($map[$type] ?? $map['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    
    {{-- Optional close button slot --}}
    @if(isset($close))
        <div class="float-right">{{ $close }}</div>
    @endif
  </div>
