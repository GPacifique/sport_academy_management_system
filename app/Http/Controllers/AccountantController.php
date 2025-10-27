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
}
