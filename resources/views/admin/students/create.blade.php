@php($title = 'Create Student')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header">
            <h1 class="page-title">Create Student</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.input label="First name" name="first_name" required />
                    <x-form.input label="Last name" name="last_name" required />
                    <x-form.input label="Date of birth" name="dob" type="date" />
                    <x-form.select label="Gender" name="gender">
                        <option value="">—</option>
                        <option value="male" @selected(old('gender')==='male')>Male</option>
                        <option value="female" @selected(old('gender')==='female')>Female</option>
                        <option value="other" @selected(old('gender')==='other')>Other</option>
                    </x-form.select>
                    <x-form.input label="Phone" name="phone" />
                    <x-form.select label="Parent" name="parent_user_id">
                        <option value="">—</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" @selected(old('parent_user_id')==$p->id)>{{ $p->name }} ({{ $p->email }})</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Status" name="status">
                        <option value="active" @selected(old('status','active')==='active')>Active</option>
                        <option value="inactive" @selected(old('status')==='inactive')>Inactive</option>
                    </x-form.select>
                    <x-form.input label="Joined at" name="joined_at" type="date" />
                    <x-form.select label="Branch" name="branch_id">
                        <option value="">—</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" @selected(old('branch_id')==$b->id)>{{ $b->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Group" name="group_id">
                        <option value="">—</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->id }}" @selected(old('group_id')==$g->id)>{{ $g->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.input label="Jersey Number" name="jersey_number" placeholder="e.g., 10" />
                    <x-form.input label="Jersey Name" name="jersey_name" placeholder="e.g., RONALDO" />
                    <div class="md:col-span-2">
                        <label class="label mb-1">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('admin.students.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Create</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
