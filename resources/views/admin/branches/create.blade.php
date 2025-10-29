@php($title = 'Create Branch')
@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.branches.index') }}" class="text-green-600 hover:text-green-800 font-semibold mb-4 inline-block">
                ← Back to Branches
            </a>
            <h1 class="text-4xl font-bold text-slate-900">➕ Create New Branch</h1>
            <p class="text-slate-600 mt-2">Add a new location to your sports academy</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.branches.store') }}" class="space-y-6">
                @csrf

                <!-- Branch Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-3">
                        🏢 Branch Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required placeholder="e.g., Downtown Branch" value="{{ old('name') }}" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Code -->
                <div>
                    <label for="code" class="block text-sm font-semibold text-slate-700 mb-3">
                        🔖 Branch Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="code" name="code" required placeholder="e.g., BR001" value="{{ old('code') }}" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('code') border-red-500 @enderror">
                    <p class="text-slate-500 text-sm mt-2">A unique identifier for this branch</p>
                    @error('code')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-slate-700 mb-3">
                        📍 Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" required rows="3" placeholder="Full address of the branch" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-3">
                        📱 Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" required placeholder="e.g., +250 788 123 456" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">ℹ️ Note:</span> Default groups (A, B, C, D, E, F) will be automatically created for this branch.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t border-slate-200">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                        ✅ Create Branch
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
