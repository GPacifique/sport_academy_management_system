<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $from = $request->query('from');
        $to = $request->query('to');

        $payments = Payment::with(['student','subscription.plan','invoice'])
            ->when($q, function($query) use ($q) {
                $query->whereHas('student', function($s) use ($q) {
                    $s->where('first_name','like',"%$q%")->orWhere('last_name','like',"%$q%");
                })->orWhere('reference','like',"%$q%");
            })
            ->when($from, fn($query) => $query->whereDate('paid_at', '>=', $from))
            ->when($to, fn($query) => $query->whereDate('paid_at', '<=', $to))
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('accountant.payments.index', compact('payments','q','from','to'));
    }

    public function export(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $payments = Payment::with(['student','subscription.plan','invoice'])
            ->when($from, fn($query) => $query->whereDate('paid_at', '>=', $from))
            ->when($to, fn($query) => $query->whereDate('paid_at', '<=', $to))
            ->orderBy('paid_at')
            ->get();

        $filename = 'payments_' . ($from ?? 'all') . '_to_' . ($to ?? now()->format('Y-m-d')) . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Student','Subscription Plan','Invoice','Amount','Currency','Method','Status','Reference','Notes']);
            foreach ($payments as $p) {
                fputcsv($file, [
                    optional($p->paid_at)->format('Y-m-d H:i') ?? $p->created_at->format('Y-m-d H:i'),
                    $p->student->first_name . ' ' . $p->student->last_name,
                    optional($p->subscription?->plan)->name ?? '—',
                    $p->invoice_id ? "Invoice #{$p->invoice_id}" : '—',
                    number_format($p->amount_cents / 100, 2),
                    $p->currency,
                    ucfirst(str_replace('_', ' ', $p->method)),
                    ucfirst($p->status),
                    $p->reference ?? '—',
                    $p->notes ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $students = Student::orderBy('first_name')->orderBy('last_name')->get();
        $invoices = \App\Models\Invoice::with('subscription.student')
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->get();
        return view('accountant.payments.create', compact('students', 'invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'subscription_id' => ['nullable','exists:subscriptions,id'],
            'invoice_id' => ['nullable','exists:invoices,id'],
            'amount_cents' => ['required','integer','min:0'],
            'currency' => ['required','string','size:3'],
            'method' => ['required','in:cash,mobile_money,card,bank'],
            'status' => ['nullable','in:pending,succeeded,failed'],
            'paid_at' => ['nullable','date'],
            'reference' => ['nullable','string','max:120'],
            'notes' => ['nullable','string'],
        ]);
        $data['status'] = $data['status'] ?? 'succeeded';
        if (!isset($data['paid_at']) || empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }
        $payment = Payment::create($data);

        // If linked to invoice, check if fully paid and mark as paid
        if ($payment->invoice_id) {
            $payment->invoice->markAsPaidIfFull();
        }

        return redirect()->route('accountant.payments.index')->with('success', 'Payment recorded');
    }
}
