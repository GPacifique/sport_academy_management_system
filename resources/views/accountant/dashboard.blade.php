@php($title = 'Accountant Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Hero (reuse footer styling: gradient + animated blobs) -->
        <div style="position: relative; background: linear-gradient(to bottom right, #1e3a8a, #6b21a8, #be185d); color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
            <div style="position: absolute; inset: 0; opacity: 0.12; pointer-events: none;">
                <div style="position: absolute; top: -1.5rem; left: -3rem; width: 16rem; height: 16rem; background-color: #3b82f6; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(64px);" class="animate-blob"></div>
                <div style="position: absolute; top: -1rem; right: -3rem; width: 14rem; height: 14rem; background-color: #ec4899; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(64px);" class="animate-blob animation-delay-2000"></div>
                <div style="position: absolute; bottom: -2rem; left: 4rem; width: 18rem; height: 18rem; background-color: #a855f7; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(64px);" class="animate-blob animation-delay-4000"></div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="position: relative; z-index: 2;">
                <div>
                    <h1 class="text-3xl font-extrabold">Finances & Billing</h1>
                    <p class="text-emerald-100 mt-1">Overview of revenue, invoices, and expenses</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-sm text-emerald-50">{{ now()->format('l, F d, Y') }}</div>
                    <a href="{{ route('accountant.payments.create') }}" class="px-4 py-2 bg-white text-emerald-700 rounded-lg font-semibold">Record Payment</a>
                </div>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 h-full">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs text-slate-500 font-semibold">Revenue — This Month</div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format(($totalRevenueCents ?? 0)/100, 2) }} RWF</div>
                            <div class="text-xs text-slate-400 mt-1">{{ now()->format('M Y') }}</div>
                        </div>
                        <div class="text-3xl">💰</div>
                    </div>
                    <div class="mt-4">
                        <canvas id="revenueSparkline" height="72" style="width:100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 h-full">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs text-slate-500 font-semibold">Outstanding Balance</div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format(($outstandingCents ?? 0)/100, 2) }} RWF</div>
                            <div class="text-xs text-slate-400 mt-1">Pending + Overdue</div>
                        </div>
                        <div class="text-3xl">📊</div>
                    </div>
                    <div class="mt-4">
                        <canvas id="agingChart" height="72" style="width:100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <div class="text-xs text-slate-500">Pending Invoices</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $pendingInvoices ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Awaiting payment</div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <div class="text-xs text-slate-500">Overdue Invoices</div>
                    <div class="mt-2 text-xl font-bold text-slate-900">{{ $overdueInvoices ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Past due</div>
                </div>
            </div>
        </div>

        <!-- Subscriptions / Revenue Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Active Subscriptions</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ $activeSubscriptions ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">of {{ $totalSubscriptions ?? 0 }} total</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Succeeded Payments</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ $succeededPayments ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">All-time</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Total Revenue (All Time)</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format(($totalRevenueAllTime ?? 0)/100, 2) }} RWF</div>
                <div class="text-xs text-slate-400 mt-1">Grand total</div>
            </div>
        </div>

        <!-- Expenses and Profit -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Expenses This Month</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format(($totalExpensesThisMonth ?? 0)/100, 2) }} RWF</div>
                <div class="text-xs text-slate-400 mt-1">Monthly costs</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Pending Expenses</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ $pendingExpenses ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Awaiting approval</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="text-xs text-slate-500">Net Profit (Month)</div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format(($netProfitThisMonth ?? 0)/100, 2) }} RWF</div>
                <div class="text-xs text-slate-400 mt-1">Revenue minus expenses</div>
            </div>
        </div>

        <!-- Recent transactions + Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Recent Transactions</h2>
                @if(($recentPayments ?? collect())->isEmpty())
                    <div class="text-sm font-medium text-slate-600">No transactions recorded yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="font-semibold text-left">Date</th>
                                    <th class="font-semibold text-left">Student</th>
                                    <th class="font-semibold text-left">Plan</th>
                                    <th class="font-semibold text-right">Amount</th>
                                    <th class="font-semibold text-left">Method</th>
                                    <th class="font-semibold text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $p)
                                    <tr class="border-t">
                                        <td class="py-3">{{ optional($p->paid_at)->format('M d, Y H:i') ?? $p->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3">{{ $p->student?->first_name }} {{ $p->student?->second_name }}</td>
                                        <td class="py-3">{{ $p->subscription?->plan?->name ?? '—' }}</td>
                                        <td class="py-3 text-right font-semibold">{{ number_format($p->amount_cents/100,2) }} {{ $p->currency }}</td>
                                        <td class="py-3">{{ ucfirst(str_replace('_',' ',$p->method)) }}</td>
                                        <td class="py-3">
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
                <div class="space-y-3">
                    <a href="{{ route('accountant.payments.create') }}" class="flex items-center justify-between gap-2 px-4 py-3 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold transition">
                        <div class="flex items-center gap-2"><span>💳</span><span>Record Payment</span></div>
                        <span class="text-sm text-slate-600">New</span>
                    </a>
                    <a href="{{ route('accountant.invoices.index') }}" class="flex items-center justify-between gap-2 px-4 py-3 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium transition">
                        <div class="flex items-center gap-2"><span>📄</span><span>Invoices</span></div>
                        <span class="text-sm text-slate-600">Manage</span>
                    </a>
                    <a href="{{ route('accountant.payments.index') }}" class="flex items-center justify-between gap-2 px-4 py-3 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-900 font-medium transition">
                        <div class="flex items-center gap-2"><span>📊</span><span>Payments & Export</span></div>
                        <span class="text-sm text-slate-600">Reports</span>
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="flex items-center justify-between gap-2 px-4 py-3 rounded-md bg-red-50 hover:bg-red-100 text-red-700 font-medium transition">
                        <div class="flex items-center gap-2"><span>💰</span><span>Manage Expenses</span></div>
                        <span class="text-sm text-slate-600">Expenses</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js CDN (small, only for sparklines) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const metricsUrl = "{{ route('accountant.dashboard.metrics') }}";

            async function fetchMetrics() {
                try {
                    const res = await fetch(metricsUrl, { credentials: 'same-origin' });
                    if (!res.ok) throw new Error('Failed to load metrics');
                    return await res.json();
                } catch (err) {
                    console.error('Metrics error', err);
                    return null;
                }
            }

            function renderRevenueSparkline(ctx, labels, data) {
                new Chart(ctx, {
                    type: 'line',
                    data: { labels: labels, datasets: [{ data: data.map(v => (v/100)), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.08)', fill: true, tension: 0.3, pointRadius: 0 }]},
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false } }, elements: { line: { borderWidth: 2 } } }
                });
            }

            function renderAgingChart(ctx, labels, data) {
                new Chart(ctx, {
                    type: 'bar',
                    data: { labels: labels, datasets: [{ data: data.map(v => (v/100)), backgroundColor: ['#60a5fa','#f59e0b','#fb7185','#ef4444','#8b5cf6'] }]},
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { display: false } } }
                });
            }

            document.addEventListener('DOMContentLoaded', async function(){
                const metrics = await fetchMetrics();
                if (!metrics) return;

                // Revenue sparkline
                const revCtx = document.getElementById('revenueSparkline');
                if (revCtx && metrics.monthlyRevenue) {
                    renderRevenueSparkline(revCtx, metrics.monthlyRevenue.labels, metrics.monthlyRevenue.data);
                }

                // Aging chart
                const agingCtx = document.getElementById('agingChart');
                if (agingCtx && metrics.agingBuckets) {
                    const labels = Object.keys(metrics.agingBuckets);
                    const values = Object.values(metrics.agingBuckets);
                    renderAgingChart(agingCtx, labels, values);
                }
            });
        })();
    </script>
@endpush