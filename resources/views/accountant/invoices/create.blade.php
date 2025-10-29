@php($title = 'Create Invoice')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header"><h1 class="page-title">Create Invoice</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ route('accountant.invoices.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.select label="Subscription" name="subscription_id" class="md:col-span-2">
                        <option value="">—</option>
                        @foreach($subscriptions as $sub)
                            <option value="{{ $sub->id }}" @selected(old('subscription_id')==$sub->id)>
                                {{ $sub->student->first_name }} {{ $sub->student->second_name }} — {{ $sub->plan->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.input label="Amount (cents)" name="amount_cents" type="number" />
                    <x-form.input label="Currency" name="currency" value="RWF" />
                    <x-form.input label="Due date" name="due_date" type="date" />
                    <div class="md:col-span-2">
                        <label class="label mb-1">Notes</label>
                        <textarea name="notes" class="input min-h-24">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('accountant.invoices.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Create</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
