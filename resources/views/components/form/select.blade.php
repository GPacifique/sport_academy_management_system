@props([
    'label' => null,
    'name',
    'value' => null,
    'placeholder' => '—',
])

<div {{ $attributes->only('class')->class('') }}>
    @if($label)
        <label for="{{ $name }}" class="label mb-1">{{ $label }}</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->except('class')->merge(['class' => 'select']) }}>
        @if(!is_null($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-1" />
  </div>
