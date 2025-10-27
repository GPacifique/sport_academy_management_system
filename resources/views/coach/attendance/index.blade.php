@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">My Training Sessions</h1>
        @if (session('status'))
            <div class="text-green-600 text-sm">{{ session('status') }}</div>
        @endif
    </div>

    <div class="space-y-3">
        @forelse ($sessions as $session)
            <div class="bg-white dark:bg-neutral-900 shadow rounded-lg p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold">{{ $session->date->format('M d, Y') }} • {{ $session->start_time }} - {{ $session->end_time }}</div>
                    <div class="text-sm text-neutral-600 dark:text-neutral-400">Location: {{ $session->location }} • Group: {{ optional($session->group)->name ?? $session->group_name }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('coach.attendance.show', $session) }}" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Mark Attendance</a>
                </div>
            </div>
        @empty
            <p class="text-neutral-600 dark:text-neutral-400">No sessions found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
