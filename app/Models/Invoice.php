<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id','amount_cents','currency','due_date','status','notes'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute(): int
    {
        return $this->payments()->where('status', 'succeeded')->sum('amount_cents');
    }

    public function getOutstandingBalanceAttribute(): int
    {
        return max(0, $this->amount_cents - $this->total_paid);
    }

    public function markAsPaidIfFull(): void
    {
        if ($this->outstanding_balance === 0 && $this->status !== 'paid') {
            $this->update(['status' => 'paid']);
        }
    }
}
