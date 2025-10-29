@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="flex items-center justify-between mb-6">
        <h1 class="page-title">User Role Management</h1>
    </div>

    <div class="mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="label mb-1">Search</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Name or email" class="input" />
            </div>
            <div>
                <label class="label mb-1">Role</label>
                <select name="role" class="select">
                    <option value="">All</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(($roleFilter ?? '') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <x-button :href="route('admin.users.create')" variant="primary">New User</x-button>
                <x-button type="submit" variant="outline">Filter</x-button>
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Group</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr @class(['bg-red-50 dark:bg-red-900/30' => $user->deleted_at])>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->branch?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $user->group?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-3">
                                @csrf
                                @method('PATCH')
                                <select name="roles[]" multiple class="select w-64" @disabled($user->deleted_at)>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @selected($user->roles->pluck('name')->contains($role))>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                                <x-button type="submit" variant="primary" :disabled="$user->deleted_at">Save</x-button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->deleted_at)
                                <span class="badge badge-red">Deactivated</span>
                            @else
                                <span class="badge badge-green">Active</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" @class(['text-indigo-700 hover:underline px-2','opacity-50 pointer-events-none' => $user->deleted_at])>Edit</a>
                            <form method="POST" action="{{ route('admin.users.sendReset', $user) }}" class="inline">
                                @csrf
                                <button class="text-indigo-700 hover:underline px-2" type="submit" @disabled($user->deleted_at)>Send reset link</button>
                            </form>
                            @if(!$user->deleted_at)
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Deactivate this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-700 hover:underline px-2" type="submit">Deactivate</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="inline">
                                    @csrf
                                    <button class="text-green-700 hover:underline px-2" type="submit">Restore</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
