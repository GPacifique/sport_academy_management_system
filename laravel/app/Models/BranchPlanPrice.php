<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchPlanPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id','subscription_plan_id','price_cents'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
