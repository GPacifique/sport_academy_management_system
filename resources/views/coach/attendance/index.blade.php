@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">📅 My Training Sessions</h1>
            <p class="text-slate-600 mt-1">Record and manage student attendance for your sessions</p>
        </div>
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-sm font-semibold">✓ {{ session('status') }}</div>
        @endif
    </div>

    @if($sessions->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-slate-500 font-medium text-lg mb-2">No sessions scheduled</p>
            <p class="text-slate-600 text-sm">Create a training session to start recording attendance</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($sessions as $session)
                <div class="bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-neutral-800 dark:to-neutral-700 px-4 py-3 border-b border-slate-200 dark:border-neutral-700">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white">📅 {{ $session->date->format('M d, Y') }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">⏰ {{ $session->start_time }} - {{ $session->end_time }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <div class="space-y-2 mb-4">
                            <p class="text-sm">
                                <span class="text-slate-600 dark:text-slate-400">📍 Location:</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $session->location }}</span>
                            </p>
                            <p class="text-sm">
                                <span class="text-slate-600 dark:text-slate-400">👥 Group:</span>
                                <span class="font-semibold text-slate-900 dark:text-white">{{ optional($session->group)->name ?? $session->group_name }}</span>
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">Ready to Record</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('coach.attendance.show', $session) }}" class="flex-1 px-3 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transition text-center text-sm">
                                ✏️ Mark Attendance
                            </a>
                            <a href="{{ route('coach.sessions.index') }}" title="View Session Details" class="px-3 py-2 border border-slate-300 dark:border-neutral-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-neutral-800 transition text-sm">
                                👁️
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
            <div class="mt-6">
                {{ $sessions->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
