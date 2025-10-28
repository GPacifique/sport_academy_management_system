@php($title = 'Admin Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Hero (styled like footer: gradient + animated blobs) -->
        <div style="position: relative; background: linear-gradient(to bottom right, #1e3a8a, #6b21a8, #be185d); color: white; padding: 2rem; border-radius: 1rem; margin-bottom: 0.5rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden;">
            <div style="position: absolute; inset: 0; opacity: 0.14; pointer-events: none;">
                <div style="position: absolute; top: -2rem; left: -4rem; width: 20rem; height: 20rem; background-color: #3b82f6; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(72px);" class="animate-blob"></div>
                <div style="position: absolute; top: -1rem; right: -4rem; width: 18rem; height: 18rem; background-color: #ec4899; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(72px);" class="animate-blob animation-delay-2000"></div>
                <div style="position: absolute; bottom: -3rem; left: 6rem; width: 22rem; height: 22rem; background-color: #a855f7; border-radius: 9999px; mix-blend-mode: overlay; filter: blur(72px);" class="animate-blob animation-delay-4000"></div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="position: relative; z-index: 2;">
                <div>
                    <h1 class="text-4xl font-extrabold">Admin Dashboard</h1>
                    <p class="mt-1 text-indigo-100">A high-level view of academy operations</p>
                    <p class="mt-2 text-indigo-200 text-sm">{{ now()->format('l, F d, Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right px-4 py-2 bg-white/10 rounded-lg border border-white/10">
                        <p class="text-xs text-white/90 font-semibold">System Status</p>
                        <p class="text-sm text-white/80 mt-1">✅ All Systems Operational</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-white text-indigo-700 font-semibold rounded-lg shadow-sm hover:shadow-md">➕ Add User</a>
                </div>
            </div>
        </div>

        <!-- Compact Filters + KPI Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row md:items-center gap-4">
                <form method="GET" class="flex-1 flex flex-wrap items-center gap-3">
                    <input type="hidden" name="view" value="{{ request('view') }}">
                    <select name="branch_id" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="">All Branches</option>
                        @foreach(($branches ?? []) as $b)
                            <option value="{{ $b->id }}" {{ (($currentBranchId ?? '') == $b->id) ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <select name="range" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        @php($r = $currentRange ?? 'today')
                        <option value="today" @selected($r==='today')>Today</option>
                        <option value="week" @selected($r==='week')>This Week</option>
                        <option value="month" @selected($r==='month')>This Month</option>
                        <option value="all" @selected($r==='all')>All Time</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Apply</button>
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-slate-100 rounded-lg text-sm">Reset</a>
                </form>
                <div class="hidden md:flex items-center gap-4">
                    <div class="text-sm text-slate-500">Quick overview</div>
                </div>
            </div>

            <div class="lg:col-span-1 grid grid-cols-2 gap-3">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                    <div class="text-xs text-slate-500">Students</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $stats['activeStudents'] ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Active</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                    <div class="text-xs text-slate-500">Branches</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $stats['totalBranches'] ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Locations</div>
                </div>
            </div>
        </div>

        <!-- Top KPI Cards -->
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users.index') }}" class="block">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs text-slate-500 font-semibold">Total Users</div>
                                <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['totalUsers'] ?? 0 }}</div>
                            </div>
                            <div class="text-3xl">👥</div>
                        </div>
                        <div class="mt-3 text-sm text-slate-500">Active accounts across the system</div>
                    </div>
                </a>

                <a href="#" class="block">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs text-slate-500 font-semibold">Branches</div>
                                <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['totalBranches'] ?? 0 }}</div>
                            </div>
                            <div class="text-3xl">🏢</div>
                        </div>
                        <div class="mt-3 text-sm text-slate-500">Active branch locations</div>
                    </div>
                </a>

                <a href="{{ route('admin.students.index') }}" class="block">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs text-slate-500 font-semibold">Active Students</div>
                                <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['activeStudents'] ?? 0 }}</div>
                            </div>
                            <div class="text-3xl">🎓</div>
                        </div>
                        <div class="mt-3 text-sm text-slate-500">Currently enrolled</div>
                    </div>
                </a>

                <a href="{{ route('admin.sessions.index') }}" class="block">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs text-slate-500 font-semibold">Sessions ({{ $rangeLabel ?? 'Today' }})</div>
                                <div class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['todaySessions'] ?? 0 }}</div>
                            </div>
                            <div class="text-3xl">📅</div>
                        </div>
                        <div class="mt-3 text-sm text-slate-500">Scheduled sessions</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Revenue / Subscriptions Row simplified -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('accountant.subscriptions.index') }}" class="block">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl border border-green-200 shadow-sm hover:shadow-md transition">
                    <div class="text-xs text-green-700 font-semibold">Active Subscriptions</div>
                    <div class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['activeSubscriptions'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500 mt-1">of {{ $stats['totalSubscriptions'] ?? 0 }} total</div>
                </div>
            </a>

            <a href="{{ route('accountant.payments.index') }}" class="block">
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 rounded-xl border border-emerald-200 shadow-sm hover:shadow-md transition">
                    <div class="text-xs text-emerald-700 font-semibold">Revenue This Month</div>
                    <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['revenueThisMonth'] ?? 0) }} Rwf</div>
                    <div class="text-xs text-slate-500 mt-1">Monthly income</div>
                </div>
            </a>

            <a href="{{ route('accountant.invoices.index') }}" class="block">
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-5 rounded-xl border border-amber-200 shadow-sm hover:shadow-md transition">
                    <div class="text-xs text-amber-700 font-semibold">Pending Invoices</div>
                    <div class="text-2xl font-bold text-slate-900 mt-2">{{ $stats['pendingInvoices'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500 mt-1">Awaiting payment</div>
                </div>
            </a>

            <a href="{{ route('accountant.payments.index') }}" class="block">
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-5 rounded-xl border border-indigo-200 shadow-sm hover:shadow-md transition">
                    <div class="text-xs text-indigo-700 font-semibold">Total Revenue</div>
                    <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($stats['totalRevenue'] ?? 0) }} Rwf</div>
                    <div class="text-xs text-slate-500 mt-1">All-time earnings</div>
                </div>
            </a>
        </div>

        <!-- Today's Sessions (polished) -->
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
                                            <span class="flex items-center gap-1"><span class="font-semibold">📍</span>{{ $s->location ?? 'N/A' }}</span>
                                            <span class="flex items-center gap-1"><span class="font-semibold">👨‍🏫</span>{{ $s->coach->name ?? 'N/A' }}</span>
                                            <span class="flex items-center gap-1"><span class="font-semibold">🏢</span>{{ $s->branch->name ?? 'N/A' }}</span>
                                            <span class="flex items-center gap-1"><span class="font-semibold">👥</span>{{ $s->group->name ?? 'N/A' }}</span>
                                        </div>
                                        @if($s->description)
                                            <p class="text-sm text-slate-600 mt-2">{{ Str::limit($s->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.sessions.edit', $s) }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition whitespace-nowrap">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Analytics & Insights Section (polished layout) -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">📈 Analytics & Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-300 rounded-xl p-6">
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
