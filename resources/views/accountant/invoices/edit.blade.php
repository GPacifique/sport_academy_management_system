@php($title = 'Edit Invoice')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header"><h1 class="page-title">Edit Invoice</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ route('accountant.invoices.update', $invoice) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="label mb-1">Student</div>
                        <div class="input">{{ $invoice->subscription->student->first_name }} {{ $invoice->subscription->student->last_name }}</div>
                    </div>
                    <div>
                        <div class="label mb-1">Plan</div>
                        <div class="input">{{ $invoice->subscription->plan->name }}</div>
                    </div>
                    <x-form.input label="Amount (cents)" name="amount_cents" type="number" :value="$invoice->amount_cents" />
                    <x-form.input label="Due date" name="due_date" type="date" :value="$invoice->due_date->format('Y-m-d')" />
                    <x-form.select label="Status" name="status">
                        <option value="pending" @selected(old('status', $invoice->status)==='pending')>Pending</option>
                        <option value="paid" @selected(old('status', $invoice->status)==='paid')>Paid</option>
                        <option value="overdue" @selected(old('status', $invoice->status)==='overdue')>Overdue</option>
                        <option value="cancelled" @selected(old('status', $invoice->status)==='cancelled')>Cancelled</option>
                    </x-form.select>
                    <div>
                        <div class="label mb-1">Outstanding Balance</div>
                        <div class="text-lg font-semibold text-slate-900">{{ number_format($invoice->outstanding_balance/100, 2) }} {{ $invoice->currency }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label mb-1">Notes</label>
                        <textarea name="notes" class="input min-h-24">{{ old('notes', $invoice->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('accountant.invoices.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Save changes</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
