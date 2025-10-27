@php($title = 'Edit Plan')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header"><h1 class="page-title">Edit Plan</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input label="Name" name="name" :value="$plan->name" required />
                    <x-form.input label="Price (cents)" name="price_cents" type="number" :value="$plan->price_cents" required />
                    <x-form.input label="Currency" name="currency" :value="$plan->currency" />
                    <x-form.select label="Interval" name="interval">
                        <option value="monthly" @selected($plan->interval==='monthly')>Monthly</option>
                        <option value="quarterly" @selected($plan->interval==='quarterly')>Quarterly</option>
                        <option value="yearly" @selected($plan->interval==='yearly')>Yearly</option>
                    </x-form.select>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="active" value="1" class="rounded border-slate-300" @checked($plan->active)>
                            <span class="label">Active</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label mb-1">Description</label>
                        <textarea name="description" class="input min-h-24">{{ old('description', $plan->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('admin.plans.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Save changes</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
