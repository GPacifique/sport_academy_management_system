@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    
    {{-- Hero Section --}}
    <div class="footer-like-hero relative overflow-hidden">
        <div class="hero-blob-layer">
            <div class="hero-blob blob-1"></div>
            <div class="hero-blob blob-2"></div>
            <div class="hero-blob blob-3"></div>
        </div>
        
        <div class="relative z-10 container mx-auto px-6 py-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Financial Dashboard</h1>
            <p class="text-emerald-100">Monitor revenue, expenses, and financial health.</p>
        </div>
    </div>

    <div class="container mx-auto px-6 -mt-8">
        
        {{-- Financial KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Revenue This Month --}}
            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Revenue (This Month)</p>
                            <h3 class="text-3xl font-bold text-emerald-600 dark:text-emerald-400" data-animate-count>
                                {{ number_format($totalRevenueCents / 100, 0) }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                <span class="{{ $revenueChangeDirection === 'up' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $revenueChangeDirection === 'up' ? '↑' : '↓' }} {{ abs($revenueChange) }}%
                                </span> vs last month
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Expenses This Month --}}
            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Expenses (This Month)</p>
                            <h3 class="text-3xl font-bold text-orange-600 dark:text-orange-400" data-animate-count>
                                {{ number_format($totalExpensesThisMonth / 100, 0) }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                <span class="text-amber-600 dark:text-amber-400">{{ $pendingExpenses }}</span> pending
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Net Profit</p>
                            <h3 class="text-3xl font-bold {{ $netProfitColor === 'green' ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}" data-animate-count>
                                {{ number_format($netProfitThisMonth / 100, 0) }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">RWF</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br {{ $netProfitColor === 'green' ? 'from-green-500 to-green-600' : 'from-rose-500 to-rose-600' }} rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $netProfitColor === 'green' ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Outstanding Balance --}}
            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Outstanding</p>
                            <h3 class="text-3xl font-bold text-red-600 dark:text-red-400" data-animate-count>
                                {{ number_format($outstandingCents / 100, 0) }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                <span class="text-red-600 dark:text-red-400">{{ $overdueInvoices }}</span> overdue
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Secondary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Subscriptions</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeSubscriptions }}/{{ $totalSubscriptions }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Payments</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $succeededPayments }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Pending Invoices</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $pendingInvoices }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Total Revenue</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalRevenueAllTime / 100, 0) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Payment Methods Chart --}}
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Payment Methods (This Month)</h3>
                    <div class="card-chart">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Expense Categories Chart --}}
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Expense Categories (This Month)</h3>
                    <div class="card-chart">
                        <canvas id="expenseCategoriesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mb-8">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('accountant.payments.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Payments</span>
                    </a>

                    <a href="{{ route('accountant.invoices.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Invoices</span>
                    </a>

                    <a href="{{ route('accountant.subscriptions.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Subscriptions</span>
                    </a>

                    <a href="{{ route('admin.expenses.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Expenses</span>
                    </a>

                    <a href="{{ route('admin.plans.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Plans</span>
                    </a>

                    <a href="{{ route('admin.students.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors group">
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Students</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Payments Table --}}
        @if($recentPayments->count() > 0)
        <div class="card mb-8">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Recent Payments</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($recentPayments as $payment)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ $payment->student->first_name ?? 'N/A' }} {{ $payment->student->second_name ?? '' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ number_format($payment->amount_cents / 100, 0) }} RWF</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $payment->status === 'succeeded' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Chart.js Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
    if (paymentMethodsCtx) {
        const paymentData = @json($paymentMethodBreakdown);
        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(paymentData),
                datasets: [{
                    data: Object.values(paymentData).map(v => v / 100),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Expense Categories Chart
    const expenseCategoriesCtx = document.getElementById('expenseCategoriesChart');
    if (expenseCategoriesCtx) {
        const expenseData = @json($expenseCategories);
        new Chart(expenseCategoriesCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(expenseData),
                datasets: [{
                    data: Object.values(expenseData).map(v => v / 100),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(251, 146, 60, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(236, 72, 153, 0.7)',
                        'rgba(14, 165, 233, 0.7)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>
@endsection
