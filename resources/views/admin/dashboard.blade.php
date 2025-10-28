@extends('layouts.app')@php($title = 'Admin Dashboard')

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

@section('content')    <div class="space-y-8">

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">        <!-- Hero (styled like footer: gradient + animated blobs) -->

            <div class="footer-like-hero" style="margin-bottom: 0.5rem;">

    {{-- Hero Section with Filters --}}            <div class="hero-blob-layer" aria-hidden="true">

    <div class="footer-like-hero relative overflow-hidden">                <div class="hero-blob animate-blob" style="top: -2rem; left: -4rem; width: 20rem; height: 20rem; background-color: #3b82f6;"></div>

        <div class="hero-blob-layer">                <div class="hero-blob animate-blob animation-delay-2000" style="top: -1rem; right: -4rem; width: 18rem; height: 18rem; background-color: #ec4899;"></div>

            <div class="hero-blob blob-1"></div>                <div class="hero-blob animate-blob animation-delay-4000" style="bottom: -3rem; left: 6rem; width: 22rem; height: 22rem; background-color: #a855f7;"></div>

            <div class="hero-blob blob-2"></div>            </div>

            <div class="hero-blob blob-3"></div>

        </div>            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="position: relative; z-index: 2;">

                        <div>

        <div class="relative z-10 container mx-auto px-6 py-8">                    <h1 class="text-4xl font-extrabold">Admin Dashboard</h1>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">                    <p class="mt-1 text-indigo-100">A high-level view of academy operations</p>

                <div>                    <p class="mt-2 text-indigo-200 text-sm">{{ now()->format('l, F d, Y') }}</p>

                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Admin Dashboard</h1>                </div>

                    <p class="text-blue-100">Welcome back! Here's what's happening today.</p>                <div class="flex items-center gap-3">

                </div>                    <div class="text-right px-4 py-2 bg-white/10 rounded-lg border border-white/10">

                                        <p class="text-xs text-white/90 font-semibold">System Status</p>

                {{-- Filters --}}                        <p class="text-sm text-white/80 mt-1">✅ All Systems Operational</p>

                <div class="flex flex-wrap gap-3">                    </div>

                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-3">                    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-white text-indigo-700 font-semibold rounded-lg shadow-sm hover:shadow-md">➕ Add User</a>

                        <select name="range" onchange="this.form.submit()"                 </div>

                                class="px-4 py-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-lg border border-white/20 shadow-lg">            </div>

                            <option value="today" {{ $currentRange === 'today' ? 'selected' : '' }}>Today</option>        </div>

                            <option value="week" {{ $currentRange === 'week' ? 'selected' : '' }}>This Week</option>

                            <option value="month" {{ $currentRange === 'month' ? 'selected' : '' }}>This Month</option>        <!-- Compact Filters + KPI Row -->

                            <option value="all" {{ $currentRange === 'all' ? 'selected' : '' }}>All Time</option>        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        </select>            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row md:items-center gap-4">

                                        <form method="GET" class="flex-1 flex flex-wrap items-center gap-3">

                        <select name="branch_id" onchange="this.form.submit()"                     <input type="hidden" name="view" value="{{ request('view') }}">

                                class="px-4 py-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-lg border border-white/20 shadow-lg">                    <select name="branch_id" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">

                            <option value="">All Branches</option>                        <option value="">All Branches</option>

                            @foreach($branches as $branch)                        @foreach(($branches ?? []) as $b)

                                <option value="{{ $branch->id }}" {{ $currentBranchId == $branch->id ? 'selected' : '' }}>                            <option value="{{ $b->id }}" {{ (($currentBranchId ?? '') == $b->id) ? 'selected' : '' }}>{{ $b->name }}</option>

                                    {{ $branch->name }}                        @endforeach

                                </option>                    </select>

                            @endforeach                    <select name="range" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">

                        </select>                        @php($r = $currentRange ?? 'today')

                    </form>                        <option value="today" {{ ($r==='today') ? 'selected' : '' }}>Today</option>

                </div>                        <option value="week" {{ ($r==='week') ? 'selected' : '' }}>This Week</option>

            </div>                        <option value="month" {{ ($r==='month') ? 'selected' : '' }}>This Month</option>

        </div>                        <option value="all" {{ ($r==='all') ? 'selected' : '' }}>All Time</option>

    </div>                    </select>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Apply</button>

    <div class="container mx-auto px-6 -mt-8">                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-slate-100 rounded-lg text-sm">Reset</a>

                        </form>

        {{-- Main KPI Cards --}}                <div class="hidden md:flex items-center gap-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">                    <div class="text-sm text-slate-500">Quick overview</div>

            {{-- Total Users --}}                </div>

            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">            </div>

                <div class="card-body">

                    <div class="flex items-start justify-between">            <div class="lg:col-span-1 grid grid-cols-2 gap-3">

                        <div class="flex-1">                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">

                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Total Users</p>                    <div class="text-xs text-slate-500">Students</div>

                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white" data-animate-count>{{ $stats['totalUsers'] }}</h3>                        <div class="text-2xl font-bold text-slate-900" data-animate-count>{{ $stats['activeStudents'] ?? 0 }}</div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">                    <div class="text-xs text-slate-400 mt-1">Active</div>

                                <span class="text-blue-600 dark:text-blue-400">{{ $stats['coachUsers'] }}</span> coaches                </div>

                            </p>                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">

                        </div>                    <div class="text-xs text-slate-500">Branches</div>

                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">                        <div class="text-2xl font-bold text-slate-900" data-animate-count>{{ $stats['totalBranches'] ?? 0 }}</div>

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">                    <div class="text-xs text-slate-400 mt-1">Locations</div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>                </div>

                            </svg>            </div>

                        </div>        </div>

                    </div>

                </div>        <!-- Top KPI Cards -->

            </div>        <div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Active Students --}}                <a href="{{ route('admin.users.index') }}" class="block">

            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">                    <div class="card">

                <div class="card-body">                        <div class="card-body p-6">

                    <div class="flex items-start justify-between">                            <div class="flex items-start justify-between">

                        <div class="flex-1">                                <div>

                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Active Students</p>                                    <div class="text-xs text-slate-500 font-semibold">Total Users</div>

                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white" data-animate-count>{{ $stats['activeStudents'] }}</h3>                                    <div class="mt-2 text-3xl font-extrabold text-slate-900" data-animate-count>{{ $stats['totalUsers'] ?? 0 }}</div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">                                </div>

                                <span class="text-green-600 dark:text-green-400">{{ $stats['activeSubscriptions'] }}</span> with subscriptions                                <div class="text-3xl">👥</div>

                            </p>                            </div>

                        </div>                            <div class="mt-3 text-sm text-slate-500">Active accounts across the system</div>

                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">                        </div>

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">                    </div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>                </a>

                            </svg>

                        </div>                <a href="#" class="block">

                    </div>                    <div class="card">

                </div>                        <div class="card-body p-6">

            </div>                            <div class="flex items-start justify-between">

                                <div>

            {{-- Training Sessions --}}                                    <div class="text-xs text-slate-500 font-semibold">Branches</div>

            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">                                    <div class="mt-2 text-3xl font-extrabold text-slate-900" data-animate-count>{{ $stats['totalBranches'] ?? 0 }}</div>

                <div class="card-body">                                </div>

                    <div class="flex items-start justify-between">                                <div class="text-3xl">🏢</div>

                        <div class="flex-1">                            </div>

                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Sessions ({{ $rangeLabel }})</p>                            <div class="mt-3 text-sm text-slate-500">Active branch locations</div>

                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white" data-animate-count>{{ $stats['todaySessions'] }}</h3>                        </div>

                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">                    </div>

                                <span class="text-purple-600 dark:text-purple-400">{{ $stats['sessionsThisWeek'] }}</span> this week                </a>

                            </p>

                        </div>                <a href="{{ route('admin.students.index') }}" class="block">

                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">                    <div class="card">

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <div class="card-body p-6">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>                            <div class="flex items-start justify-between">

                            </svg>                                <div>

                        </div>                                    <div class="text-xs text-slate-500 font-semibold">Active Students</div>

                    </div>                                    <div class="mt-2 text-3xl font-extrabold text-slate-900" data-animate-count>{{ $stats['activeStudents'] ?? 0 }}</div>

                </div>                                </div>

            </div>                                <div class="text-3xl">🎓</div>

                            </div>

            {{-- Monthly Revenue --}}                            <div class="mt-3 text-sm text-slate-500">Currently enrolled</div>

            <div class="card group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">                        </div>

                <div class="card-body">                    </div>

                    <div class="flex items-start justify-between">                </a>

                        <div class="flex-1">

                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Revenue (Month)</p>                <a href="{{ route('admin.sessions.index') }}" class="block">

                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white" data-animate-count>                    <div class="card">

                                {{ number_format(($stats['revenueThisMonth'] ?? 0) / 100, 0) }}                        <div class="card-body p-6">

                            </h3>                            <div class="flex items-start justify-between">

                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">RWF</p>                                <div>

                        </div>                                    <div class="text-xs text-slate-500 font-semibold">Sessions ({{ $rangeLabel ?? 'Today' }})</div>

                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">                                    <div class="mt-2 text-3xl font-extrabold text-slate-900" data-animate-count>{{ $stats['todaySessions'] ?? 0 }}</div>

                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">                                </div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>                                <div class="text-3xl">📅</div>

                            </svg>                            </div>

                        </div>                            <div class="mt-3 text-sm text-slate-500">Scheduled sessions</div>

                    </div>                        </div>

                </div>                    </div>

            </div>                </a>

        </div>            </div>

        </div>

        {{-- Secondary Stats Row --}}

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">        <!-- Revenue / Subscriptions Row simplified -->

            <div class="card">        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="card-body">            <a href="{{ route('accountant.subscriptions.index') }}" class="block">

                    <div class="flex items-center justify-between">                <div class="card">

                        <div>                    <div class="card-body p-5">

                            <p class="text-sm text-slate-600 dark:text-slate-400">Branches</p>                        <div class="text-xs text-green-700 font-semibold">Active Subscriptions</div>

                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['totalBranches'] }}</p>                        <div class="text-2xl font-bold text-slate-900 mt-2" data-animate-count>{{ $stats['activeSubscriptions'] ?? 0 }}</div>

                        </div>                        <div class="text-xs text-slate-500 mt-1">of {{ $stats['totalSubscriptions'] ?? 0 }} total</div>

                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">                    </div>

                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                </div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>            </a>

                            </svg>

                        </div>            <a href="{{ route('accountant.payments.index') }}" class="block">

                    </div>                <div class="card">

                </div>                    <div class="card-body p-5">

            </div>                        <div class="text-xs text-emerald-700 font-semibold">Revenue This Month</div>

                        <div class="text-2xl font-bold text-slate-900 mt-2" data-animate-count>{{ number_format($stats['revenueThisMonth'] ?? 0) }} Rwf</div>

            <div class="card">                        <div class="text-xs text-slate-500 mt-1">Monthly income</div>

                <div class="card-body">                    </div>

                    <div class="flex items-center justify-between">                </div>

                        <div>            </a>

                            <p class="text-sm text-slate-600 dark:text-slate-400">Groups</p>

                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['totalGroups'] }}</p>            <a href="{{ route('accountant.invoices.index') }}" class="block">

                        </div>                <div class="card">

                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">                    <div class="card-body p-5">

                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <div class="text-xs text-amber-700 font-semibold">Pending Invoices</div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>                        <div class="text-2xl font-bold text-slate-900 mt-2" data-animate-count>{{ $stats['pendingInvoices'] ?? 0 }}</div>

                            </svg>                        <div class="text-xs text-slate-500 mt-1">Awaiting payment</div>

                        </div>                    </div>

                    </div>                </div>

                </div>            </a>

            </div>

            <a href="{{ route('accountant.payments.index') }}" class="block">

            <div class="card">                <div class="card">

                <div class="card-body">                    <div class="card-body p-5">

                    <div class="flex items-center justify-between">                        <div class="text-xs text-indigo-700 font-semibold">Total Revenue</div>

                        <div>                        <div class="text-2xl font-bold text-slate-900 mt-2" data-animate-count>{{ number_format($stats['totalRevenue'] ?? 0) }} Rwf</div>

                            <p class="text-sm text-slate-600 dark:text-slate-400">Net Profit</p>                        <div class="text-xs text-slate-500 mt-1">All-time earnings</div>

                            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">                    </div>

                                {{ number_format($netProfit / 100, 0) }}                </div>

                            </p>            </a>

                        </div>        </div>

                        <div class="w-10 h-10 {{ $netProfit >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-lg flex items-center justify-center">

                            <svg class="w-5 h-5 {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">        <!-- Quick Actions Grid -->

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $netProfit >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>        <div>

                            </svg>            <h2 class="text-xl font-bold text-slate-900 mb-4">⚡ Quick Actions</h2>

                        </div>            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                    </div>                <a href="{{ route('admin.students.index') }}" class="card hover:shadow-lg transition-shadow">

                </div>                    <div class="card-body p-4 text-center">

            </div>                        <div class="text-3xl mb-2">🎓</div>

                        <div class="text-sm font-semibold text-slate-900">Students</div>

            <div class="card">                        <div class="text-xs text-slate-500 mt-1">Manage</div>

                <div class="card-body">                    </div>

                    <div class="flex items-center justify-between">                </a>

                        <div>                <a href="{{ route('admin.students.create') }}" class="card hover:shadow-lg transition-shadow">

                            <p class="text-sm text-slate-600 dark:text-slate-400">Equipment Utilization</p>                    <div class="card-body p-4 text-center">

                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $equipmentUtilization }}%</p>                        <div class="text-3xl mb-2">➕</div>

                        </div>                        <div class="text-sm font-semibold text-slate-900">New Student</div>

                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">                        <div class="text-xs text-slate-500 mt-1">Add</div>

                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                    </div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>                </a>

                            </svg>                <a href="{{ route('admin.sessions.index') }}" class="card hover:shadow-lg transition-shadow">

                        </div>                    <div class="card-body p-4 text-center">

                    </div>                        <div class="text-3xl mb-2">📅</div>

                </div>                        <div class="text-sm font-semibold text-slate-900">Sessions</div>

            </div>                        <div class="text-xs text-slate-500 mt-1">View All</div>

        </div>                    </div>

                </a>

        {{-- Charts Section --}}                <a href="{{ route('admin.sessions.create') }}" class="card hover:shadow-lg transition-shadow">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">                    <div class="card-body p-4 text-center">

            {{-- Session Trends Chart --}}                        <div class="text-3xl mb-2">📝</div>

            <div class="card">                        <div class="text-sm font-semibold text-slate-900">New Session</div>

                <div class="card-body">                        <div class="text-xs text-slate-500 mt-1">Schedule</div>

                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Session Trends (Last 8 Weeks)</h3>                    </div>

                    <div class="card-chart">                </a>

                        <canvas id="sessionTrendsChart"></canvas>                <a href="{{ route('admin.users.index') }}" class="card hover:shadow-lg transition-shadow">

                    </div>                    <div class="card-body p-4 text-center">

                </div>                        <div class="text-3xl mb-2">👥</div>

            </div>                        <div class="text-sm font-semibold text-slate-900">Users</div>

                        <div class="text-xs text-slate-500 mt-1">Manage</div>

            {{-- Coach Workload Chart --}}                    </div>

            <div class="card">                </a>

                <div class="card-body">                <a href="{{ route('admin.users.create') }}" class="card hover:shadow-lg transition-shadow">

                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Coach Workload (This Month)</h3>                    <div class="card-body p-4 text-center">

                    <div class="card-chart">                        <div class="text-3xl mb-2">👤</div>

                        <canvas id="coachWorkloadChart"></canvas>                        <div class="text-sm font-semibold text-slate-900">New User</div>

                    </div>                        <div class="text-xs text-slate-500 mt-1">Create</div>

                </div>                    </div>

            </div>                </a>

        </div>                <a href="{{ route('admin.groups.index') }}" class="card hover:shadow-lg transition-shadow">

                    <div class="card-body p-4 text-center">

        {{-- Quick Actions --}}                        <div class="text-3xl mb-2">👨‍👩‍👧‍👦</div>

        <div class="card mb-8">                        <div class="text-sm font-semibold text-slate-900">Groups</div>

            <div class="card-body">                        <div class="text-xs text-slate-500 mt-1">Manage</div>

                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h3>                    </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">                </a>

                    <a href="{{ route('admin.students.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">                <a href="{{ route('admin.branches.index') }}" class="card hover:shadow-lg transition-shadow">

                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">                    <div class="card-body p-4 text-center">

                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <div class="text-3xl mb-2">🏢</div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>                        <div class="text-sm font-semibold text-slate-900">Branches</div>

                            </svg>                        <div class="text-xs text-slate-500 mt-1">Locations</div>

                        </div>                    </div>

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Students</span>                </a>

                    </a>                <a href="{{ route('admin.equipment.index') }}" class="card hover:shadow-lg transition-shadow">

                    <div class="card-body p-4 text-center">

                    <a href="{{ route('admin.sessions.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors group">                        <div class="text-3xl mb-2">⚽</div>

                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">                        <div class="text-sm font-semibold text-slate-900">Equipment</div>

                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <div class="text-xs text-slate-500 mt-1">Assets</div>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>                    </div>

                            </svg>                </a>

                        </div>                <a href="{{ route('coach.attendance.index') }}" class="card hover:shadow-lg transition-shadow">

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Sessions</span>                    <div class="card-body p-4 text-center">

                    </a>                        <div class="text-3xl mb-2">✅</div>

                        <div class="text-sm font-semibold text-slate-900">Attendance</div>

                    <a href="{{ route('coach.attendance.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors group">                        <div class="text-xs text-slate-500 mt-1">Track</div>

                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">                    </div>

                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                </a>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>                <a href="{{ route('accountant.payments.index') }}" class="card hover:shadow-lg transition-shadow">

                            </svg>                    <div class="card-body p-4 text-center">

                        </div>                        <div class="text-3xl mb-2">💰</div>

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Attendance</span>                        <div class="text-sm font-semibold text-slate-900">Payments</div>

                    </a>                        <div class="text-xs text-slate-500 mt-1">Finance</div>

                    </div>

                    <a href="{{ route('admin.branches.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors group">                </a>

                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">                <a href="{{ route('accountant.invoices.index') }}" class="card hover:shadow-lg transition-shadow">

                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                    <div class="card-body p-4 text-center">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>                        <div class="text-3xl mb-2">📄</div>

                            </svg>                        <div class="text-sm font-semibold text-slate-900">Invoices</div>

                        </div>                        <div class="text-xs text-slate-500 mt-1">Billing</div>

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Branches</span>                    </div>

                    </a>                </a>

            </div>

                    <a href="{{ route('admin.equipment.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors group">        </div>

                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">

                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">        <!-- Today's Sessions (polished) -->

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>        <div>

                            </svg>            <h2 class="text-xl font-bold text-slate-900 mb-4">📅 Today's Sessions</h2>

                        </div>            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Equipment</span>                @if(($sessions ?? collect())->isEmpty())

                    </a>                    <div class="text-center py-12">

                        <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors group">                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">                        </svg>

                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <p class="text-slate-500 font-medium text-lg">No sessions scheduled for today</p>

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>                        <a href="{{ route('admin.sessions.create') }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">

                            </svg>                            Schedule a Session

                        </div>                        </a>

                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Users</span>                    </div>

                    </a>                @else

                </div>                    <div class="space-y-4">

            </div>                        @foreach($sessions as $s)

        </div>                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 hover:border-indigo-300 hover:shadow-md transition-all">

                                <div class="flex items-start justify-between gap-4">

        {{-- Today's Sessions --}}                                    <div class="flex-1 space-y-2">

        @if($todaysSessions->count() > 0)                                        <div class="font-bold text-slate-900 text-lg">{{ $s->date->format('M d, Y') }} • {{ $s->start_time }}–{{ $s->end_time }}</div>

        <div class="card mb-8">                                        <div class="flex flex-wrap gap-4 text-sm text-slate-600">

            <div class="card-body">                                            <span class="flex items-center gap-1"><span class="font-semibold">📍</span>{{ $s->location ?? 'N/A' }}</span>

                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ $rangeLabel }} Sessions</h3>                                            <span class="flex items-center gap-1"><span class="font-semibold">👨‍🏫</span>{{ $s->coach->name ?? 'N/A' }}</span>

                <div class="overflow-x-auto">                                            <span class="flex items-center gap-1"><span class="font-semibold">🏢</span>{{ $s->branch->name ?? 'N/A' }}</span>

                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">                                            <span class="flex items-center gap-1"><span class="font-semibold">👥</span>{{ $s->group->name ?? 'N/A' }}</span>

                        <thead>                                        </div>

                            <tr>                                        @if($s->description)

                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>                                            <p class="text-sm text-slate-600 mt-2">{{ Str::limit($s->description, 100) }}</p>

                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Time</th>                                        @endif

                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Group</th>                                    </div>

                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Coach</th>                                    <a href="{{ route('admin.sessions.edit', $s) }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">Edit</a>

                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Location</th>                                </div>

                            </tr>                            </div>

                        </thead>                        @endforeach

                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">                    </div>

                            @foreach($todaysSessions as $session)                @endif

                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">            </div>

                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($session->date)->format('M d, Y') }}</td>        </div>

                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</td>

                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $session->group_name ?? 'N/A' }}</td>        <!-- Analytics & Insights Section (polished layout) -->

                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $session->coach->name ?? 'Unassigned' }}</td>        <div>

                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $session->location ?? 'TBD' }}</td>            <h2 class="text-xl font-bold text-slate-900 mb-4">📈 Analytics & Insights</h2>

                            </tr>            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                            @endforeach                <!-- Trends Chart (Sessions over last 8 weeks) -->

                        </tbody>                <div class="lg:col-span-2 card">

                    </table>                    <div class="card-body p-6">

                </div>                        <h3 class="font-bold text-slate-900 mb-4">📊 Weekly Session Trends (Last 8 Weeks)</h3>

            </div>                        <canvas id="sessionTrendsChart" class="card-chart card-chart--large"></canvas>

        </div>                    </div>

        @endif                </div>



    </div>                <!-- Coach Workload (Top 5 Coaches) -->

