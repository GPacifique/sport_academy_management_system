@php($title = 'Create Group')
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.groups.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold mb-4 inline-block">
                ← Back to Groups
            </a>
            <h1 class="text-4xl font-bold text-slate-900">➕ Create New Group</h1>
            <p class="text-slate-600 mt-2">Add a new training group to your academy</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.groups.store') }}" class="space-y-6">
                @csrf

                <!-- Branch Selection -->
                <div>
                    <label for="branch_id" class="block text-sm font-semibold text-slate-700 mb-3">
                        🏢 Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('branch_id') border-red-500 @enderror">
                        <option value="">Select a branch...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-3">
                        👥 Group Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required placeholder="e.g., Beginners A, Advanced B" value="{{ old('name') }}" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                    <p class="text-slate-500 text-sm mt-2">Choose a descriptive name for this group</p>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t border-slate-200">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                        ✅ Create Group
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
