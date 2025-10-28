<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Models\User;
use App\Models\Branch;
use App\Models\Student;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $range = $request->query('range', 'today'); // today|week|month|all
        $branchId = $request->query('branch_id');

        // Determine date window
        $start = null; $end = null; $rangeLabel = 'Today';
        switch ($range) {
            case 'week':
                $start = now()->startOfWeek()->toDateString();
                $end = now()->endOfWeek()->toDateString();
                $rangeLabel = 'This Week';
                break;
            case 'month':
                $start = now()->startOfMonth()->toDateString();
                $end = now()->endOfMonth()->toDateString();
                $rangeLabel = 'This Month';
                break;
            case 'all':
                $start = $end = null;
                $rangeLabel = 'All Time';
                break;
            case 'today':
            default:
                $start = $today; $end = $today; $rangeLabel = 'Today';
        }

        // Sessions query for list and count
        $sessionListQuery = TrainingSession::with(['coach','branch','group'])
            ->when($start && $end, fn($q) => $q->whereBetween('date', [$start, $end]))
            ->when(($start && !$end) || (!$start && $end), fn($q) => $q->where('date', $start ?? $end))
            ->when(!$start && !$end, fn($q) => $q)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('date')
            ->orderBy('start_time');

        $sessionsForRange = (clone $sessionListQuery)->limit(8)->get();
        $sessionsCount = (clone $sessionListQuery)->count();

        // Stats
        $stats = [
            'totalUsers' => User::count(),
            'totalBranches' => Branch::count(),
            'activeStudents' => Student::where('status', 'active')->count(),
            'todaySessions' => $sessionsCount,
            'deactivatedUsers' => User::onlyTrashed()->count(),
            'coachUsers' => User::role('coach')->count(),
            'totalGroups' => Group::count(),
            'sessionsThisWeek' => TrainingSession::whereBetween('date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])->count(),
            // Payment & Subscription stats
            'activeSubscriptions' => Subscription::where('status', 'active')->count(),
            'totalSubscriptions' => Subscription::count(),
            'revenueThisMonth' => Payment::where('status', 'succeeded')
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount_cents'),
            'pendingInvoices' => Invoice::whereIn('status', ['pending', 'overdue'])->count(),
            'totalRevenue' => Payment::where('status', 'succeeded')->sum('amount_cents'),
            
            // Expenses
            'pendingExpenses' => Expense::where('status', 'pending')->count(),
            'totalExpensesThisMonth' => Expense::whereIn('status', ['approved', 'paid'])
                ->whereBetween('expense_date', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount_cents'),
            'totalExpenses' => Expense::whereIn('status', ['approved', 'paid'])->sum('amount_cents'),
        ];

        // Calculate net profit
        $netProfit = ($stats['revenueThisMonth'] ?? 0) - ($stats['totalExpensesThisMonth'] ?? 0);

        // Additional trend data for charts: last 8 weeks of sessions
        $weeklyTrends = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $count = TrainingSession::whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])->count();
            $weeklyTrends[] = [
                'label' => $weekStart->format('M d'),
                'sessions' => $count,
            ];
        }

        // Coach workload (top 5 coaches by session count this month)
        $coachWorkload = TrainingSession::selectRaw('coach_id, COUNT(*) as session_count')
            ->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->groupBy('coach_id')
            ->orderByDesc('session_count')
            ->limit(5)
            ->with('coach:id,name')
            ->get()
            ->map(fn($item) => [
                'coach' => $item->coach->name ?? 'Unknown',
                'sessions' => $item->session_count,
            ]);

        // Equipment utilization (assume Equipment model tracks usage)
        $equipmentCount = \App\Models\Equipment::count();
        $equipmentInUse = \App\Models\Equipment::where('status', 'in_use')->count();
        $equipmentUtilization = $equipmentCount > 0 ? round(($equipmentInUse / $equipmentCount) * 100, 1) : 0;

        return view('admin.dashboard', [
            'todaysSessions' => $sessionsForRange,
            'sessions' => $sessionsForRange,
            'rangeLabel' => $rangeLabel,
            'currentRange' => $range,
            'currentBranchId' => $branchId,
            'branches' => Branch::orderBy('name')->get(),
            'stats' => $stats,
            'netProfit' => $netProfit,
            'weeklyTrends' => $weeklyTrends,
            'coachWorkload' => $coachWorkload,
            'equipmentUtilization' => $equipmentUtilization,
        ]);
    }
}
