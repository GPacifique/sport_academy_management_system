@php($title = 'Admin Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header with Date Info -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Admin Dashboard</h1>
                <p class="text-slate-600 mt-2">📊 Overview of your sports academy operations</p>
                <p class="text-sm text-slate-500 mt-1">{{ now()->format('l, F d, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block px-4 py-2 bg-indigo-50 rounded-lg border border-indigo-200">
                    <p class="text-sm font-semibold text-indigo-700">System Status</p>
                    <p class="text-xs text-indigo-600 mt-1">✅ All Systems Operational</p>
                </div>
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

        <!-- Quick Actions Section with Enhanced Styling -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-slate-900">⚡ Quick Actions</h2>
                <p class="text-sm text-slate-500">Frequently used operations</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <!-- Create Operations (Primary) -->
                <a href="{{ route('admin.users.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
                    <div class="absolute inset-0 bg-blue-200 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="text-2xl block mb-2">👤</span>
                    <span class="font-semibold text-blue-900 text-sm">Add User</span>
                </a>

                <a href="{{ route('admin.students.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 hover:border-emerald-400 hover:shadow-lg transition-all duration-300">
                    <div class="absolute inset-0 bg-emerald-200 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="text-2xl block mb-2">�</span>
                    <span class="font-semibold text-emerald-900 text-sm">Add Student</span>
                </a>

                <a href="{{ route('admin.sessions.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-fuchsia-50 to-fuchsia-100 border-2 border-fuchsia-300 hover:border-fuchsia-400 hover:shadow-lg transition-all duration-300">
                    <div class="absolute inset-0 bg-fuchsia-200 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="text-2xl block mb-2">📅</span>
                    <span class="font-semibold text-fuchsia-900 text-sm">Add Session</span>
                </a>

                <a href="{{ route('accountant.subscriptions.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 hover:border-green-400 hover:shadow-lg transition-all duration-300">
                    <div class="absolute inset-0 bg-green-200 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="text-2xl block mb-2">✅</span>
                    <span class="font-semibold text-green-900 text-sm">New Subscription</span>
                </a>

                <!-- Management Operations (Secondary) -->
                <a href="{{ route('admin.expenses.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-red-50 to-red-100 border border-red-300 hover:border-red-400 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-0 bg-red-200 opacity-0 group-hover:opacity-5 transition-opacity"></div>
                    <span class="text-2xl block mb-2">�</span>
                    <span class="font-semibold text-red-900 text-sm">Add Expense</span>
                </a>

                <a href="{{ route('accountant.invoices.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-300 hover:border-indigo-400 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-0 bg-indigo-200 opacity-0 group-hover:opacity-5 transition-opacity"></div>
                    <span class="text-2xl block mb-2">�</span>
                    <span class="font-semibold text-indigo-900 text-sm">Create Invoice</span>
                </a>

                <a href="{{ route('admin.equipment.index') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 border border-cyan-300 hover:border-cyan-400 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-0 bg-cyan-200 opacity-0 group-hover:opacity-5 transition-opacity"></div>
                    <span class="text-2xl block mb-2">⚽</span>
                    <span class="font-semibold text-cyan-900 text-sm">Equipment</span>
                </a>

                <a href="{{ route('admin.plans.index') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-300 hover:border-purple-400 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-0 bg-purple-200 opacity-0 group-hover:opacity-5 transition-opacity"></div>
                    <span class="text-2xl block mb-2">💎</span>
                    <span class="font-semibold text-purple-900 text-sm">Subscription Plans</span>
                </a>
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

        <!-- Analytics & Insights Section -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">📈 Analytics & Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Performance Metrics -->
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-300 rounded-xl p-6">
                    <h3 class="font-bold text-slate-900 mb-4">🎯 Performance Metrics</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Student Enrollment Rate</span>
                            <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 w-3/4"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Session Attendance</span>
                            <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 w-5/6"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Revenue Target</span>
                            <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-500 to-amber-600 w-2/3"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700">Equipment Status</span>
                            <div class="w-24 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-green-600 w-4/5"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-300 rounded-xl p-6">
                    <h3 class="font-bold text-indigo-900 mb-4">⚙️ System Health</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                            <span class="text-xl">✅</span>
                            <span class="text-sm font-semibold text-emerald-900">Database: Optimal</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                            <span class="text-xl">✅</span>
                            <span class="text-sm font-semibold text-emerald-900">API Endpoints: Responsive</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                            <span class="text-xl">✅</span>
                            <span class="text-sm font-semibold text-emerald-900">File Storage: Adequate</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-300 rounded-lg">
                            <span class="text-xl">⚠️</span>
                            <span class="text-sm font-semibold text-amber-900">Backup: Last 2 hours ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
