<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'second_name', 'dob', 'gender', 'parent_user_id', 'phone', 'status', 'joined_at', 'branch_id', 'group_id'
    ];

    protected $casts = [
        'dob' => 'date',
        'joined_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            // Prefer the storage disk URL (works whether or not storage:link exists)
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($this->photo_path, '/'));
            } catch (\Throwable $e) {
                // Fallback to asset path if Storage driver cannot produce a URL
                return asset('storage/' . ltrim($this->photo_path, '/'));
            }
        }
        // Fallback avatar (SVG data URI or a generic placeholder)
        $initials = strtoupper(mb_substr($this->first_name ?? 'S', 0, 1) . mb_substr($this->second_name ?? 'T', 0, 1));
        $bg = '3b82f6'; // blue-600
        $fg = 'ffffff';
        return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&background={$bg}&color={$fg}&size=128&bold=true";
    }

    public function getAgeAttribute()
    {
        return $this->dob ? \Carbon\Carbon::parse($this->dob)->age : null;
    }
}
