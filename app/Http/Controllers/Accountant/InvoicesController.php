<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $invoices = Invoice::with(['subscription.student','subscription.plan'])
            ->when($q, function($query) use ($q) {
                $query->whereHas('subscription.student', function($s) use ($q) {
                    $s->where('first_name','like',"%$q%")->orWhere('last_name','like',"%$q%");
                });
            })
            ->orderByDesc('due_date')
            ->paginate(20);

        return view('accountant.invoices.index', compact('invoices','q'));
    }

    public function create()
    {
        $subscriptions = Subscription::with('student','plan')
            ->where('status', 'active')
            ->get();
        return view('accountant.invoices.create', compact('subscriptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subscription_id' => ['required','exists:subscriptions,id'],
            'amount_cents' => ['required','integer','min:0'],
            'currency' => ['required','string','size:3'],
            'due_date' => ['required','date'],
            'notes' => ['nullable','string'],
        ]);
        $data['status'] = 'pending';
        Invoice::create($data);
        return redirect()->route('accountant.invoices.index')->with('success', 'Invoice created');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('subscription.student','subscription.plan');
        return view('accountant.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'amount_cents' => ['required','integer','min:0'],
            'due_date' => ['required','date'],
            'status' => ['required','in:pending,paid,overdue,cancelled'],
            'notes' => ['nullable','string'],
        ]);
        $invoice->update($data);
        return redirect()->route('accountant.invoices.index')->with('success', 'Invoice updated');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('accountant.invoices.index')->with('success', 'Invoice deleted');
    }
}
