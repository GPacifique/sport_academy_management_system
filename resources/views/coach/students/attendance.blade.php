@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Attendance History</h1>
            <p class="text-neutral-600 dark:text-neutral-400">{{ $student->first_name }} {{ $student->second_name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('coach.students.show', $student) }}" class="text-sm underline">Profile</a>
            <a href="{{ route('coach.students.index') }}" class="text-sm underline">Back to list</a>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-900 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
            <thead class="bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Location</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse ($records as $rec)
                    <tr>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($rec->session_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3">{{ $rec->session_start }}–{{ $rec->session_end }}</td>
                        <td class="px-4 py-3">{{ $rec->session_location }}</td>
                        <td class="px-4 py-3">{{ ucfirst($rec->status) }}</td>
                        <td class="px-4 py-3">{{ $rec->notes ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-neutral-600">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $records->links() }}
    </div>
</div>
@endsection
