<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        $coach = Auth::user();
        
        // Get all sessions for this coach
        $sessions = TrainingSession::where('coach_user_id', $coach->id)
            ->with(['group', 'branch'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('coach.sessions.index', compact('sessions'));
    }

    public function create()
    {
        $coach = Auth::user();
        
        // Get groups for the coach's branch, or all groups if no branch assigned
        $groups = Group::orderBy('name');
        if ($coach->branch_id) {
            $groups = $groups->where('branch_id', $coach->branch_id);
        }
        $groups = $groups->get();

        // If no groups available, show message
        if ($groups->isEmpty()) {
            return redirect()->route('coach.attendance.index')
                ->with('error', 'No groups available. Please contact administrator.');
        }

        return view('coach.sessions.create', [
            'groups' => $groups,
            'defaultGroupId' => $coach->group_id,
            'branch' => $coach->branch,
        ]);
    }

    public function store(Request $request)
    {
        $coach = Auth::user();

        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'group_id' => ['required', 'integer', 'exists:groups,id'],
        ]);

        // Ensure the selected group is in coach's branch (and optionally same group)
        $group = Group::where('id', $data['group_id'])
            ->where('branch_id', $coach->branch_id)
            ->firstOrFail();

        TrainingSession::create([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'location' => $data['location'],
            'coach_user_id' => $coach->id,
            'branch_id' => $coach->branch_id,
            'group_id' => $group->id,
            'group_name' => $group->name,
        ]);

        return redirect()->route('coach.attendance.index')->with('status', 'Session scheduled successfully.');
    }
}
