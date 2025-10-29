@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">👤 Student Profile</h1>
            <p class="text-slate-600 mt-1">
                <span class="font-semibold">{{ $student->first_name }} {{ $student->second_name }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.edit', $student) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">✏️ Edit</a>
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-semibold transition">← Back</a>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <!-- Card Header with Photo -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-8 flex items-start gap-6">
            <img src="{{ $student->photo_url }}" alt="{{ $student->first_name }} {{ $student->second_name }}" class="w-24 h-24 rounded-lg object-cover ring-2 ring-white shadow-md">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-slate-900">{{ $student->first_name }} {{ $student->second_name }}</h2>
                
                <!-- Jersey Info -->
                @if($student->jersey_number || $student->jersey_name)
                    <div class="flex items-center gap-2 mt-3">
                        @if($student->jersey_number)
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded">Jersey #{{ $student->jersey_number }}</span>
                        @endif
                        @if($student->jersey_name)
                            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded">{{ $student->jersey_name }}</span>
                        @endif
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="mt-3">
                    @if($student->status === 'active')
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">✓ Active</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-full">○ {{ ucfirst($student->status) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-200">Personal Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Date of Birth</p>
                            <p class="text-slate-900 font-medium">{{ optional($student->dob)->format('M d, Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Gender</p>
                            <p class="text-slate-900 font-medium">{{ ucfirst($student->gender ?? '—') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Email</p>
                            <p class="text-slate-900 font-medium">{{ $student->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Phone</p>
                            <p class="text-slate-900 font-medium">{{ $student->phone ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-200">Family Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Father's Name</p>
                            <p class="text-slate-900 font-medium">{{ $student->father_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Mother's Name</p>
                            <p class="text-slate-900 font-medium">{{ $student->mother_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Emergency Phone</p>
                            <p class="text-slate-900 font-medium">{{ $student->emergency_phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Parent Account</p>
                            <p class="text-slate-900 font-medium">
                                @if($student->parent)
                                    {{ $student->parent->name }}<br>
                                    <span class="text-sm text-slate-600">{{ $student->parent->email }}</span>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Academy Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-200">Academy Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Branch</p>
                            <p class="text-slate-900 font-medium">{{ $student->branch?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Group</p>
                            <p class="text-slate-900 font-medium">{{ $student->group?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Sport Discipline</p>
                            <p class="text-slate-900 font-medium">{{ $student->sport_discipline ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Position</p>
                            <p class="text-slate-900 font-medium">{{ $student->position ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-200">Additional Details</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Coach</p>
                            <p class="text-slate-900 font-medium">{{ $student->coach ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">School</p>
                            <p class="text-slate-900 font-medium">{{ $student->school_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Membership Type</p>
                            <p class="text-slate-900 font-medium">{{ $student->membership_type ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase">Joined</p>
                            <p class="text-slate-900 font-medium">{{ optional($student->joined_at)->format('M d, Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">
            <a href="{{ route('admin.students.edit', $student) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg font-semibold transition">✏️ Edit Profile</a>
            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition">🗑️ Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
