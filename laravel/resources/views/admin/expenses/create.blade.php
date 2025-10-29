@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('admin.expenses.index') }}" 
               class="text-slate-600 hover:text-slate-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Add New Expense</h1>
                <p class="text-slate-600 mt-1">Record a new expense for the academy</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-8">
        <form method="POST" action="{{ route('admin.expenses.store') }}" class="space-y-6">
            @csrf

            <!-- Branch -->
            <div>
                <label for="branch_id" class="block text-sm font-medium text-slate-700 mb-2">
                    Branch <span class="text-slate-400">(Optional)</span>
                </label>
                <select id="branch_id" 
                        name="branch_id" 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('branch_id') border-red-500 @enderror">
                    <option value="">-- All Branches / General --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-slate-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="category" 
                        name="category" 
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('category') border-red-500 @enderror">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="description" 
                       name="description" 
                       value="{{ old('description') }}"
                       required
                       placeholder="e.g., Monthly rent for MASAKA branch"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount and Expense Date (2 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">
                        Amount (RWF) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount') }}"
                               required
                               min="0"
                               step="1"
                               placeholder="50000"
                               class="w-full px-4 py-2 pr-16 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-slate-500 text-sm font-medium">RWF</span>
                        </div>
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expense Date -->
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-slate-700 mb-2">
                        Expense Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="expense_date" 
                           name="expense_date" 
                           value="{{ old('expense_date', date('Y-m-d')) }}"
                           required
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('expense_date') border-red-500 @enderror">
                    @error('expense_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Method and Receipt Number (2 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-2">
                        Payment Method <span class="text-slate-400">(Optional)</span>
                    </label>
                    <select id="payment_method" 
                            name="payment_method" 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                        <option value="">-- Select Method --</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receipt Number -->
                <div>
                    <label for="receipt_number" class="block text-sm font-medium text-slate-700 mb-2">
                        Receipt Number <span class="text-slate-400">(Optional)</span>
                    </label>
                    <input type="text" 
                           id="receipt_number" 
                           name="receipt_number" 
                           value="{{ old('receipt_number') }}"
                           placeholder="RCP-2025-001"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('receipt_number') border-red-500 @enderror">
                    @error('receipt_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Vendor Name -->
            <div>
                <label for="vendor_name" class="block text-sm font-medium text-slate-700 mb-2">
                    Vendor/Supplier Name <span class="text-slate-400">(Optional)</span>
                </label>
                <input type="text" 
                       id="vendor_name" 
                       name="vendor_name" 
                       value="{{ old('vendor_name') }}"
                       placeholder="e.g., ABC Sports Equipment Ltd"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('vendor_name') border-red-500 @enderror">
                @error('vendor_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                    Additional Notes <span class="text-slate-400">(Optional)</span>
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          placeholder="Any additional details about this expense..."
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            This expense will be created with <strong>Pending</strong> status and will require approval before it can be marked as paid.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.expenses.index') }}" 
                   class="px-6 py-2 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
