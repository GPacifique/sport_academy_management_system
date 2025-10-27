@php($title = 'Equipment Details')
@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $equipment->name }}</h1>
                <p class="text-slate-600 mt-1">Equipment details and information</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.equipment.edit', $equipment) }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Edit Equipment
                </a>
                <a href="{{ route('admin.equipment.index') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                    ← Back to List
                </a>
            </div>
        </div>

        <!-- Equipment Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Basic Information</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Equipment Name</dt>
                            <dd class="text-base font-medium text-slate-900">{{ $equipment->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Category</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($equipment->category === 'balls') bg-blue-100 text-blue-800
                                    @elseif($equipment->category === 'nets') bg-purple-100 text-purple-800
                                    @elseif($equipment->category === 'training') bg-green-100 text-green-800
                                    @elseif($equipment->category === 'safety') bg-red-100 text-red-800
                                    @elseif($equipment->category === 'facility') bg-amber-100 text-amber-800
                                    @else bg-slate-100 text-slate-800 @endif">
                                    {{ ucfirst($equipment->category) }}
                                </span>
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Description</dt>
                            <dd class="text-base text-slate-900">{{ $equipment->description ?? 'No description provided' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Quantity & Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Quantity & Status</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Total Quantity</dt>
                            <dd class="text-2xl font-bold text-slate-900">{{ $equipment->quantity }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Available Quantity</dt>
                            <dd class="text-2xl font-bold text-green-600">{{ $equipment->available_quantity }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Condition</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($equipment->condition === 'good') bg-green-100 text-green-800
                                    @elseif($equipment->condition === 'fair') bg-yellow-100 text-yellow-800
                                    @elseif($equipment->condition === 'poor') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($equipment->condition) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Status</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($equipment->status === 'available') bg-green-100 text-green-800
                                    @elseif($equipment->status === 'in_use') bg-blue-100 text-blue-800
                                    @elseif($equipment->status === 'maintenance') bg-amber-100 text-amber-800
                                    @else bg-slate-100 text-slate-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $equipment->status)) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Location & Purchase Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Location & Purchase Information</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Branch</dt>
                            <dd class="text-base font-medium text-slate-900">{{ $equipment->branch->name ?? 'No specific branch' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Storage Location</dt>
                            <dd class="text-base font-medium text-slate-900">{{ $equipment->location ?? 'Not specified' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Purchase Price</dt>
                            <dd class="text-base font-medium text-slate-900">
                                @if($equipment->purchase_price)
                                    {{ number_format($equipment->purchase_price, 2) }} Rwf
                                @else
                                    Not recorded
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Purchase Date</dt>
                            <dd class="text-base font-medium text-slate-900">
                                {{ $equipment->purchase_date?->format('M d, Y') ?? 'Not recorded' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                @if($equipment->notes)
                    <!-- Notes Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Notes</h2>
                        <p class="text-base text-slate-700 whitespace-pre-line">{{ $equipment->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Availability</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm opacity-90 mb-1">In Use</div>
                            <div class="text-3xl font-bold">{{ $equipment->quantity - $equipment->available_quantity }}</div>
                        </div>
                        <div class="pt-3 border-t border-indigo-400">
                            <div class="text-sm opacity-90 mb-1">Usage Rate</div>
                            <div class="text-2xl font-bold">
                                @if($equipment->quantity > 0)
                                    {{ round((($equipment->quantity - $equipment->available_quantity) / $equipment->quantity) * 100) }}%
                                @else
                                    0%
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Metadata</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Added On</dt>
                            <dd class="text-sm text-slate-900">{{ $equipment->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500 mb-1">Last Updated</dt>
                            <dd class="text-sm text-slate-900">{{ $equipment->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.equipment.edit', $equipment) }}" class="block w-full px-4 py-2 bg-indigo-600 text-white text-center font-semibold rounded-lg hover:bg-indigo-700 transition">
                            Edit Equipment
                        </a>
                        <form action="{{ route('admin.equipment.destroy', $equipment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this equipment? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full px-4 py-2 bg-red-600 text-white text-center font-semibold rounded-lg hover:bg-red-700 transition">
                                Delete Equipment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
