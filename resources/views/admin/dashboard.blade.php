@php($title = 'Admin Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="text-slate-600 mt-1">Overview of your sports academy operations</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Branch</label>
                    <select name="branch_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Branches</option>
                        @foreach(($branches ?? []) as $b)
                            <option value="{{ $b->id }}" @selected(($currentBranchId ?? '')==$b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Timeframe</label>
                    <select name="range" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @php($r = $currentRange ?? 'today')
                        <option value="today" @selected($r==='today')>Today</option>
                        <option value="week" @selected($r==='week')>This Week</option>
                        <option value="month" @selected($r==='month')>This Month</option>
                        <option value="all" @selected($r==='all')>All Time</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- System Overview Stats -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">📊 System Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users.index') }}" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Total Users" :value="$stats['totalUsers'] ?? 0" icon="👥" color="blue" />
                </a>
                <a href="#" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Total Branches" :value="$stats['totalBranches'] ?? 0" icon="🏢" color="emerald" />
                </a>
                <a href="{{ route('admin.students.index') }}" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Active Students" :value="$stats['activeStudents'] ?? 0" icon="🎓" color="amber" />
                </a>
                <a href="{{ route('admin.sessions.index') }}" class="block hover:scale-105 transition-transform">
                    <x-stat-card :title="'Sessions — ' . ($rangeLabel ?? 'Today')" :value="$stats['todaySessions'] ?? 0" icon="📅" color="fuchsia" />
                </a>
            </div>
        </div>

        <!-- User & Training Stats -->
                <!-- User & Training Stats -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">👥 Users & Training</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users.index') }}" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Deactivated Users" :value="$stats['deactivatedUsers'] ?? 0" icon="🚫" color="slate" />
                </a>
                <a href="{{ route('admin.users.index') }}?role=coach" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Total Coaches" :value="$stats['coachUsers'] ?? 0" icon="🧑‍🏫" color="blue" />
                </a>
                <a href="#" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Total Groups" :value="$stats['totalGroups'] ?? 0" icon="🧩" color="emerald" />
                </a>
                <a href="{{ route('admin.sessions.index') }}" class="block hover:scale-105 transition-transform">
                    <x-stat-card title="Sessions This Week" :value="$stats['sessionsThisWeek'] ?? 0" icon="📆" color="amber" />
                </a>
            </div>
        </div>

        <!-- Revenue & Subscriptions -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">💰 Revenue & Subscriptions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Subscriptions -->
                <a href="{{ route('accountant.subscriptions.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Active Subscriptions</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['activeSubscriptions'] ?? 0 }}</p>
                        <p class="text-sm text-gray-800 font-semibold">of {{ $stats['totalSubscriptions'] ?? 0 }} total</p>
                    </div>
                </a>

                <!-- Revenue This Month -->
                <a href="{{ route('accountant.payments.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Revenue This Month</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['revenueThisMonth'] ?? 0) }} Rwf</p>
                        <p class="text-sm text-gray-800 font-semibold">Monthly income</p>
                    </div>
                </a>

                <!-- Pending Invoices -->
                <a href="{{ route('accountant.invoices.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Pending Invoices</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pendingInvoices'] ?? 0 }}</p>
                        <p class="text-sm text-gray-800 font-semibold">Awaiting payment</p>
                    </div>
                </a>

                <!-- Total Revenue -->
                <a href="{{ route('accountant.payments.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Total Revenue</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['totalRevenue'] ?? 0) }} Rwf</p>
                        <p class="text-sm text-gray-800 font-semibold">All-time earnings</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Expenses & Profit -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">💸 Expenses & Profitability</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pending Expenses -->
                <a href="{{ route('admin.expenses.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Pending Expenses</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pendingExpenses'] ?? 0 }}</p>
                        <p class="text-sm text-gray-800 font-semibold">Awaiting approval</p>
                    </div>
                </a>

                <!-- Expenses This Month -->
                <a href="{{ route('admin.expenses.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Expenses This Month</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['totalExpensesThisMonth'] ?? 0) }} Rwf</p>
                        <p class="text-sm text-gray-800 font-semibold">Monthly costs</p>
                    </div>
                </a>

                <!-- Total Expenses -->
                <a href="{{ route('admin.expenses.index') }}" class="block group">
                    <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-gray-900 font-bold text-base">Total Expenses</h3>
                            <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['totalExpenses'] ?? 0) }} Rwf</p>
                        <p class="text-sm text-gray-800 font-semibold">All-time costs</p>
                    </div>
                </a>

                <!-- Net Profit -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-900 font-bold text-base">Net Profit (Month)</h3>
                        <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($netProfit ?? 0) }} Rwf</p>
                    <p class="text-sm text-gray-800 font-semibold">Revenue minus expenses</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">⚡ Quick Actions</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 transition group">
                        <span class="text-2xl">👤</span>
                        <span class="font-semibold text-blue-900">Add User</span>
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition group">
                        <span class="text-2xl">🎓</span>
                        <span class="font-semibold text-emerald-900">Add Student</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-4 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-200 transition group">
                        <span class="text-2xl">🏢</span>
                        <span class="font-semibold text-purple-900">Add Branch</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-4 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 transition group">
                        <span class="text-2xl">👥</span>
                        <span class="font-semibold text-amber-900">Add Group</span>
                    </a>
                    <a href="{{ route('admin.sessions.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-fuchsia-50 hover:bg-fuchsia-100 border border-fuchsia-200 transition group">
                        <span class="text-2xl">📅</span>
                        <span class="font-semibold text-fuchsia-900">Add Session</span>
                    </a>
                    <a href="{{ route('accountant.subscriptions.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-green-50 hover:bg-green-100 border border-green-200 transition group">
                        <span class="text-2xl">✅</span>
                        <span class="font-semibold text-green-900">New Subscription</span>
                    </a>
                    <a href="{{ route('accountant.invoices.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition group">
                        <span class="text-2xl">📄</span>
                        <span class="font-semibold text-indigo-900">Create Invoice</span>
                    </a>
                    <a href="{{ route('accountant.payments.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-teal-50 hover:bg-teal-100 border border-teal-200 transition group">
                        <span class="text-2xl">💳</span>
                        <span class="font-semibold text-teal-900">View Payments</span>
                    </a>
                    <a href="{{ route('admin.expenses.create') }}" class="flex items-center gap-3 p-4 rounded-lg bg-red-50 hover:bg-red-100 border border-red-200 transition group">
                        <span class="text-2xl">💸</span>
                        <span class="font-semibold text-red-900">Add Expense</span>
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 transition group">
                        <span class="text-2xl">📊</span>
                        <span class="font-semibold text-rose-900">Manage Expenses</span>
                    </a>
                    <a href="{{ route('admin.equipment.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-cyan-50 hover:bg-cyan-100 border border-cyan-200 transition group">
                        <span class="text-2xl">⚽</span>
                        <span class="font-semibold text-cyan-900">Equipment</span>
                    </a>
                    <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 p-4 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 transition group">
                        <span class="text-2xl">💎</span>
                        <span class="font-semibold text-slate-900">Subscription Plans</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Today's Sessions -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">📅 Today's Sessions</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                @if(($sessions ?? collect())->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-500 font-medium text-lg">No sessions scheduled for today</p>
                        <a href="{{ route('admin.sessions.create') }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Schedule a Session
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($sessions as $s)
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 hover:border-indigo-300 hover:shadow-md transition-all">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 space-y-2">
                                        <div class="font-bold text-slate-900 text-lg">{{ $s->date->format('M d, Y') }} • {{ $s->start_time }}–{{ $s->end_time }}</div>
                                        <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                                            <span class="flex items-center gap-1">
                                                <span class="font-semibold">📍 Location:</span> {{ $s->location ?? 'N/A' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="font-semibold">👨‍🏫 Coach:</span> {{ $s->coach->name ?? 'N/A' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="font-semibold">🏢 Branch:</span> {{ $s->branch->name ?? 'N/A' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="font-semibold">👥 Group:</span> {{ $s->group->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                        @if($s->description)
                                            <p class="text-sm text-slate-600 mt-2">{{ Str::limit($s->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.sessions.edit', $s) }}" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
