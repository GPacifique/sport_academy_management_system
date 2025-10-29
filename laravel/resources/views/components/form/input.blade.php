@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'help' => null,
])

<div {{ $attributes->only('class')->class('') }}>
    @if($label)
        <label for="{{ $name }}" class="label mb-1">{{ $label }}</label>
    @endif
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" {{ $attributes->except('class')->merge(['class' => 'input']) }}>
    <x-input-error :messages="$errors->get($name)" class="mt-1" />
    @if($help)
        <div class="text-xs subtle mt-1">{{ $help }}</div>
    @endif
  </div>
