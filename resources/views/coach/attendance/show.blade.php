@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Mark Attendance</h1>
        <p class="text-neutral-600 dark:text-neutral-400">Session: {{ $session->date->format('M d, Y') }} • {{ $session->start_time }} - {{ $session->end_time }} • {{ $session->location }}</p>
        <p class="text-neutral-600 dark:text-neutral-400">Group: {{ optional($session->group)->name ?? $session->group_name }}</p>
    </div>

    <form method="POST" action="{{ route('coach.attendance.store', $session) }}" class="bg-white dark:bg-neutral-900 rounded-lg shadow p-4">
        @csrf
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">Student</th>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                    @foreach ($students as $student)
                        <tr>
                            <td class="px-3 py-2">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td class="px-3 py-2">
                                <select name="attendance[{{ $student->id }}][status]" class="border rounded px-2 py-1 dark:bg-neutral-900 dark:border-neutral-700">
                                    <option value="present" @selected(($existing[$student->id] ?? '') === 'present')>Present</option>
                                    <option value="absent" @selected(($existing[$student->id] ?? '') === 'absent')>Absent</option>
                                </select>
                                <input type="hidden" name="attendance[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" name="attendance[{{ $student->id }}][notes]" value="" placeholder="Optional" class="border rounded px-2 py-1 w-full dark:bg-neutral-900 dark:border-neutral-700" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <a href="{{ route('coach.attendance.index') }}" class="px-3 py-2 border rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Attendance</button>
        </div>
    </form>
</div>
@endsection
