@php($title = 'Branch Details - ' . $branch->name)
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.branches.index') }}" class="text-green-600 hover:text-green-800 font-semibold mb-4 inline-block">
                    ← Back to Branches
                </a>
                <h1 class="text-4xl font-bold text-slate-900">🏢 {{ $branch->name }}</h1>
                <p class="text-slate-600 mt-2">Branch details and management</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.branches.edit', $branch) }}" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    ✏️ Edit
                </a>
            </div>
        </div>

        <!-- Branch Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">ℹ️ Branch Information</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Branch Name</p>
                        <p class="text-lg text-slate-900 font-medium mt-1">{{ $branch->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Branch Code</p>
                        <p class="text-lg text-slate-900 font-medium mt-1 font-mono bg-slate-50 px-3 py-2 rounded">{{ $branch->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Address</p>
                        <p class="text-slate-900 font-medium mt-1">{{ $branch->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Phone</p>
                        <a href="tel:{{ $branch->phone }}" class="text-lg text-green-600 font-semibold hover:text-green-800 mt-1">{{ $branch->phone }}</a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-sm border border-green-200 p-6">
                <h2 class="text-lg font-bold text-green-900 mb-4">📊 Statistics</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-200">
                        <span class="text-slate-700 font-semibold">👥 Total Groups</span>
                        <span class="text-3xl font-bold text-green-600">{{ $branch->groups_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-200">
                        <span class="text-slate-700 font-semibold">👤 Total Users</span>
                        <span class="text-3xl font-bold text-green-600">{{ $branch->users_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-200">
                        <span class="text-slate-700 font-semibold">🎓 Total Students</span>
                        <span class="text-3xl font-bold text-green-600">{{ $branch->students_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups in Branch -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">👥 Groups in {{ $branch->name }}</h2>
                <a href="{{ route('admin.groups.create') }}" class="px-4 py-2 text-sm bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition">
                    ➕ New Group
                </a>
            </div>
            
            @if($branch->groups->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 font-medium text-lg">No groups in this branch</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($branch->groups as $group)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <a href="{{ route('admin.groups.show', $group) }}" class="font-semibold text-slate-900 hover:text-blue-600">{{ $group->name }}</a>
                            </div>
                            <div class="text-sm text-slate-600 space-y-1 mb-3">
                                <p>🎓 Students: {{ $group->students()->count() }}</p>
                                <p>📅 Sessions: {{ $group->trainingSessions()->count() }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.groups.edit', $group) }}" class="flex-1 px-2 py-1 text-xs bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition text-center">
                                    ✏️ Edit
                                </a>
                                <a href="{{ route('admin.groups.show', $group) }}" class="flex-1 px-2 py-1 text-xs bg-slate-100 text-slate-700 font-semibold rounded hover:bg-slate-200 transition text-center">
                                    👁️ View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Users in Branch -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">👤 Users in {{ $branch->name }}</h2>
            
            @if($branch->users->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 font-medium text-lg">No users assigned to this branch</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($branch->users as $user)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition">
                            <div class="font-semibold text-slate-900 mb-1">{{ $user->name }}</div>
                            <div class="text-sm text-slate-600 space-y-1">
                                <p>📧 {{ $user->email }}</p>
                                @if($user->roles->count() > 0)
                                    <p>🔑 {{ $user->roles->pluck('name')->join(', ') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Students in Branch -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">🎓 Students in {{ $branch->name }}</h2>
            
            @if($branch->students->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 font-medium text-lg">No students in this branch</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($branch->students as $student)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-emerald-300 hover:shadow-md transition">
                            <div class="font-semibold text-slate-900 mb-2">{{ $student->first_name }} {{ $student->second_name }}</div>
                            <div class="text-sm text-slate-600 space-y-1">
                                <p>📧 {{ $student->email ?? 'N/A' }}</p>
                                <p>📱 {{ $student->phone ?? 'N/A' }}</p>
                                <p>👥 Group: {{ $student->group->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
