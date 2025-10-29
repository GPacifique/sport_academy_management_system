@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">📅 My Sessions</h1>
                <p class="text-slate-600 mt-2">Training sessions you have scheduled</p>
            </div>
            <a href="{{ route('coach.sessions.create') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                ➕ Schedule Session
            </a>
        </div>

        <!-- Sessions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if($sessions->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-slate-500 font-medium text-lg mb-4">No sessions scheduled yet</p>
                    <a href="{{ route('coach.sessions.create') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Schedule Your First Session
                    </a>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Group</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($sessions as $session)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-900">
                                        {{ $session->date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-700 font-medium">
                                        {{ $session->start_time }} – {{ $session->end_time }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                        {{ $session->group->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-700">📍 {{ $session->location }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-700">{{ $session->branch->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('coach.attendance.show', $session) }}" class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-medium rounded hover:bg-slate-200 transition" title="Mark Attendance">
                                            👁️ Attendance
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>

        <!-- Statistics Section -->
        @if($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm border border-blue-200 p-6">
                    <h3 class="text-sm font-semibold text-blue-900 uppercase mb-2">Total Sessions</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $sessions->total() }}</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl shadow-sm border border-emerald-200 p-6">
                    <h3 class="text-sm font-semibold text-emerald-900 uppercase mb-2">Upcoming Sessions</h3>
                    <p class="text-3xl font-bold text-emerald-600">
                        {{ $sessions->getCollection()->where('date', '>=', now()->toDateString())->count() }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-200 p-6">
                    <h3 class="text-sm font-semibold text-amber-900 uppercase mb-2">Past Sessions</h3>
                    <p class="text-3xl font-bold text-amber-600">
                        {{ $sessions->getCollection()->where('date', '<', now()->toDateString())->count() }}
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection
