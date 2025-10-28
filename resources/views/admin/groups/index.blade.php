@php($title = 'Manage Groups')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">👥 Manage Groups</h1>
                <p class="text-slate-600 mt-2">Organize students into training groups</p>
            </div>
            <a href="{{ route('admin.groups.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                ➕ New Group
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search Groups</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by group name..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Filter by Branch</label>
                    <select name="branch_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(request('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        🔍 Filter
                    </button>
                    @if(request('search') || request('branch_id'))
                        <a href="{{ route('admin.groups.index') }}" class="px-6 py-2 bg-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-400 transition">
                            ✕ Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Groups Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if($groups->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <p class="text-slate-500 font-medium text-lg mb-4">No groups found</p>
                    <a href="{{ route('admin.groups.create') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Create Your First Group
                    </a>
                </div>
            @else
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Group Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Students</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Sessions</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($groups as $group)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $group->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                        {{ $group->branch->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-900 font-semibold">{{ $group->students_count ?? $group->students()->count() }} 🎓</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-900 font-semibold">{{ $group->training_sessions_count ?? $group->trainingSessions()->count() }} 📅</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.groups.show', $group) }}" class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-medium rounded hover:bg-slate-200 transition" title="View Details">
                                            👁️
                                        </a>
                                        <a href="{{ route('admin.groups.edit', $group) }}" class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded hover:bg-blue-200 transition" title="Edit">
                                            ✏️
                                        </a>
                                        <form method="POST" action="{{ route('admin.groups.destroy', $group) }}" class="inline" onsubmit="return confirm('Delete this group?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded hover:bg-red-200 transition" title="Delete">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $groups->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
