<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlansController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('active','desc')->orderBy('name')->paginate(12);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'price_cents' => ['required','integer','min:0'],
            'currency' => ['required','string','size:3'],
            'interval' => ['required', Rule::in(['monthly','quarterly','yearly'])],
            'active' => ['nullable','boolean'],
            'description' => ['nullable','string'],
        ]);
        $data['active'] = (bool)($data['active'] ?? true);
        SubscriptionPlan::create($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100'],
            'price_cents' => ['required','integer','min:0'],
            'currency' => ['required','string','size:3'],
            'interval' => ['required', Rule::in(['monthly','quarterly','yearly'])],
            'active' => ['nullable','boolean'],
            'description' => ['nullable','string'],
        ]);
        $data['active'] = (bool)($data['active'] ?? false);
        $plan->update($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted');
    }
}
