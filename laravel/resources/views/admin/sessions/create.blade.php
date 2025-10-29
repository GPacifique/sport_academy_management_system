@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Create Session</h1>
        <a href="{{ route('admin.sessions.index') }}" class="text-sm underline">Back to list</a>
    </div>

    <form method="POST" action="{{ route('admin.sessions.store') }}" class="bg-white dark:bg-neutral-900 shadow rounded-lg p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Coach</label>
            <select name="coach_user_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->id }}">{{ $coach->name }} ({{ $coach->email }})</option>
                @endforeach
            </select>
            @error('coach_user_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Branch</label>
                <select id="branch_id" name="branch_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                    <option value="">-- Select a branch --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Group</label>
                <select id="group_id" name="group_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                    <option value="">-- Select a group --</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" data-branch="{{ $group->branch_id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
            @error('date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium mb-1">Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('start_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('end_time')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" placeholder="e.g., Main Field" required>
            @error('location')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.sessions.index') }}" class="px-3 py-2 border rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Create Session</button>
        </div>
    </form>

    <script>
        // Filter group list by selected branch
        const branchSel = document.getElementById('branch_id');
        const groupSel = document.getElementById('group_id');
        const allOptions = Array.from(groupSel.options);

        function filterGroups() {
            const selectedBranch = branchSel.value;
            
            // Preserve the first non-data option (placeholder if exists)
            const firstOption = allOptions[0];
            
            // Clear and reset with initial placeholder if exists
            groupSel.innerHTML = '';
            
            // Add placeholder/first option back
            if (firstOption && !firstOption.dataset.branch) {
                groupSel.appendChild(firstOption.cloneNode(true));
            }
            
            // Add filtered options based on selected branch
            if (selectedBranch) {
                allOptions.forEach(opt => {
                    if (opt.dataset.branch && opt.dataset.branch === selectedBranch) {
                        groupSel.appendChild(opt.cloneNode(true));
                    }
                });
            } else {
                // If no branch selected, add all group options
                allOptions.forEach(opt => {
                    if (opt.dataset.branch) {
                        groupSel.appendChild(opt.cloneNode(true));
                    }
                });
            }
        }

        // Add initial placeholder option if not exists
        if (!allOptions[0] || allOptions[0].dataset.branch) {
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Select a group --';
            groupSel.insertBefore(placeholder, groupSel.firstChild);
        }

        branchSel.addEventListener('change', filterGroups);
        filterGroups();
    </script>
</div>
@endsection
