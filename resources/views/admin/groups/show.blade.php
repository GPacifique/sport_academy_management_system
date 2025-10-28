@php($title = 'Group Details - ' . $group->name)
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold mb-4 inline-block">
                    ← Back to Groups
                </a>
                <h1 class="text-4xl font-bold text-slate-900">👥 {{ $group->name }}</h1>
                <p class="text-slate-600 mt-2">Group details and statistics</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.groups.edit', $group) }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    ✏️ Edit
                </a>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Branch Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">🏢 Branch Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold">Branch Name</p>
                        <p class="text-slate-900 font-medium">{{ $group->branch->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold">Branch Code</p>
                        <p class="text-slate-900 font-medium">{{ $group->branch->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold">Address</p>
                        <p class="text-slate-900 font-medium">{{ $group->branch->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-semibold">Phone</p>
                        <p class="text-slate-900 font-medium">{{ $group->branch->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl shadow-sm border border-indigo-200 p-6">
                <h2 class="text-lg font-bold text-indigo-900 mb-4">📊 Group Statistics</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-200">
                        <span class="text-slate-700 font-semibold">👤 Total Students</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $group->students()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-200">
                        <span class="text-slate-700 font-semibold">📅 Training Sessions</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $group->trainingSessions()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-indigo-200">
                        <span class="text-slate-700 font-semibold">👥 Total Users</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $group->users()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students in Group -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">🎓 Students in {{ $group->name }}</h2>
            
            @if($group->students()->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 font-medium text-lg">No students in this group yet</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($group->students as $student)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="font-semibold text-slate-900">{{ $student->first_name }} {{ $student->second_name }}</div>
                                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-1 rounded">Active</span>
                            </div>
                            <div class="text-sm text-slate-600 space-y-1">
                                <p>📧 {{ $student->email ?? 'N/A' }}</p>
                                <p>📱 {{ $student->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Training Sessions -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">📅 Training Sessions</h2>
            
            @if($group->trainingSessions()->isEmpty())
                <div class="text-center py-12">
                    <p class="text-slate-500 font-medium text-lg">No training sessions scheduled for this group</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($group->trainingSessions as $session)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900">{{ $session->date->format('M d, Y') }} • {{ $session->start_time }}–{{ $session->end_time }}</p>
                                <p class="text-sm text-slate-600 mt-1">📍 {{ $session->location ?? 'N/A' }} • 👨‍🏫 {{ $session->coach->name ?? 'N/A' }}</p>
                            </div>
                            <a href="{{ route('admin.sessions.edit', $session) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded hover:bg-indigo-200 transition">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
