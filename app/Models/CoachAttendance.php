<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_user_id', 'training_session_id', 'status', 'notes'
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_user_id');
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }
}