</div>                <div class="card">

                    <div class="card-body p-6">

{{-- Chart.js Scripts --}}                        <h3 class="font-bold text-slate-900 mb-4">👨‍🏫 Coach Workload (This Month)</h3>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>                        <canvas id="coachWorkloadChart" class="card-chart card-chart--large"></canvas>

<script>                    </div>

document.addEventListener('DOMContentLoaded', function() {                </div>

    // Session Trends Chart            </div>

    const trendsCtx = document.getElementById('sessionTrendsChart');

    if (trendsCtx) {            <!-- Equipment & System Stats Grid -->

        new Chart(trendsCtx, {            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">

            type: 'line',                <div class="card">

            data: {                    <div class="card-body p-5">

                labels: {!! json_encode($weeklyTrends->pluck('label')) !!},                        <div class="text-xs text-slate-500 font-semibold">Equipment Utilization</div>

                datasets: [{                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $equipmentUtilization ?? 0 }}%</div>

                    label: 'Sessions',                        <div class="text-xs text-slate-400 mt-1">Assets in use</div>

                    data: {!! json_encode($weeklyTrends->pluck('sessions')) !!},                    </div>

                    borderColor: 'rgb(99, 102, 241)',                </div>

                    backgroundColor: 'rgba(99, 102, 241, 0.1)',                <div class="card">

                    tension: 0.4,                    <div class="card-body p-5">

                    fill: true                        <div class="text-xs text-slate-500 font-semibold">Net Profit (Month)</div>

                }]                        <div class="mt-2 text-2xl font-bold {{ ($netProfit ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">

            },                            {{ number_format(($netProfit ?? 0)/100, 2) }} RWF

            options: {                        </div>

                responsive: true,                        <div class="text-xs text-slate-400 mt-1">Revenue minus expenses</div>

                maintainAspectRatio: false,                    </div>

                plugins: {                </div>

                    legend: { display: false }                <div class="card">

                },                    <div class="card-body p-5">

                scales: {                        <div class="text-xs text-slate-500 font-semibold">Groups / Coaches</div>

                    y: { beginAtZero: true }                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['totalGroups'] ?? 0 }} / {{ $stats['coachUsers'] ?? 0 }}</div>

                }                        <div class="text-xs text-slate-400 mt-1">Active groupings</div>

            }                    </div>

        });                </div>

    }                <div class="card">

                    <div class="card-body p-5">

    // Coach Workload Chart                        <div class="text-xs text-slate-500 font-semibold">Sessions This Week</div>

    const workloadCtx = document.getElementById('coachWorkloadChart');                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $stats['sessionsThisWeek'] ?? 0 }}</div>

    if (workloadCtx) {                        <div class="text-xs text-slate-400 mt-1">Scheduled</div>

        new Chart(workloadCtx, {                    </div>

            type: 'bar',                </div>

            data: {            </div>

                labels: {!! json_encode($coachWorkload->pluck('coach')) !!},

                datasets: [{            <!-- System Health -->

                    label: 'Sessions',            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

                    data: {!! json_encode($coachWorkload->pluck('sessions')) !!},                <div class="card">

                    backgroundColor: 'rgba(147, 51, 234, 0.7)',                    <div class="card-body p-6">

                    borderColor: 'rgb(147, 51, 234)',                        <h3 class="font-bold text-indigo-900 mb-4">⚙️ System Health</h3>

                    borderWidth: 1                        <div class="space-y-3">

                }]                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">

            },                                <span class="text-xl">✅</span>

            options: {                                <span class="text-sm font-semibold text-emerald-900">Database: Optimal</span>

                responsive: true,                            </div>

                maintainAspectRatio: false,                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">

                plugins: {                                <span class="text-xl">✅</span>

                    legend: { display: false }                                <span class="text-sm font-semibold text-emerald-900">API Endpoints: Responsive</span>

                },                            </div>

                scales: {                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">

                    y: { beginAtZero: true }                                <span class="text-xl">✅</span>

                }                                <span class="text-sm font-semibold text-emerald-900">File Storage: Adequate</span>

            }                            </div>

        });                            <div class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-300 rounded-lg">

    }                                <span class="text-xl">⚠️</span>

});                                <span class="text-sm font-semibold text-amber-900">Backup: Last 2 hours ago</span>

