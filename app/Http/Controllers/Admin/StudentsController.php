<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Group;
use App\Models\User;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['branch', 'group', 'parent'])
            ->orderBy('first_name')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $parents = User::role('parent')->orderBy('name')->get(['id','name','email']);
        return view('admin.students.create', compact('branches','groups','parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'second_name' => ['required','string','max:255'],
            'dob' => ['nullable','date'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'father_name' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'emergency_phone' => ['nullable','string','max:50'],
            'mother_name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'status' => ['nullable', Rule::in(['active','inactive'])],
            'joined_at' => ['nullable','date'],
            'branch_id' => ['nullable', Rule::exists('branches','id')],
            'group_id' => ['nullable', Rule::exists('groups','id')],
            'parent_user_id' => ['nullable', Rule::exists('users','id')],
            'photo' => ['nullable','image','max:2048'],
            'sport_discipline' => ['nullable','string','max:255'],
            'jersey_number' => ['nullable','string','max:50'],
            'jersey_name' => ['nullable','string','max:255'],
            'school_name' => ['nullable','string','max:255'],
            'position' => ['nullable','string','max:255'],
            'coach' => ['nullable','string','max:255'],
            'combination' => ['nullable','string','max:255'],
            'membership_type' => ['nullable','string','max:255'],
            'program' => ['nullable','string','max:255'],
        ]);

        $student = new Student($data);
        $student->status = $student->status ?? 'active';
        $student->registered_by = auth()->id();
        $student->save();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos/students', 'public');
            $student->photo_path = $path;
            $student->save();
        }

        return redirect()->route('admin.students.index')->with('status', 'Student created.');
    }

    public function edit(Student $student)
    {
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $parents = User::role('parent')->orderBy('name')->get(['id','name','email']);
        return view('admin.students.edit', compact('student','branches','groups','parents'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'second_name' => ['required','string','max:255'],
            'dob' => ['nullable','date'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'father_name' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'emergency_phone' => ['nullable','string','max:50'],
            'mother_name' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'status' => ['nullable', Rule::in(['active','inactive'])],
            'joined_at' => ['nullable','date'],
            'branch_id' => ['nullable', Rule::exists('branches','id')],
            'group_id' => ['nullable', Rule::exists('groups','id')],
            'parent_user_id' => ['nullable', Rule::exists('users','id')],
            'photo' => ['nullable','image','max:2048'],
            'sport_discipline' => ['nullable','string','max:255'],
            'jersey_number' => ['nullable','string','max:50'],
            'jersey_name' => ['nullable','string','max:255'],
            'school_name' => ['nullable','string','max:255'],
            'position' => ['nullable','string','max:255'],
            'coach' => ['nullable','string','max:255'],
            'combination' => ['nullable','string','max:255'],
            'membership_type' => ['nullable','string','max:255'],
            'program' => ['nullable','string','max:255'],
        ]);

        $student->fill($data);
        $student->status = $student->status ?? 'active';
        $student->save();

        if ($request->hasFile('photo')) {
            // delete old if present
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('photos/students', 'public');
            $student->photo_path = $path;
            $student->save();
        }

        return redirect()->route('admin.students.index')->with('status', 'Student updated.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
            Storage::disk('public')->delete($student->photo_path);
        }
        $student->delete();
        return redirect()->route('admin.students.index')->with('status', 'Student deleted.');
    }

    public function importForm()
    {
        return view('admin.students.import');
    }

    public function importProcess(Request $request)
    {
        $data = $request->validate([
            'csv' => ['required','file','mimes:csv,txt','max:4096'],
            'photos' => ['nullable','array'],
            'photos.*' => ['file','image','max:5120'],
            'apply' => ['nullable','boolean'],
        ]);

        $apply = (bool)($data['apply'] ?? false);
        $photos = $request->file('photos', []);
        $byName = [];
        foreach ($photos as $file) {
            $byName[strtolower($file->getClientOriginalName())] = $file;
        }

        $handle = fopen($request->file('csv')->getRealPath(), 'r');
        if (!$handle) {
            return back()->withErrors('Unable to read CSV file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors('CSV appears to be empty.');
        }
        $header = array_map(fn($h) => strtolower(trim((string)$h)), $header);
        $idxStudent = array_search('student_id', $header, true);
        $idxFilename = array_search('filename', $header, true);
        if ($idxStudent === false || $idxFilename === false) {
            fclose($handle);
            return back()->withErrors('CSV must have headers: student_id, filename');
        }

        $results = [];
        $applied = 0; $skipped = 0; $missing = 0; $notfound = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (!is_array($row) || count($row) < max($idxStudent, $idxFilename)+1) { continue; }
            $studentId = (int) trim((string) $row[$idxStudent]);
            $filename = trim((string) $row[$idxFilename]);
            $key = strtolower($filename);
            $student = Student::find($studentId);

            $entry = [
                'student_id' => $studentId,
                'student_name' => $student ? ($student->first_name.' '.$student->second_name) : null,
                'filename' => $filename,
                'status' => null,
            ];

            if (!$student) {
                $entry['status'] = 'student_not_found';
                $notfound++;
                $results[] = $entry;
                continue;
            }
            if ($filename === '' || !isset($byName[$key])) {
                $entry['status'] = 'photo_missing';
                $missing++;
                $results[] = $entry;
                continue;
            }

            if ($apply) {
                $file = $byName[$key];
                // delete old
                if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                    Storage::disk('public')->delete($student->photo_path);
                }
                $path = $file->store('photos/students', 'public');
                $student->photo_path = $path;
                $student->save();
                $entry['status'] = 'applied';
                $applied++;
                // prevent re-use
                unset($byName[$key]);
            } else {
                $entry['status'] = 'ok';
            }
            $results[] = $entry;
        }
        fclose($handle);

        if ($apply) {
            return redirect()->route('admin.students.importForm')
                ->with('status', "Import completed: {$applied} applied, {$missing} missing photos, {$notfound} students not found.");
        }

        return view('admin.students.import', [
            'results' => $results,
            'summary' => [
                'ok' => collect($results)->where('status','ok')->count(),
                'missing' => $missing,
                'notfound' => $notfound,
            ],
        ]);
    }
}
