@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">📅 Schedule a Session</h1>
            <p class="text-slate-600 mt-1">Create a new training session for your group</p>
        </div>
        <a href="{{ route('coach.attendance.index') }}" class="text-sm underline text-indigo-600 hover:text-indigo-800">← Back</a>
    </div>

    @if($groups->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-yellow-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-yellow-800 font-semibold">No groups available</p>
            <p class="text-yellow-700 text-sm mt-2">Please contact the administrator to assign you to a branch and group.</p>
            <a href="{{ route('coach.attendance.index') }}" class="inline-block mt-4 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                Return to Attendance
            </a>
        </div>
    @else
        <form method="POST" action="{{ route('coach.sessions.store') }}" class="bg-white dark:bg-neutral-900 shadow rounded-lg p-6 space-y-5">
            @csrf
            
            @if($branch)
                <div class="bg-blue-50 dark:bg-neutral-800 border border-blue-200 dark:border-neutral-700 rounded-lg p-4">
                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase">Assigned Branch</p>
                    <p class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ $branch->name }}</p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-2">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                @error('date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('start_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('end_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Location <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Main Field, Stadium" required>
                @error('location')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Select Group <span class="text-red-500">*</span></label>
                <select name="group_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">-- Choose a group --</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected(old('group_id', $defaultGroupId) == $group->id)>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
                @error('group_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-slate-500 mt-2">{{ $groups->count() }} group(s) available</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t dark:border-neutral-700">
                <a href="{{ route('coach.attendance.index') }}" class="px-4 py-2 border rounded hover:bg-slate-50 dark:hover:bg-neutral-800">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition font-semibold">
                    ✓ Schedule Session
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
