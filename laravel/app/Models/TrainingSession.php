<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'start_time', 'end_time', 'location', 'group_name', 'coach_user_id', 'branch_id', 'group_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }
}
