@php($title = 'Branch Details - ' . $branch->name)
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl shadow-lg p-8 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <a href="{{ route('admin.branches.index') }}" class="text-green-100 hover:text-white font-semibold mb-4 inline-flex items-center gap-2">
                        ← Back to Branches
                    </a>
                    <h1 class="text-4xl font-bold mb-2">🏢 {{ $branch->name }}</h1>
                    <p class="text-green-100 text-lg">Code: <span class="font-mono font-semibold">{{ $branch->code }}</span></p>
                </div>
                <a href="{{ route('admin.branches.edit', $branch) }}" class="px-6 py-3 bg-white text-green-600 font-bold rounded-lg hover:bg-green-50 transition shadow-md">
                    ✏️ Edit Branch
                </a>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Total Groups</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $branch->groups_count ?? 0 }}</p>
                    </div>
                    <div class="text-4xl opacity-20">👥</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Total Users</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $branch->users_count ?? 0 }}</p>
                    </div>
                    <div class="text-4xl opacity-20">🔐</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Total Students</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ $branch->students_count ?? 0 }}</p>
                    </div>
                    <div class="text-4xl opacity-20">🎓</div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg shadow-sm border border-amber-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-amber-900 font-semibold uppercase">Location Status</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">✓ Active</p>
                    </div>
                    <div class="text-4xl opacity-20">📍</div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Branch Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">ℹ️ Branch Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Branch Name</p>
                                <p class="text-lg text-slate-900 font-semibold">{{ $branch->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Branch Code</p>
                                <p class="text-lg text-slate-900 font-semibold font-mono bg-slate-100 px-3 py-2 rounded">{{ $branch->code }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Address</p>
                                <p class="text-slate-900">{{ $branch->address }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Phone</p>
                                <a href="tel:{{ $branch->phone }}" class="text-lg text-green-600 font-semibold hover:text-green-800">{{ $branch->phone }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Groups in Branch -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">👥 Groups ({{ $branch->groups->count() }})</h2>
                        <a href="{{ route('admin.groups.create') }}" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition">
                            ➕ Add
                        </a>
                    </div>
                    <div class="p-6">
                        @if($branch->groups->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
                                </svg>
                                <p class="text-slate-500 font-medium">No groups in this branch</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($branch->groups as $group)
                                    <div class="border border-slate-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition">
                                        <div class="flex items-start justify-between mb-3">
                                            <a href="{{ route('admin.groups.show', $group) }}" class="font-semibold text-slate-900 hover:text-blue-600">{{ $group->name }}</a>
                                        </div>
                                        <div class="text-sm text-slate-600 space-y-1 mb-3">
                                            <p>🎓 Students: <span class="font-semibold">{{ $group->students()->count() }}</span></p>
                                            <p>📅 Sessions: <span class="font-semibold">{{ $group->trainingSessions()->count() }}</span></p>
                                        </div>
                                        <div class="flex gap-2 pt-3 border-t border-slate-100">
                                            <a href="{{ route('admin.groups.edit', $group) }}" class="flex-1 px-2 py-2 text-xs bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition text-center">
                                                ✏️ Edit
                                            </a>
                                            <a href="{{ route('admin.groups.show', $group) }}" class="flex-1 px-2 py-2 text-xs bg-slate-100 text-slate-700 font-semibold rounded hover:bg-slate-200 transition text-center">
                                                👁️ View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Users in Branch -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">👤 Users ({{ $branch->users->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($branch->users->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-slate-500 font-medium">No users assigned to this branch</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($branch->users as $user)
                                    <div class="border border-slate-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition">
                                        <div class="font-semibold text-slate-900 mb-2">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-600 space-y-1">
                                            <p>📧 {{ $user->email }}</p>
                                            @if($user->roles->count() > 0)
                                                <p class="mt-2">
                                                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">
                                                        {{ $user->roles->first()->name }}
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Students in Branch -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">🎓 Students ({{ $branch->students->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($branch->students->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-slate-500 font-medium">No students in this branch</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($branch->students as $student)
                                    <div class="border border-slate-200 rounded-lg p-4 hover:border-emerald-300 hover:shadow-md transition">
                                        <div class="font-semibold text-slate-900 mb-2">
                                            <a href="{{ route('admin.students.show', $student) }}" class="hover:text-emerald-600">
                                                {{ $student->first_name }} {{ $student->second_name }}
                                            </a>
                                        </div>
                                        @if($student->jersey_number || $student->jersey_name)
                                            <div class="flex items-center gap-1 mb-2">
                                                @if($student->jersey_number)
                                                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded">J#{{ $student->jersey_number }}</span>
                                                @endif
                                                @if($student->jersey_name)
                                                    <span class="inline-block px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">{{ $student->jersey_name }}</span>
                                                @endif
                                            </div>
                                        @endif
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
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Branch Summary -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-sm border border-green-200 p-6">
                    <h3 class="text-lg font-bold text-green-900 mb-4">📊 Summary</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-white rounded-lg border border-green-100">
                            <p class="text-xs text-slate-600 font-semibold">BRANCH NAME</p>
                            <p class="text-slate-900 font-semibold mt-1">{{ $branch->name }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-green-100">
                            <p class="text-xs text-slate-600 font-semibold">CODE</p>
                            <p class="text-slate-900 font-semibold font-mono mt-1">{{ $branch->code }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-green-100">
                            <p class="text-xs text-slate-600 font-semibold">STATUS</p>
                            <p class="text-slate-900 font-semibold mt-1">✓ Active</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.branches.edit', $branch) }}" class="block px-4 py-2 bg-green-100 text-green-700 text-center font-semibold rounded-lg hover:bg-green-200 transition">
                            ✏️ Edit Branch
                        </a>
                        <a href="{{ route('admin.groups.create') }}" class="block px-4 py-2 bg-blue-100 text-blue-700 text-center font-semibold rounded-lg hover:bg-blue-200 transition">
                            ➕ Add Group
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="block px-4 py-2 bg-slate-100 text-slate-700 text-center font-semibold rounded-lg hover:bg-slate-200 transition">
                            🎓 View All Students
                        </a>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">ℹ️ Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">ID</dt>
                            <dd class="text-slate-900 font-mono">{{ $branch->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">Created</dt>
                            <dd class="text-slate-900">{{ $branch->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">Updated</dt>
                            <dd class="text-slate-900">{{ $branch->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
