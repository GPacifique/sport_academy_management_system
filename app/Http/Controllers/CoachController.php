<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $sessionsToday = TrainingSession::where('coach_user_id', Auth::id())
            ->where('date', $today)
            ->orderBy('start_time')
            ->get();

        return view('coach.dashboard', [
            'sessionsToday' => $sessionsToday,
        ]);
    }
}
