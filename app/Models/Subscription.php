<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','subscription_plan_id','start_date','end_date','next_billing_date','status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalInvoicedAttribute(): int
    {
        return $this->invoices()->sum('amount_cents');
    }

    public function getTotalPaidAttribute(): int
    {
        return $this->payments()->where('status', 'succeeded')->sum('amount_cents');
    }

    public function getOutstandingBalanceAttribute(): int
    {
        return max(0, $this->total_invoiced - $this->total_paid);
    }
}
