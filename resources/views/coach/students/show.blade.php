@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Student Profile</h1>
        <a href="{{ route('coach.students.index') }}" class="text-sm underline">Back to list</a>
    </div>

    <div class="bg-white dark:bg-neutral-900 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-neutral-500">Name</div>
                <div class="font-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Status</div>
                <div class="font-medium">{{ ucfirst($student->status ?? 'active') }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Branch / Group</div>
                <div class="font-medium">{{ $student->branch?->name ?? '—' }} / {{ $student->group?->name ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Parent</div>
                <div class="font-medium">{{ $student->parent?->name ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Phone</div>
                <div class="font-medium">{{ $student->phone ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Date of Birth</div>
                <div class="font-medium">{{ optional($student->dob)->format('M d, Y') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-neutral-500">Joined</div>
                <div class="font-medium">{{ optional($student->joined_at)->format('M d, Y') ?? '—' }}</div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('coach.students.attendance', $student) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">View Attendance History</a>
        </div>
    </div>
</div>
@endsection
