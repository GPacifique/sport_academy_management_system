<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','subscription_id','invoice_id','amount_cents','currency','method','status','paid_at','reference','notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gatewayTransaction()
    {
        return $this->hasOne(PaymentGatewayTransaction::class);
    }
}