</script>                            </div>

@endsection                        </div>

                    </div>
                </div>

                <div class="lg:col-span-2 card">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-slate-900 mb-4">🎯 Performance Metrics</h3>
                        <div class="space-y-4">
                            @foreach([['Student Enrollment Rate', 'bg-gradient-to-r from-blue-500 to-blue-600', '75%'], ['Session Attendance', 'bg-gradient-to-r from-emerald-500 to-emerald-600','83%'], ['Revenue Target','bg-gradient-to-r from-amber-500 to-amber-600','66%'], ['Equipment Status','bg-gradient-to-r from-green-500 to-green-600','80%']] as $m)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-700">{{ $m[0] }}</span>
                                    <div class="w-3/12 h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $m[1] }}" style="width: {{ $m[2] }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            // Session trends chart (last 8 weeks)
            const trendsCtx = document.getElementById('sessionTrendsChart');
            if (trendsCtx) {
                const trendsData = @json($weeklyTrends ?? []);
                const labels = trendsData.map(t => t.label);
                const data = trendsData.map(t => t.sessions);
                new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sessions',
                            data: data,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79,70,229,0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#4f46e5',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }

            // Coach workload bar chart
            const coachCtx = document.getElementById('coachWorkloadChart');
            if (coachCtx) {
                const workloadData = @json($coachWorkload ?? []);
                const coaches = workloadData.map(c => c.coach);
                const sessions = workloadData.map(c => c.sessions);
                new Chart(coachCtx, {
                    type: 'bar',
                    data: {
                        labels: coaches,
                        datasets: [{
                            label: 'Sessions',
                            data: sessions,
                            backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }
        })();
    </script>
@endpush
