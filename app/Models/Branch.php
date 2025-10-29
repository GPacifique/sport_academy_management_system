<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address', 'phone'];

    protected static function booted(): void
    {
        static::created(function (Branch $branch) {
            // Ensure groups A-F exist for every new branch
            $letters = ['A','B','C','D','E','F'];
            foreach ($letters as $letter) {
                $branch->groups()->firstOrCreate(['name' => $letter]);
            }
        });
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }
}
