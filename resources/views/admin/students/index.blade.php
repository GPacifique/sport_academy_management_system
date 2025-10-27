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

    @if(request('view') === 'cards')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($students as $student)
                <div class="card overflow-hidden">
                    <div class="card-body flex items-center gap-4">
                        <img src="{{ $student->photo_url }}" alt="{{ $student->first_name }} {{ $student->last_name }}" class="w-16 h-16 rounded-full object-cover ring-2 ring-slate-200 dark:ring-slate-800">
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="subtle truncate">{{ $student->branch?->name ?? '—' }} @if($student->group) • Group {{ $student->group->name }} @endif</div>
                            @if($student->parent)
                                <div class="text-xs text-slate-500 dark:text-slate-400 truncate">Parent: <span class="text-slate-700 dark:text-slate-300">{{ $student->parent->name }}</span> • <span class="truncate inline-block align-bottom">{{ $student->parent->email }}</span></div>
                            @endif
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                @if($student->status)
                                    <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($student->status) }}</span>
                                @endif
                                @if($student->phone)
                                    <span class="badge badge-slate">📞 {{ $student->phone }}</span>
                                @endif
                                @if($student->joined_at)
                                    <span class="badge badge-slate">Joined {{ $student->joined_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pb-4 sm:px-6 flex items-center justify-end gap-2">
                        <a class="text-indigo-700 hover:underline px-2" href="#">View</a>
                        <a class="text-indigo-700 hover:underline px-2" href="#">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $students->appends(request()->query())->links() }}</div>
    @else
        <div class="card overflow-hidden">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Group</th>
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
                                    <span>{{ $student->first_name }} {{ $student->last_name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $student->branch?->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $student->group?->name ?? '—' }}</td>
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
                                <a class="text-indigo-700 hover:underline px-2" href="#">View</a>
                                <a class="text-indigo-700 hover:underline px-2" href="#">Edit</a>
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
