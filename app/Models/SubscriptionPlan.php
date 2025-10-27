<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','price_cents','currency','interval','active','description'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function branchPrices()
    {
        return $this->hasMany(BranchPlanPrice::class);
    }

    /**
     * Get the price for a given branch, or default price if no override exists.
     */
    public function getPriceForBranch(?int $branchId): int
    {
        if ($branchId) {
            $override = $this->branchPrices()->where('branch_id', $branchId)->first();
            if ($override) {
                return $override->price_cents;
            }
        }
        return $this->price_cents;
    }
}
