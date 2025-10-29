@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">📋 Attendance History</h1>
            <p class="text-slate-600 mt-1">
                <span class="font-semibold">{{ $student->first_name }} {{ $student->second_name }}</span>
                @if($student->jersey_number || $student->jersey_name)
                    <span class="inline-block ml-3">
                        @if($student->jersey_number)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">Jersey #{{ $student->jersey_number }}</span>
                        @endif
                        @if($student->jersey_name)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded ml-1">{{ $student->jersey_name }}</span>
                        @endif
                    </span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('coach.students.show', $student) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition">👁️ Profile</a>
            <a href="{{ route('coach.students.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-semibold transition">← Back</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-neutral-800 dark:to-neutral-700 rounded-lg shadow-sm border border-blue-200 dark:border-neutral-600 p-4">
            <p class="text-xs font-semibold text-blue-900 dark:text-blue-200 uppercase">Total Records</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $records->total() }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-neutral-800 dark:to-neutral-700 rounded-lg shadow-sm border border-emerald-200 dark:border-neutral-600 p-4">
            <p class="text-xs font-semibold text-emerald-900 dark:text-emerald-200 uppercase">Present Sessions</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $records->count() > 0 ? $records->getCollection()->where('status', 'present')->count() : 0 }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-neutral-800 dark:to-neutral-700 rounded-lg shadow-sm border border-amber-200 dark:border-neutral-600 p-4">
            <p class="text-xs font-semibold text-amber-900 dark:text-amber-200 uppercase">Absent Sessions</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $records->count() > 0 ? $records->getCollection()->where('status', 'absent')->count() : 0 }}</p>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white dark:bg-neutral-900 rounded-lg shadow-sm border border-slate-200 dark:border-neutral-700 overflow-hidden">
        @if($records->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-500 font-medium text-lg">No attendance records found</p>
                <p class="text-slate-600 text-sm mt-1">Attendance will appear here once sessions are recorded</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-neutral-800 dark:to-neutral-700 border-b border-slate-200 dark:border-neutral-600">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-neutral-700">
                    @foreach ($records as $rec)
                        <tr class="hover:bg-slate-50 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ \Illuminate\Support\Carbon::parse($rec->session_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $rec->session_start }}–{{ $rec->session_end }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">📍 {{ $rec->session_location }}</td>
                            <td class="px-4 py-3">
                                @if($rec->status === 'present')
                                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">✓ Present</span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">✗ Absent</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                @if($rec->notes)
                                    <span class="inline-block px-2 py-1 bg-slate-100 dark:bg-neutral-800 text-slate-700 dark:text-slate-400 text-xs rounded">{{ $rec->notes }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if($records->hasPages())
                <div class="border-t border-slate-200 dark:border-neutral-700 px-4 py-4 bg-slate-50 dark:bg-neutral-800">
                    {{ $records->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
