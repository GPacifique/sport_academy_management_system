@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">My Students</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search name or phone…" class="border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" />
            <button class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded">Search</button>
        </form>
    </div>

    <div class="bg-white dark:bg-neutral-900 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
            <thead class="bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Group</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Parent</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse ($students as $student)
                    <tr>
                        <td class="px-4 py-3">{{ $student->first_name }} {{ $student->second_name }}</td>
                        <td class="px-4 py-3">{{ $student->group?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $student->parent?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $student->phone ?? '—' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($student->status ?? 'active') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-indigo-700 hover:underline px-2" href="{{ route('coach.students.show', $student) }}">Profile</a>
                            <a class="text-indigo-700 hover:underline px-2" href="{{ route('coach.students.attendance', $student) }}">Attendance</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-neutral-600">No students found in your group.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
