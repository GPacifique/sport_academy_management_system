@php($title = 'User Dashboard')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back!</h1>
            <p class="text-slate-600 mt-1">Your personalized dashboard</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-stat-card title="My Attendance" value="0%" icon="✅" color="emerald" />
            <x-stat-card title="Upcoming Sessions" value="0" icon="📅" color="blue" />
            <x-stat-card title="Pending Fees" value="$0" icon="💳" color="amber" />
        </div>

        <div class="bg-white rounded-lg shadow-md border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Upcoming Training Sessions</h2>
            <div class="text-sm text-slate-600">No upcoming sessions scheduled.</div>
        </div>
    </div>
@endsection