@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="flex items-center justify-between mb-6">
        <h1 class="page-title">Students</h1>
        <div class="flex items-center gap-2">
            <x-button :href="route('admin.students.create')" variant="primary">New Student</x-button>
            <x-button :href="route('admin.students.importForm')" variant="outline">Bulk Import Photos</x-button>
            <x-button :href="request()->fullUrlWithQuery(['view' => 'table'])" variant="outline" class="{{ request('view') !== 'cards' ? 'ring-1 ring-indigo-500/40' : '' }}">Table</x-button>
            <x-button :href="request()->fullUrlWithQuery(['view' => 'cards'])" variant="outline" class="{{ request('view') === 'cards' ? 'ring-1 ring-indigo-500/40' : '' }}">Cards</x-button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" class="flex items-center gap-3">
            <input type="hidden" name="view" value="{{ request('view') }}">
            <div class="flex-1 flex items-center gap-2">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $q ?? '' }}" 
                    placeholder="Search by name, email, phone, jersey number or jersey name…" 
                    class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                />
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                    🔍 Search
                </button>
                @if($q ?? false)
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition">
                        ✕ Clear
                    </a>
                @endif
            </div>
        </form>
        @if($q ?? false)
            <p class="text-sm text-slate-600 mt-2">
                Found {{ $students->total() }} student(s) matching "<strong>{{ $q }}</strong>"
            </p>
        @endif
    </div>

    @if(request('view') === 'cards')
        @if($students->isEmpty())
            <div class="card text-center py-16">
                <svg class="mx-auto h-16 w-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
                </svg>
                <p class="text-slate-500 font-medium text-lg mb-2">
                    @if($q ?? false)
                        No students found matching "{{ $q }}"
                    @else
                        No students yet
                    @endif
                </p>
                <p class="text-slate-600 text-sm mb-6">
                    @if($q ?? false)
                        Try a different search term
                    @else
                        Create a new student to get started
                    @endif
                </p>
                @if(!($q ?? false))
                    <a href="{{ route('admin.students.create') }}" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                        ➕ Create First Student
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($students as $student)
                    <div class="card overflow-hidden hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-4 py-3 border-b border-slate-200">
                            <img src="{{ $student->photo_url }}" alt="{{ $student->first_name }} {{ $student->second_name }}" class="w-full h-40 object-cover rounded-lg mb-2">
                            <div class="font-semibold text-slate-900 text-sm truncate">{{ $student->first_name }} {{ $student->second_name }}</div>
                            
                            <!-- Jersey Badges -->
                            @if($student->jersey_number || $student->jersey_name)
                                <div class="flex items-center gap-1 mt-2 flex-wrap">
                                    @if($student->jersey_number)
                                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded">Jersey #{{ $student->jersey_number }}</span>
                                    @endif
                                    @if($student->jersey_name)
                                        <span class="inline-block px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">{{ $student->jersey_name }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="subtle truncate text-xs mb-2">{{ $student->branch?->name ?? '—' }} @if($student->group) • Group {{ $student->group->name }} @endif</div>
                            @if($student->parent)
                                <div class="text-xs text-slate-500 dark:text-slate-400 truncate mb-2">Parent: <span class="text-slate-700 dark:text-slate-300">{{ $student->parent->name }}</span></div>
                            @endif
                            <div class="mt-2 flex flex-wrap items-center gap-1 text-xs">
                                @if($student->status)
                                    <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($student->status) }}</span>
                                @endif
                                @if($student->sport_discipline)
                                    <span class="badge badge-indigo">🏆 {{ $student->sport_discipline }}</span>
                                @endif
                                @if($student->phone)
                                    <span class="badge badge-slate">📞 {{ $student->phone }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="px-4 pb-4 sm:px-6 flex flex-col gap-2 text-sm">
                            <a href="{{ route('admin.students.show', $student) }}" class="text-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition">👁️ View Details</a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="text-center px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg font-semibold transition">✏️ Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $students->appends(request()->query())->links() }}</div>
        @endif
    @else
        <div class="card overflow-hidden">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name & Jersey</th>
                        <th>Branch</th>
                        <th>Group</th>
                        <th>Sport</th>
                        <th>Parent</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student->photo_url }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-800" alt="">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 dark:text-white truncate">{{ $student->first_name }} {{ $student->second_name }}</div>
                                        @if($student->jersey_number || $student->jersey_name)
                                            <div class="flex items-center gap-1 mt-1">
                                                @if($student->jersey_number)
                                                    <span class="inline-block px-1.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded">J#{{ $student->jersey_number }}</span>
                                                @endif
                                                @if($student->jersey_name)
                                                    <span class="inline-block px-1.5 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">{{ $student->jersey_name }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $student->branch?->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $student->group?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($student->sport_discipline)
                                    <span class="badge badge-indigo">🏆 {{ $student->sport_discipline }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($student->parent)
                                    <div class="leading-tight">
                                        <div>{{ $student->parent->name }}</div>
                                        <div class="text-xs subtle">{{ $student->parent->email }}</div>
                                    </div>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $student->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ ($student->status ?? 'active') === 'active' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($student->status ?? 'active') }}</span>
                            </td>
                            <td class="px-4 py-3">{{ optional($student->joined_at)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a class="text-indigo-700 hover:underline px-2 font-semibold text-sm" href="{{ route('admin.students.show', $student) }}">View</a>
                                <a class="text-indigo-700 hover:underline px-2 font-semibold text-sm" href="{{ route('admin.students.edit', $student) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $students->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
