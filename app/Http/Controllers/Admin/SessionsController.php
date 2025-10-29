<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Group;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function index()
    {
        $sessions = TrainingSession::with(['coach', 'branch', 'group'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $coaches = User::role('coach')->orderBy('name')->get();

        return view('admin.sessions.create', compact('branches', 'groups', 'coaches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'coach_user_id' => ['required', 'integer', 'exists:users,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
        ]);

        // Ensure the group belongs to the branch
        $group = Group::where('id', $data['group_id'])
            ->where('branch_id', $data['branch_id'])
            ->firstOrFail();

        TrainingSession::create([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'coach_user_id' => $data['coach_user_id'],
            'branch_id' => $data['branch_id'],
            'group_id' => $group->id,
            'group_name' => $group->name,
        ]);

        return redirect()->route('admin.sessions.index')->with('status', 'Session created.');
    }

    public function edit(TrainingSession $session)
    {
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $coaches = User::role('coach')->orderBy('name')->get();

        return view('admin.sessions.edit', compact('session', 'branches', 'groups', 'coaches'));
    }

    public function update(Request $request, TrainingSession $session)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'coach_user_id' => ['required', 'integer', 'exists:users,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
        ]);

        $group = Group::where('id', $data['group_id'])
            ->where('branch_id', $data['branch_id'])
            ->firstOrFail();

        $session->update([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'coach_user_id' => $data['coach_user_id'],
            'branch_id' => $data['branch_id'],
            'group_id' => $group->id,
            'group_name' => $group->name,
        ]);

        return redirect()->route('admin.sessions.index')->with('status', 'Session updated.');
    }

    public function destroy(TrainingSession $session)
    {
        // Clean up related attendance records to avoid orphans
        \App\Models\StudentAttendance::where('training_session_id', $session->id)->delete();
        \App\Models\CoachAttendance::where('training_session_id', $session->id)->delete();
        $session->delete();

        return redirect()->route('admin.sessions.index')->with('status', 'Session deleted.');
    }
}
