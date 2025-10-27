@php($title = 'Record Payment')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header"><h1 class="page-title">Record Payment</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ route('accountant.payments.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.select label="Student" name="student_id">
                        <option value="">—</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" @selected(old('student_id')==$st->id)>{{ $st->first_name }} {{ $st->last_name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Invoice (optional)" name="invoice_id">
                        <option value="">— No Invoice —</option>
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}" @selected(old('invoice_id')==$inv->id)>
                                {{ $inv->subscription->student->first_name }} {{ $inv->subscription->student->last_name }} - 
                                Due {{ $inv->due_date->format('M d, Y') }} - 
                                {{ number_format($inv->outstanding_balance/100, 2) }} {{ $inv->currency }} due
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-form.input label="Amount (cents)" name="amount_cents" type="number" />
                    <x-form.input label="Currency" name="currency" value="RWF" />
                    <x-form.select label="Method" name="method">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                        <option value="bank">Bank</option>
                    </x-form.select>
                    <x-form.select label="Status" name="status">
                        <option value="succeeded" @selected(old('status','succeeded')==='succeeded')>Succeeded</option>
                        <option value="pending" @selected(old('status')==='pending')>Pending</option>
                        <option value="failed" @selected(old('status')==='failed')>Failed</option>
                    </x-form.select>
                    <x-form.input label="Paid at" name="paid_at" type="datetime-local" />
                    <x-form.input label="Reference" name="reference" />
                    <div class="md:col-span-2">
                        <label class="label mb-1">Notes</label>
                        <textarea name="notes" class="input min-h-24">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('accountant.payments.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Save</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
