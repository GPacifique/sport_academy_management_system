@php($title = 'Payments')
@extends('layouts.app')

@section('content')
<div class="container-page space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="page-title">Payments</h1>
        <div class="flex gap-2">
            <form method="GET" action="{{ route('accountant.payments.export') }}" class="inline">
                @if(request('from'))
                    <input type="hidden" name="from" value="{{ request('from') }}">
                @endif
                @if(request('to'))
                    <input type="hidden" name="to" value="{{ request('to') }}">
                @endif
                <x-button type="submit" variant="secondary">Export to CSV</x-button>
            </form>
            <x-button :href="route('accountant.payments.create')" variant="primary">Record Payment</x-button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('accountant.payments.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <x-form.input label="From Date" name="from" type="date" :value="request('from')" />
                <x-form.input label="To Date" name="to" type="date" :value="request('to')" />
                <div class="flex items-end">
                    <x-button type="submit" variant="primary" class="w-full">Filter</x-button>
                </div>
            </form>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Subscription</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                    <tr>
                        <td class="px-4 py-3">{{ optional($p->paid_at)->format('Y-m-d H:i') ?? $p->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $p->student->first_name }} {{ $p->student->second_name }}</td>
                        <td class="px-4 py-3">{{ optional($p->subscription?->plan)->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ', $p->method)) }}</td>
                        <td class="px-4 py-3">
                            <x-badge color="{{ $p->status === 'succeeded' ? 'green' : ($p->status === 'pending' ? 'yellow' : 'red') }}">{{ ucfirst($p->status) }}</x-badge>
                        </td>
                        <td class="px-4 py-3 text-right">{{ number_format($p->amount_cents/100, 2) }} {{ $p->currency }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
