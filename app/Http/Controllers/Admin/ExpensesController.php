<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    /**
     * Display a listing of expenses
     */
    public function index(Request $request)
    {
        $query = Expense::with(['branch', 'user', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%");
            });
        }

        $expenses = $query->latest('expense_date')->paginate(20);

        // Calculate totals for current filters
        $totalAmount = $query->sum('amount_cents');
        $pendingCount = Expense::where('status', 'pending')->count();
        $approvedCount = Expense::where('status', 'approved')->count();
        $paidCount = Expense::where('status', 'paid')->count();

        $branches = Branch::all();

        return view('admin.expenses.index', compact(
            'expenses',
            'branches',
            'totalAmount',
            'pendingCount',
            'approvedCount',
            'paidCount'
        ));
    }

    /**
     * Show the form for creating a new expense
     */
    public function create()
    {
        $branches = Branch::all();
        $categories = Expense::categories();
        $paymentMethods = Expense::paymentMethods();

        return view('admin.expenses.create', compact('branches', 'categories', 'paymentMethods'));
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
        ]);

        $expense = Expense::create([
            'branch_id' => $validated['branch_id'] ?? null,
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'notes' => $validated['notes'] ?? null,
            'amount_cents' => (int)($validated['amount'] * 100),
            'currency' => 'RWF',
            'expense_date' => $validated['expense_date'],
            'payment_method' => $validated['payment_method'] ?? null,
            'receipt_number' => $validated['receipt_number'] ?? null,
            'vendor_name' => $validated['vendor_name'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.expenses.index')
            ->with('status', 'Expense created successfully.');
    }

    /**
     * Show the form for editing an expense
     */
    public function edit(Expense $expense)
    {
        $branches = Branch::all();
        $categories = Expense::categories();
        $paymentMethods = Expense::paymentMethods();
        $statuses = Expense::statuses();

        return view('admin.expenses.edit', compact('expense', 'branches', 'categories', 'paymentMethods', 'statuses'));
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected,paid',
        ]);

        $expense->update([
            'branch_id' => $validated['branch_id'] ?? null,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'notes' => $validated['notes'] ?? null,
            'amount_cents' => (int)($validated['amount'] * 100),
            'expense_date' => $validated['expense_date'],
            'payment_method' => $validated['payment_method'] ?? null,
            'receipt_number' => $validated['receipt_number'] ?? null,
            'vendor_name' => $validated['vendor_name'] ?? null,
            'status' => $validated['status'],
        ]);

        // If approving, record who approved
        if ($validated['status'] === 'approved' && $expense->status !== 'approved') {
            $expense->update([
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        }

        return redirect()->route('admin.expenses.index')
            ->with('status', 'Expense updated successfully.');
    }

    /**
     * Approve an expense
     */
    public function approve(Expense $expense)
    {
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Expense approved successfully.');
    }

    /**
     * Reject an expense
     */
    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);

        return redirect()->back()->with('status', 'Expense rejected.');
    }

    /**
     * Mark expense as paid
     */
    public function markPaid(Expense $expense)
    {
        $expense->update(['status' => 'paid']);

        return redirect()->back()->with('status', 'Expense marked as paid.');
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('status', 'Expense deleted successfully.');
    }
}
