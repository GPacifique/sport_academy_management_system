@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Edit Session</h1>
        <a href="{{ route('admin.sessions.index') }}" class="text-sm underline">Back to list</a>
    </div>

    <form method="POST" action="{{ route('admin.sessions.update', $session) }}" class="bg-white dark:bg-neutral-900 shadow rounded-lg p-4 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Coach</label>
            <select name="coach_user_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->id }}" @selected(old('coach_user_id', $session->coach_user_id) == $coach->id)>{{ $coach->name }} ({{ $coach->email }})</option>
                @endforeach
            </select>
            @error('coach_user_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Branch</label>
                <select id="branch_id" name="branch_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id', $session->branch_id) == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Group</label>
                <select id="group_id" name="group_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" data-branch="{{ $group->branch_id }}" @selected(old('group_id', $session->group_id) == $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date', $session->date->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
            @error('date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time', $session->start_time) }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('start_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time', $session->end_time) }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('end_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location', $session->location) }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" placeholder="e.g., Main Field" required>
            @error('location')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-between gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update Session</button>
            <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" onsubmit="return confirm('Delete this session? This will remove related attendance.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </form>
        </div>
    </form>

    <script>
        // filter group list by selected branch
        const branchSel = document.getElementById('branch_id');
        const groupSel = document.getElementById('group_id');
        const allOptions = Array.from(groupSel.options);
        function filterGroups() {
            const b = branchSel.value;
            groupSel.innerHTML = '';
            allOptions.forEach(opt => {
                if (opt.dataset.branch === b) {
                    groupSel.appendChild(opt.cloneNode(true));
                }
            });
        }
        branchSel.addEventListener('change', filterGroups);
        // initialize filtered options keeping current selection
        filterGroups();
        const currentGroup = '{{ old('group_id', $session->group_id) }}';
        Array.from(groupSel.options).forEach(opt => { if (opt.value == currentGroup) opt.selected = true; });
    </script>
</div>
@endsection
