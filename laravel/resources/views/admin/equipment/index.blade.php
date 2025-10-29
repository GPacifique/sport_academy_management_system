@php($title = 'Equipment Management')
@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Equipment Management</h1>
                <p class="text-slate-600 mt-1">Manage sports equipment and inventory</p>
            </div>
            <a href="{{ route('admin.equipment.create') }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Equipment
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search equipment..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Categories</option>
                        <option value="balls" @selected(request('category') === 'balls')>Balls</option>
                        <option value="nets" @selected(request('category') === 'nets')>Nets</option>
                        <option value="training" @selected(request('category') === 'training')>Training Equipment</option>
                        <option value="safety" @selected(request('category') === 'safety')>Safety Gear</option>
                        <option value="facility" @selected(request('category') === 'facility')>Facility Equipment</option>
                        <option value="other" @selected(request('category') === 'other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="available" @selected(request('status') === 'available')>Available</option>
                        <option value="in_use" @selected(request('status') === 'in_use')>In Use</option>
                        <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                        <option value="retired" @selected(request('status') === 'retired')>Retired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Condition</label>
                    <select name="condition" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Conditions</option>
                        <option value="good" @selected(request('condition') === 'good')>Good</option>
                        <option value="fair" @selected(request('condition') === 'fair')>Fair</option>
                        <option value="poor" @selected(request('condition') === 'poor')>Poor</option>
                        <option value="damaged" @selected(request('condition') === 'damaged')>Damaged</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Branch</label>
                    <select name="branch_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(request('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2 md:col-span-2 lg:col-span-5">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.equipment.index') }}" class="px-6 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Equipment List -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if($equipment->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-slate-500 font-medium text-lg">No equipment found</p>
                    <a href="{{ route('admin.equipment.create') }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Add First Equipment
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Equipment</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Condition</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Branch</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($equipment as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $item->name }}</div>
                                        @if($item->description)
                                            <div class="text-sm text-slate-500">{{ Str::limit($item->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($item->category === 'balls') bg-blue-100 text-blue-800
                                            @elseif($item->category === 'nets') bg-purple-100 text-purple-800
                                            @elseif($item->category === 'training') bg-green-100 text-green-800
                                            @elseif($item->category === 'safety') bg-red-100 text-red-800
                                            @elseif($item->category === 'facility') bg-amber-100 text-amber-800
                                            @else bg-slate-100 text-slate-800 @endif">
                                            {{ ucfirst($item->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $item->available_quantity }} / {{ $item->quantity }}</div>
                                        <div class="text-xs text-slate-500">Available / Total</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($item->condition === 'good') bg-green-100 text-green-800
                                            @elseif($item->condition === 'fair') bg-yellow-100 text-yellow-800
                                            @elseif($item->condition === 'poor') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($item->condition) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($item->status === 'available') bg-green-100 text-green-800
                                            @elseif($item->status === 'in_use') bg-blue-100 text-blue-800
                                            @elseif($item->status === 'maintenance') bg-amber-100 text-amber-800
                                            @else bg-slate-100 text-slate-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $item->branch->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $item->location ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.equipment.show', $item) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                            <a href="{{ route('admin.equipment.edit', $item) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                            <form action="{{ route('admin.equipment.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this equipment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $equipment->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
