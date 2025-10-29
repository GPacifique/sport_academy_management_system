@php($title = 'Manage Branches')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">🏢 Manage Branches</h1>
                <p class="text-slate-600 mt-2">Manage academy branches and locations</p>
            </div>
            <a href="{{ route('admin.branches.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                ➕ New Branch
            </a>
        </div>

        <!-- Search & Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search Branches</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                        🔍 Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.branches.index') }}" class="px-6 py-2 bg-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-400 transition">
                            ✕ Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Branches Grid -->
        @if($branches->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 text-center py-16">
                <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.5m0 0H9m0 0H3.5m0 0H1"/>
                </svg>
                <p class="text-slate-500 font-medium text-lg mb-4">No branches found</p>
                <a href="{{ route('admin.branches.create') }}" class="inline-block px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    Create Your First Branch
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($branches as $branch)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-green-300 transition p-6">
                        <!-- Branch Header -->
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $branch->name }}</h3>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                {{ $branch->code }}
                            </span>
                        </div>

                        <!-- Branch Details -->
                        <div class="space-y-3 mb-6 pb-6 border-b border-slate-200">
                            <div>
                                <p class="text-xs text-slate-600 uppercase font-semibold">Address</p>
                                <p class="text-sm text-slate-900 font-medium">{{ $branch->address }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 uppercase font-semibold">Phone</p>
                                <p class="text-sm text-slate-900 font-medium">{{ $branch->phone }}</p>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-2xl font-bold text-blue-600">{{ $branch->groups_count ?? 0 }}</p>
                                <p class="text-xs text-blue-800 font-semibold mt-1">👥 Groups</p>
                            </div>
                            <div class="text-center p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                                <p class="text-2xl font-bold text-emerald-600">{{ $branch->users_count ?? 0 }}</p>
                                <p class="text-xs text-emerald-800 font-semibold mt-1">👤 Users</p>
                            </div>
                            <div class="text-center p-3 bg-amber-50 rounded-lg border border-amber-200">
                                <p class="text-2xl font-bold text-amber-600">{{ $branch->students_count ?? 0 }}</p>
                                <p class="text-xs text-amber-800 font-semibold mt-1">🎓 Students</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.branches.show', $branch) }}" class="flex-1 px-3 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded hover:bg-slate-200 transition text-center">
                                👁️ View
                            </a>
                            <a href="{{ route('admin.branches.edit', $branch) }}" class="flex-1 px-3 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded hover:bg-green-200 transition text-center">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" class="flex-1" onsubmit="return confirm('Delete this branch? (Only if no associated data)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded hover:bg-red-200 transition">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
@endsection
