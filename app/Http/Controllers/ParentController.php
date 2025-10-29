<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function index()
    {
        $parent = Auth::user();
        
        // Get children for this parent with subscriptions and invoices
        $children = Student::where('parent_user_id', $parent->id)
            ->with(['subscriptions.plan', 'subscriptions.invoices', 'payments'])
            ->get();

        return view('parent.dashboard', compact('children'));
    }

    public function childPayments(Student $student)
    {
        // Verify parent owns this child
        if ($student->parent_user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $payments = $student->payments()
            ->with(['subscription.plan', 'invoice'])
            ->orderByDesc('paid_at')
            ->paginate(15);

        return view('parent.child-payments', compact('student', 'payments'));
    }
}
