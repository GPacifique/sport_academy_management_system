<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $subs = Subscription::with(['student','plan'])
            ->when($q, function($query) use ($q) {
                $query->whereHas('student', function($s) use ($q) {
                    $s->where('first_name','like',"%$q%")->orWhere('second_name','like',"%$q%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('accountant.subscriptions.index', compact('subs','q'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('active', true)->orderBy('name')->get();
        $students = Student::orderBy('first_name')->orderBy('second_name')->get();
        return view('accountant.subscriptions.create', compact('plans','students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'subscription_plan_id' => ['required','exists:subscription_plans,id'],
            'start_date' => ['required','date'],
            'status' => ['nullable', Rule::in(['active','paused','cancelled','expired'])],
        ]);
        $data['status'] = $data['status'] ?? 'active';
        $plan = SubscriptionPlan::findOrFail($data['subscription_plan_id']);
        // compute next billing date from interval
        $start = \Carbon\Carbon::parse($data['start_date']);
        $next = match($plan->interval) {
            'monthly' => $start->copy()->addMonth(),
            'quarterly' => $start->copy()->addMonths(3),
            'yearly' => $start->copy()->addYear(),
            default => null,
        };
        $data['next_billing_date'] = $next?->toDateString();
        Subscription::create($data);
        return redirect()->route('accountant.subscriptions.index')->with('success', 'Subscription created');
    }

    public function edit(Subscription $subscription)
    {
        $plans = SubscriptionPlan::where('active', true)->orderBy('name')->get();
        return view('accountant.subscriptions.edit', [
            'subscription' => $subscription->load('student','plan'),
            'plans' => $plans,
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'subscription_plan_id' => ['required','exists:subscription_plans,id'],
            'status' => ['required', Rule::in(['active','paused','cancelled','expired'])],
            'end_date' => ['nullable','date'],
        ]);
        $subscription->update($data);
        return redirect()->route('accountant.subscriptions.index')->with('success', 'Subscription updated');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('accountant.subscriptions.index')->with('success', 'Subscription deleted');
    }
}
