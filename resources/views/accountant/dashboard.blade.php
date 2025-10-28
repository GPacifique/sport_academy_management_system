@php($title = 'Accountant Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Accountant Dashboard</h1>
            <p class="text-slate-600 mt-1">Manage finances, fees, and payments</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('accountant.payments.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Revenue — This Month" value="{{ number_format(($totalRevenueCents ?? 0)/100, 2) }} RWF" icon="💰" color="emerald" trend="{{ now()->format('M Y') }}" />
            </a>
            <a href="{{ route('accountant.invoices.index') }}?status=pending,overdue" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Outstanding Balance" value="{{ number_format(($outstandingCents ?? 0)/100, 2) }} RWF" icon="📊" color="amber" trend="Pending + Overdue" />
            </a>
            <a href="{{ route('accountant.invoices.index') }}?status=pending" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Pending Invoices" value="{{ $pendingInvoices ?? 0 }}" icon="🕒" color="blue" trend="Awaiting payment" />
            </a>
            <a href="{{ route('accountant.invoices.index') }}?status=overdue" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Overdue Invoices" value="{{ $overdueInvoices ?? 0 }}" icon="⚠️" color="rose" trend="Past due" />
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('accountant.subscriptions.index') }}?status=active" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Active Subscriptions" value="{{ $activeSubscriptions ?? 0 }}" icon="✅" color="green" trend="Currently active" />
            </a>
            <a href="{{ route('accountant.subscriptions.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Total Subscriptions" value="{{ $totalSubscriptions ?? 0 }}" icon="📋" color="indigo" trend="All time" />
            </a>
            <a href="{{ route('accountant.payments.index') }}?status=succeeded" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Succeeded Payments" value="{{ $succeededPayments ?? 0 }}" icon="✔️" color="teal" trend="All successful" />
            </a>
            <a href="{{ route('accountant.payments.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Total Revenue (All Time)" value="{{ number_format(($totalRevenueAllTime ?? 0)/100, 2) }} RWF" icon="💎" color="purple" trend="Grand total" />
            </a>
        </div>

        <!-- Expense Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('admin.expenses.index') }}?status=pending" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Pending Expenses" value="{{ $pendingExpenses ?? 0 }}" icon="⏳" color="amber" trend="Awaiting approval" />
            </a>
            <a href="{{ route('admin.expenses.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Expenses This Month" value="{{ number_format(($totalExpensesThisMonth ?? 0)/100, 2) }} RWF" icon="📤" color="red" trend="{{ now()->format('M Y') }}" />
            </a>
            <a href="{{ route('accountant.payments.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Net Profit This Month" value="{{ number_format(($netProfitThisMonth ?? 0)/100, 2) }} RWF" icon="💹" color="{{ $netProfitColor ?? 'green' }}" trend="Revenue - Expenses" />
            </a>
            <a href="{{ route('admin.expenses.index') }}" class="block hover:scale-105 transition-transform">
                <x-stat-card title="Manage Expenses" value="View All" icon="💰" color="slate" trend="Financial control" />
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Recent Transactions</h2>
                @if(($recentPayments ?? collect())->isEmpty())
                    <div class="text-sm font-medium text-slate-600">No transactions recorded yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="font-semibold">Date</th>
                                    <th class="font-semibold">Student</th>
                                    <th class="font-semibold">Plan</th>
                                    <th class="font-semibold">Amount</th>
                                    <th class="font-semibold">Method</th>
                                    <th class="font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $p)
                                    <tr>
                                        <td class="font-medium">{{ optional($p->paid_at)->format('M d, Y H:i') ?? $p->created_at->format('M d, Y H:i') }}</td>
                                        <td class="font-medium">{{ $p->student?->first_name }} {{ $p->student?->second_name }}</td>
                                        <td class="font-medium">{{ $p->subscription?->plan?->name ?? '—' }}</td>
                                        <td class="font-semibold">{{ number_format($p->amount_cents/100,2) }} {{ $p->currency }}</td>
                                        <td class="font-medium">{{ ucfirst(str_replace('_',' ',$p->method)) }}</td>
                                        <td>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                @if($p->status === 'succeeded') bg-green-100 text-green-800
                                                @elseif($p->status === 'failed') bg-rose-100 text-rose-800
                                                @else bg-amber-100 text-amber-800
                                                @endif">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

                        <div class="bg-white rounded-lg shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('accountant.payments.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium transition">
                        <span>💳</span><span>Record Payment</span>
                    </a>
                    <a href="{{ route('accountant.invoices.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium transition">
                        <span>📄</span><span>View Invoices</span>
                    </a>
                    <a href="{{ route('accountant.payments.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium transition">
                        <span>📊</span><span>Payments & Export</span>
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-md bg-red-50 hover:bg-red-100 text-slate-900 font-medium transition">
                        <span>💰</span><span>Manage Expenses</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection