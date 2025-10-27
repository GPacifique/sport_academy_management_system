@php($title = 'Edit Student')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h1 class="page-title">Edit Student</h1>
            <div class="flex items-center gap-3">
                <img src="{{ $student->photo_url }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-800" alt="">
                <span class="subtle">{{ $student->first_name }} {{ $student->last_name }}</span>
            </div>
        </div>
        <div class="card-body">
            <form id="student-edit-form" method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input label="First name" name="first_name" :value="$student->first_name" required />
                    <x-form.input label="Last name" name="last_name" :value="$student->last_name" required />
                    <x-form.input label="Date of birth" name="dob" type="date" :value="optional($student->dob)->format('Y-m-d')" />
                    <x-form.select label="Gender" name="gender">
                        <option value="">—</option>
                        <option value="male" @selected(old('gender', $student->gender)==='male')>Male</option>
                        <option value="female" @selected(old('gender', $student->gender)==='female')>Female</option>
                        <option value="other" @selected(old('gender', $student->gender)==='other')>Other</option>
                    </x-form.select>
                    <x-form.input label="Phone" name="phone" :value="$student->phone" />
                    <x-form.select label="Parent" name="parent_user_id">
                        <option value="">—</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" @selected(old('parent_user_id', $student->parent_user_id)==$p->id)>{{ $p->name }} ({{ $p->email }})</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Status" name="status">
                        <option value="active" @selected(old('status', $student->status ?? 'active')==='active')>Active</option>
                        <option value="inactive" @selected(old('status', $student->status)==='inactive')>Inactive</option>
                    </x-form.select>
                    <x-form.input label="Joined at" name="joined_at" type="date" :value="optional($student->joined_at)->format('Y-m-d')" />
                    <x-form.select label="Branch" name="branch_id">
                        <option value="">—</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" @selected(old('branch_id', $student->branch_id)==$b->id)>{{ $b->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Group" name="group_id">
                        <option value="">—</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->id }}" @selected(old('group_id', $student->group_id)==$g->id)>{{ $g->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input label="Jersey Number" name="jersey_number" :value="$student->jersey_number" placeholder="e.g., 10" />
                    <x-form.input label="Jersey Name" name="jersey_name" :value="$student->jersey_name" placeholder="e.g., RONALDO" />
                    <div class="md:col-span-2">
                        <label class="label mb-1">Photo</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $student->photo_url }}" class="w-16 h-16 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-800" alt="">
                            <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                        <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div></div>
                    <div class="flex items-center gap-2">
                        <x-button :href="route('admin.students.index')" variant="secondary">Cancel</x-button>
                        <x-button type="submit" form="student-edit-form" variant="primary">Save changes</x-button>
                    </div>
                </div>
            </form>
            <div class="mt-4">
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger">Delete</x-button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
