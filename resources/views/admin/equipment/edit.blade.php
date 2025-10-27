@php($title = 'Edit Equipment')
@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Edit Equipment</h1>
                <p class="text-slate-600 mt-1">Update equipment details</p>
            </div>
            <a href="{{ route('admin.equipment.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                ← Back to List
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Equipment Name *</label>
                                <input type="text" name="name" value="{{ old('name', $equipment->name) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $equipment->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Category *</label>
                                <select name="category" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    <option value="balls" @selected(old('category', $equipment->category) === 'balls')>Balls</option>
                                    <option value="nets" @selected(old('category', $equipment->category) === 'nets')>Nets</option>
                                    <option value="training" @selected(old('category', $equipment->category) === 'training')>Training Equipment</option>
                                    <option value="safety" @selected(old('category', $equipment->category) === 'safety')>Safety Gear</option>
                                    <option value="facility" @selected(old('category', $equipment->category) === 'facility')>Facility Equipment</option>
                                    <option value="other" @selected(old('category', $equipment->category) === 'other')>Other</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Branch</label>
                                <select name="branch_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('branch_id') border-red-500 @enderror">
                                    <option value="">No Specific Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected(old('branch_id', $equipment->branch_id) == $branch->id)>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Status -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Quantity & Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Total Quantity *</label>
                                <input type="number" name="quantity" value="{{ old('quantity', $equipment->quantity) }}" min="0" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quantity') border-red-500 @enderror">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Available Quantity *</label>
                                <input type="number" name="available_quantity" value="{{ old('available_quantity', $equipment->available_quantity) }}" min="0" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('available_quantity') border-red-500 @enderror">
                                @error('available_quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Condition *</label>
                                <select name="condition" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('condition') border-red-500 @enderror">
                                    <option value="good" @selected(old('condition', $equipment->condition) === 'good')>Good</option>
                                    <option value="fair" @selected(old('condition', $equipment->condition) === 'fair')>Fair</option>
                                    <option value="poor" @selected(old('condition', $equipment->condition) === 'poor')>Poor</option>
                                    <option value="damaged" @selected(old('condition', $equipment->condition) === 'damaged')>Damaged</option>
                                </select>
                                @error('condition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                                <select name="status" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                                    <option value="available" @selected(old('status', $equipment->status) === 'available')>Available</option>
                                    <option value="in_use" @selected(old('status', $equipment->status) === 'in_use')>In Use</option>
                                    <option value="maintenance" @selected(old('status', $equipment->status) === 'maintenance')>Maintenance</option>
                                    <option value="retired" @selected(old('status', $equipment->status) === 'retired')>Retired</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Information -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Purchase Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Purchase Price (Rwf)</label>
                                <input type="number" name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}" step="0.01" min="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('purchase_price') border-red-500 @enderror">
                                @error('purchase_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Purchase Date</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date', $equipment->purchase_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('purchase_date') border-red-500 @enderror">
                                @error('purchase_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location & Notes -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Additional Details</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Storage Location</label>
                                <input type="text" name="location" value="{{ old('location', $equipment->location) }}" placeholder="e.g., Storage Room A, Field Equipment Shed" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('location') border-red-500 @enderror">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
                                <textarea name="notes" rows="3" placeholder="Additional information about this equipment..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes', $equipment->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 pt-6 border-t border-slate-200">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Update Equipment
                        </button>
                        <a href="{{ route('admin.equipment.index') }}" class="px-8 py-3 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
