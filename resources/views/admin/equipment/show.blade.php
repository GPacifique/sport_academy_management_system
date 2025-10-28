@php($title = 'Equipment Details - ' . $equipment->name)
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl shadow-lg p-8 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <a href="{{ route('admin.equipment.index') }}" class="text-indigo-100 hover:text-white font-semibold mb-4 inline-flex items-center gap-2">
                        ← Back to Equipment
                    </a>
                    <h1 class="text-4xl font-bold mb-2">🎽 {{ $equipment->name }}</h1>
                    <p class="text-indigo-100 text-lg">Category: <span class="font-semibold">{{ $equipment->category }}</span></p>
                </div>
                <a href="{{ route('admin.equipment.edit', $equipment) }}" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-indigo-50 transition shadow-md">
                    ✏️ Edit Equipment
                </a>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Total Quantity</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $equipment->quantity ?? 0 }}</p>
                    </div>
                    <div class="text-4xl opacity-20">📦</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">Available</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $equipment->available_quantity ?? 0 }}</p>
                    </div>
                    <div class="text-4xl opacity-20">✓</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 font-semibold uppercase">In Use</p>
                        <p class="text-3xl font-bold text-orange-600 mt-1">{{ ($equipment->quantity ?? 0) - ($equipment->available_quantity ?? 0) }}</p>
                    </div>
                    <div class="text-4xl opacity-20">👥</div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow-sm border border-purple-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-900 font-semibold uppercase">Condition</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $equipment->condition ?? 'Good' }}</p>
                    </div>
                    <div class="text-4xl opacity-20">🔧</div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Equipment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">ℹ️ Equipment Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Equipment Name</p>
                                <p class="text-lg text-slate-900 font-semibold">{{ $equipment->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Category</p>
                                <p class="text-lg text-slate-900 font-semibold">
                                    <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 font-semibold rounded">
                                        {{ $equipment->category }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Condition</p>
                                <p class="text-lg text-slate-900 font-semibold">
                                    @php
                                        $conditionColors = [
                                            'Excellent' => 'bg-green-100 text-green-700',
                                            'Good' => 'bg-blue-100 text-blue-700',
                                            'Fair' => 'bg-yellow-100 text-yellow-700',
                                            'Poor' => 'bg-red-100 text-red-700',
                                        ];
                                        $condition = $equipment->condition ?? 'Good';
                                    @endphp
                                    <span class="inline-block px-3 py-1 {{ $conditionColors[$condition] ?? 'bg-slate-100 text-slate-700' }} font-semibold rounded">
                                        {{ $condition }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Status</p>
                                <p class="text-lg text-slate-900 font-semibold">
                                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 font-semibold rounded">
                                        ✓ Active
                                    </span>
                                </p>
                            </div>
                            @if($equipment->description)
                                <div class="md:col-span-2">
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Description</p>
                                    <p class="text-slate-900">{{ $equipment->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quantity & Availability -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">📊 Quantity & Availability</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Total Quantity -->
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-700 font-bold uppercase">Total Quantity</p>
                                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $equipment->quantity ?? 0 }}</p>
                                <p class="text-xs text-blue-600 mt-1">units in inventory</p>
                            </div>

                            <!-- Available Quantity -->
                            <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                                <p class="text-sm text-green-700 font-bold uppercase">Available</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $equipment->available_quantity ?? 0 }}</p>
                                <p class="text-xs text-green-600 mt-1">ready for use</p>
                            </div>

                            <!-- In Use Quantity -->
                            <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                                <p class="text-sm text-orange-700 font-bold uppercase">In Use</p>
                                <p class="text-3xl font-bold text-orange-600 mt-2">{{ ($equipment->quantity ?? 0) - ($equipment->available_quantity ?? 0) }}</p>
                                <p class="text-xs text-orange-600 mt-1">currently assigned</p>
                            </div>
                        </div>

                        <!-- Usage Rate -->
                        <div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-bold text-slate-700 uppercase">Usage Rate</p>
                                <p class="text-sm font-bold text-slate-900">{{ $equipment->quantity > 0 ? round((($equipment->quantity - ($equipment->available_quantity ?? 0)) / $equipment->quantity) * 100) : 0 }}%</p>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full" style="width: {{ $equipment->quantity > 0 ? round((($equipment->quantity - ($equipment->available_quantity ?? 0)) / $equipment->quantity) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Purchase Info -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900">📍 Location & Purchase Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($equipment->location)
                                <div>
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Location</p>
                                    <p class="text-slate-900 text-lg">{{ $equipment->location }}</p>
                                </div>
                            @endif
                            @if($equipment->purchase_date)
                                <div>
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Purchase Date</p>
                                    <p class="text-slate-900 text-lg">{{ \Carbon\Carbon::parse($equipment->purchase_date)->format('M d, Y') }}</p>
                                </div>
                            @endif
                            @if($equipment->purchase_price)
                                <div>
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Purchase Price</p>
                                    <p class="text-slate-900 text-lg font-semibold">${{ number_format($equipment->purchase_price, 2) }}</p>
                                </div>
                            @endif
                            @if($equipment->supplier)
                                <div>
                                    <p class="text-xs text-slate-600 font-bold uppercase tracking-wide mb-2">Supplier</p>
                                    <p class="text-slate-900 text-lg">{{ $equipment->supplier }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Equipment Notes -->
                @if($equipment->notes)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                            <h2 class="text-lg font-bold text-slate-900">📝 Notes</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-900 leading-relaxed">{{ $equipment->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Equipment Summary -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl shadow-sm border border-indigo-200 p-6">
                    <h3 class="text-lg font-bold text-indigo-900 mb-4">📋 Summary</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-white rounded-lg border border-indigo-100">
                            <p class="text-xs text-slate-600 font-semibold">NAME</p>
                            <p class="text-slate-900 font-semibold mt-1">{{ $equipment->name }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-indigo-100">
                            <p class="text-xs text-slate-600 font-semibold">CATEGORY</p>
                            <p class="text-slate-900 font-semibold mt-1">{{ $equipment->category }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-indigo-100">
                            <p class="text-xs text-slate-600 font-semibold">CONDITION</p>
                            <p class="text-slate-900 font-semibold mt-1">{{ $equipment->condition ?? 'Good' }}</p>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-indigo-100">
                            <p class="text-xs text-slate-600 font-semibold">TOTAL UNITS</p>
                            <p class="text-slate-900 font-semibold mt-1">{{ $equipment->quantity ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.equipment.edit', $equipment) }}" class="block px-4 py-2 bg-indigo-100 text-indigo-700 text-center font-semibold rounded-lg hover:bg-indigo-200 transition">
                            ✏️ Edit Equipment
                        </a>
                        <a href="{{ route('admin.equipment.index') }}" class="block px-4 py-2 bg-slate-100 text-slate-700 text-center font-semibold rounded-lg hover:bg-slate-200 transition">
                            📦 View All Equipment
                        </a>
                    </div>
                </div>

                <!-- Availability Status -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">📊 Availability</h3>
                    <div class="space-y-4">
                        <div class="p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-blue-700 font-semibold">Available</p>
                                <p class="text-xl font-bold text-blue-600">{{ $equipment->available_quantity ?? 0 }}</p>
                            </div>
                            <div class="text-xs text-blue-600">{{ round((($equipment->available_quantity ?? 0) / ($equipment->quantity ?? 1)) * 100) }}% available</div>
                        </div>

                        <div class="p-3 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-orange-700 font-semibold">In Use</p>
                                <p class="text-xl font-bold text-orange-600">{{ ($equipment->quantity ?? 0) - ($equipment->available_quantity ?? 0) }}</p>
                            </div>
                            <div class="text-xs text-orange-600">{{ round(((($equipment->quantity ?? 0) - ($equipment->available_quantity ?? 0)) / ($equipment->quantity ?? 1)) * 100) }}% in use</div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">ℹ️ Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">ID</dt>
                            <dd class="text-slate-900 font-mono">{{ $equipment->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">Created</dt>
                            <dd class="text-slate-900">{{ $equipment->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-600 font-bold uppercase mb-1">Updated</dt>
                            <dd class="text-slate-900">{{ $equipment->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
