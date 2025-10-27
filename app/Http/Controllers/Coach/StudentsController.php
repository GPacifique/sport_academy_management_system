<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $coach = Auth::user();

        $q = trim((string) $request->get('q'));

        $students = Student::with(['parent', 'group'])
            ->where('branch_id', $coach->branch_id)
            ->where('group_id', $coach->group_id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('first_name', 'like', "%$q%")
                        ->orWhere('last_name', 'like', "%$q%")
                        ->orWhere('phone', 'like', "%$q%");
                });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15)
            ->withQueryString();

        return view('coach.students.index', [
            'students' => $students,
            'q' => $q,
        ]);
    }

    public function show(Student $student)
    {
        $coach = Auth::user();
        abort_unless($student->branch_id === $coach->branch_id && $student->group_id === $coach->group_id, 403);

        $student->load(['parent', 'branch', 'group']);

        return view('coach.students.show', compact('student'));
    }

    public function attendance(Student $student)
    {
        $coach = Auth::user();
        abort_unless($student->branch_id === $coach->branch_id && $student->group_id === $coach->group_id, 403);

        // Join sessions to filter to this coach's sessions and sort by session date/time
        $records = \App\Models\StudentAttendance::query()
            ->where('student_id', $student->id)
            ->join('training_sessions', 'student_attendances.training_session_id', '=', 'training_sessions.id')
            ->where('training_sessions.coach_user_id', $coach->id)
            ->orderByDesc('training_sessions.date')
            ->orderByDesc('training_sessions.start_time')
            ->select('student_attendances.*',
                'training_sessions.date as session_date',
                'training_sessions.start_time as session_start',
                'training_sessions.end_time as session_end',
                'training_sessions.location as session_location',
                'training_sessions.group_name as session_group')
            ->paginate(15);

        return view('coach.students.attendance', [
            'student' => $student,
            'records' => $records,
        ]);
    }
}
