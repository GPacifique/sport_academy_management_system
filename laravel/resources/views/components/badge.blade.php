@props([
    'color' => 'slate', // slate, green, red, yellow, blue, indigo
])

@php
    $map = [
        'slate' => 'badge badge-slate',
        'green' => 'badge badge-green',
        'red' => 'badge badge-red',
        'yellow' => 'badge badge-yellow',
        'blue' => 'badge badge-blue',
        'indigo' => 'badge badge-indigo',
    ];
    $classes = $map[$color] ?? $map['slate'];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
