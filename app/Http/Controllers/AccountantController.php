<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class AccountantController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Total revenue this month (succeeded payments)
        $totalRevenueCents = Payment::where('status', 'succeeded')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount_cents');

        // Outstanding balance: sum of pending/overdue invoice balances
        $invoices = Invoice::with('payments')
            ->whereIn('status', ['pending','overdue'])
            ->get();
        $outstandingCents = $invoices->sum(fn($inv) => $inv->outstanding_balance ?? max(0, ($inv->amount_cents ?? 0) - ($inv->total_paid ?? 0)));

        // Invoice counts
        $pendingInvoices = Invoice::where('status','pending')->count();
        $overdueInvoices = Invoice::where('status','overdue')->count();

        // Subscription stats
        $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count();
        $totalSubscriptions = \App\Models\Subscription::count();

                // Payment stats
        $succeededPayments = Payment::where('status', 'succeeded')->count();
        $totalRevenueAllTime = Payment::where('status', 'succeeded')->sum('amount_cents');

        // Expense stats
        $pendingExpenses = Expense::where('status', 'pending')->count();
        $totalExpensesThisMonth = Expense::whereIn('status', ['approved', 'paid'])
            ->whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount_cents');

        // Calculate net profit
        $netProfitThisMonth = $totalRevenueCents - $totalExpensesThisMonth;
        $netProfitColor = $netProfitThisMonth >= 0 ? 'green' : 'rose';

        // Fetch recent payments
        $recentPayments = Payment::with(['student', 'subscription.plan', 'invoice'])
            ->where('status', 'succeeded')
            ->latest('paid_at')
            ->limit(10)
            ->get();

        return view('accountant.dashboard', [
            'totalRevenueCents' => $totalRevenueCents,
            'outstandingCents' => $outstandingCents,
            'pendingInvoices' => $pendingInvoices,
            'overdueInvoices' => $overdueInvoices,
            'recentPayments' => $recentPayments,
            'activeSubscriptions' => $activeSubscriptions,
            'totalSubscriptions' => $totalSubscriptions,
            'succeededPayments' => $succeededPayments,
            'totalRevenueAllTime' => $totalRevenueAllTime,
            'pendingExpenses' => $pendingExpenses,
            'totalExpensesThisMonth' => $totalExpensesThisMonth,
            'netProfitThisMonth' => $netProfitThisMonth,
            'netProfitColor' => $netProfitColor,
        ]);
    }

    /**
     * Return JSON metrics for dashboard charts.
     * - monthlyRevenue: labels (month) and data (amount in cents)
     * - agingBuckets: associative array of bucket => amount_cents
     */
    public function metrics(Request $request)
    {
        $now = now();
        $months = [];
        $labels = [];
        $data = [];

        // last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $start = $now->copy()->subMonths($i)->startOfMonth();
            $end = $now->copy()->subMonths($i)->endOfMonth();
            $label = $start->format('M Y');
            $months[] = [$start, $end];
            $labels[] = $label;
            $sum = \App\Models\Payment::where('status', 'succeeded')
                ->whereBetween('paid_at', [$start, $end])
                ->sum('amount_cents');
            $data[] = (int) $sum;
        }

        // Aging buckets based on invoice due_date and outstanding balance
        $today = now()->startOfDay();
        $buckets = [
            'current' => 0,
            '1-30' => 0,
            '31-60' => 0,
            '61-90' => 0,
            '90+' => 0,
        ];

        $invoices = \App\Models\Invoice::select('id', 'amount_cents', 'due_date', 'status')
            ->whereIn('status', ['pending','overdue'])
            ->get();

        foreach ($invoices as $inv) {
            $balance = $inv->outstanding_balance ?? ($inv->amount_cents ?? 0);
            $due = $inv->due_date ? \Illuminate\Support\Carbon::parse($inv->due_date)->startOfDay() : null;
            if (!$due) {
                $buckets['current'] += (int) $balance;
                continue;
            }
            $days = $due->diffInDays($today, false); // positive if due in past
            if ($days <= 0) {
                // not yet due or due today
                $buckets['current'] += (int) $balance;
            } elseif ($days <= 30) {
                $buckets['1-30'] += (int) $balance;
            } elseif ($days <= 60) {
                $buckets['31-60'] += (int) $balance;
            } elseif ($days <= 90) {
                $buckets['61-90'] += (int) $balance;
            } else {
                $buckets['90+'] += (int) $balance;
            }
        }

        return response()->json([
            'monthlyRevenue' => [
                'labels' => $labels,
                'data' => $data,
            ],
            'agingBuckets' => $buckets,
        ]);
    }
}
