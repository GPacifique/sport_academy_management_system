@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Create User</h1>
        <a href="{{ route('admin.users.index') }}" class="text-sm underline">Back to list</a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white dark:bg-neutral-900 shadow rounded-lg p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" required>
                @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Branch</label>
                <select id="branch_id" name="branch_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700">
                    <option value="">—</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Group</label>
                <select id="group_id" name="group_id" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700">
                    <option value="">—</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" data-branch="{{ $group->branch_id }}" @selected(old('group_id') == $group->id)>{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Roles</label>
            <select name="roles[]" multiple class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(collect(old('roles', ['user']))->contains($role))>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('roles')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Set Password (optional)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 dark:bg-neutral-900 dark:border-neutral-700" placeholder="Leave empty to auto-generate">
                @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2 mt-6">
                <input type="checkbox" id="send_reset" name="send_reset" value="1" @checked(old('send_reset'))>
                <label for="send_reset">Send password reset link</label>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 border rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Create</button>
        </div>
    </form>

    <script>
        const branchSel = document.getElementById('branch_id');
        const groupSel = document.getElementById('group_id');
        const allOptions = Array.from(groupSel.options);
        function filterGroups() {
            const b = branchSel.value;
            groupSel.innerHTML = '';
            allOptions.forEach(opt => {
                if (!opt.value || opt.dataset.branch === b) groupSel.appendChild(opt.cloneNode(true));
            });
            // Ensure a valid selection after filtering
            groupSel.selectedIndex = 0;
        }
        branchSel.addEventListener('change', filterGroups);
        filterGroups();
    </script>
</div>
@endsection
