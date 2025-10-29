@php($title = 'Bulk Import Student Photos')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="flex items-center justify-between mb-6">
        <h1 class="page-title">Bulk Import Photos</h1>
        <a href="{{ route('admin.students.index') }}" class="btn-secondary">Back to Students</a>
    </div>

    <div class="card">
        <div class="card-body space-y-6">
            <div class="subtle">Upload a CSV mapping and the corresponding image files. CSV headers must be: <code>student_id, filename</code>. Filenames must match the uploaded images.</div>
            <div class="bg-slate-50 dark:bg-slate-900/40 rounded-md p-4 text-sm">
                <div class="font-semibold mb-2">CSV example</div>
                <pre class="overflow-x-auto"><code>student_id,filename
12,photo12.jpg
15,student15.png
22,2025-10-27_22.jpeg</code></pre>
            </div>

            <form action="{{ route('admin.students.importProcess') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label mb-1">CSV file</label>
                        <input type="file" name="csv" accept=".csv,text/csv" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" required>
                        <x-input-error :messages="$errors->get('csv')" class="mt-1" />
                    </div>
                    <div>
                        <label class="label mb-1">Photos</label>
                        <input type="file" name="photos[]" accept="image/*" multiple class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <x-input-error :messages="$errors->get('photos')" class="mt-1" />
                        <x-input-error :messages="$errors->get('photos.*')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button name="preview" value="1" class="btn-outline" type="submit">Preview</button>
                    <button class="btn-primary" type="submit" onclick="this.form.insertAdjacentHTML('beforeend','<input type=\'hidden\' name=\'apply\' value=\'1\'>')">Apply Import</button>
                </div>
            </form>
        </div>
    </div>

    @isset($results)
        <div class="card mt-6 overflow-hidden">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Preview</div>
                    @isset($summary)
                        <div class="text-sm subtle">OK: {{ $summary['ok'] ?? 0 }} · Missing Photos: {{ $summary['missing'] ?? 0 }} · Students Not Found: {{ $summary['notfound'] ?? 0 }}</div>
                    @endisset
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Filename</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r['student_id'] }}</td>
                            <td class="px-4 py-3">{{ $r['student_name'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $r['filename'] }}</td>
                            <td class="px-4 py-3">
                                @switch($r['status'])
                                    @case('ok')<span class="badge badge-green">OK</span>@break
                                    @case('applied')<span class="badge badge-green">Applied</span>@break
                                    @case('photo_missing')<span class="badge badge-slate">Photo missing</span>@break
                                    @case('student_not_found')<span class="badge badge-red">Student not found</span>@break
                                    @default <span class="badge badge-slate">{{ $r['status'] }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endisset

    @if (session('status'))
        <div class="mt-6">
            <div data-flash="success" class="hidden">{{ session('status') }}</div>
        </div>
    @endif
</div>
@endsection
