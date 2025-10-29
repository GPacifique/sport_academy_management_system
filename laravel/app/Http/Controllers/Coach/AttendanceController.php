<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\TrainingSession;
use App\Models\CoachAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $sessions = TrainingSession::where('coach_user_id', Auth::id())
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('coach.attendance.index', compact('sessions'));
    }

    public function show(TrainingSession $session)
    {
        $this->authorizeSession($session);

        $students = Student::query()
            ->where('branch_id', $session->branch_id)
            ->where('group_id', $session->group_id)
            ->orderBy('first_name')
            ->orderBy('second_name')
            ->get();

        $existing = StudentAttendance::where('training_session_id', $session->id)
            ->pluck('status', 'student_id');

        return view('coach.attendance.show', [
            'session' => $session,
            'students' => $students,
            'existing' => $existing,
        ]);
    }

    public function store(Request $request, TrainingSession $session)
    {
        $this->authorizeSession($session);

        $data = $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*.student_id' => ['required', 'integer'],
            'attendance.*.status' => ['required', 'in:present,absent'],
        ]);

        $validStudentIds = Student::where('branch_id', $session->branch_id)
            ->where('group_id', $session->group_id)
            ->pluck('id')
            ->all();

        DB::transaction(function () use ($data, $session, $validStudentIds) {
            foreach ($data['attendance'] as $row) {
                $sid = (int) $row['student_id'];
                if (!in_array($sid, $validStudentIds, true)) {
                    continue; // skip students not in this coach's session scope
                }
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $sid,
                        'training_session_id' => $session->id,
                    ],
                    [
                        'status' => $row['status'],
                        'notes' => $row['notes'] ?? null,
                    ]
                );
            }

            // Mark coach attendance as present when submitting
            CoachAttendance::updateOrCreate(
                [
                    'coach_user_id' => Auth::id(),
                    'training_session_id' => $session->id,
                ],
                [
                    'status' => 'present',
                ]
            );
        });

        return redirect()
            ->route('coach.attendance.index')
            ->with('status', 'Attendance saved.');
    }

    protected function authorizeSession(TrainingSession $session): void
    {
        abort_unless($session->coach_user_id === Auth::id(), 403);
    }
}
