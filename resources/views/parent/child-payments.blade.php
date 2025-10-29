@php($title = $student->first_name . ' - Payment History')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="mb-6">
        <x-button :href="route('parent.dashboard')" variant="secondary" class="mb-4">
            ← Back to Children
        </x-button>
        <h1 class="page-title">{{ $student->first_name }} {{ $student->second_name }} - Payment History</h1>
    </div>
    
    @if($student->subscriptions->isEmpty())
        <div class="card">
            <div class="card-body text-center py-8 text-slate-500">
                No subscription found for this child.
            </div>
        </div>
    @else
        @php($subscription = $student->subscriptions->first())
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-sm text-slate-600 mb-1">Plan</div>
                    <div class="text-lg font-semibold text-slate-900">{{ $subscription->plan->name }}</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-sm text-slate-600 mb-1">Outstanding Balance</div>
                    <div class="text-lg font-semibold 
                        @if($subscription->outstanding_balance > 0) text-red-600
                        @else text-green-600
                        @endif">
                        {{ number_format($subscription->outstanding_balance/100, 2) }} RWF
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-sm text-slate-600 mb-1">Total Paid</div>
                    <div class="text-lg font-semibold text-green-600">
                        {{ number_format($subscription->total_paid/100, 2) }} RWF
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold">Invoices</h2>
            </div>
            <div class="card-body p-0">
                @if($subscription->invoices->isEmpty())
                    <div class="text-center py-8 text-slate-500">No invoices yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscription->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                        <td>{{ number_format($invoice->amount_cents/100, 2) }} {{ $invoice->currency }}</td>
                                        <td>{{ number_format($invoice->total_paid/100, 2) }} {{ $invoice->currency }}</td>
                                        <td class="font-semibold
                                            @if($invoice->outstanding_balance > 0) text-red-600
                                            @else text-green-600
                                            @endif">
                                            {{ number_format($invoice->outstanding_balance/100, 2) }} {{ $invoice->currency }}
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($invoice->status === 'paid') bg-green-100 text-green-800
                                                @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                                @elseif($invoice->status === 'cancelled') bg-slate-100 text-slate-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-6">
            <div class="card-header">
                <h2 class="text-lg font-semibold">Payments</h2>
            </div>
            <div class="card-body p-0">
                @php($payments = $subscription->payments)
                @if($payments->isEmpty())
                    <div class="text-center py-8 text-slate-500">No payments yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td>{{ number_format($payment->amount_cents/100, 2) }} {{ $payment->currency }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($payment->status === 'succeeded') bg-green-100 text-green-800
                                                @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-sm text-slate-600">{{ $payment->reference ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
