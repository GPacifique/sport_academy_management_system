<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    // Safety: auth middleware should ensure $user is present
    if (!$user) {
        return redirect()->route('login');
    }

    // Resolve user roles without relying on IDE-unknown trait methods
    $roles = DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->where('model_has_roles.model_id', $user->id)
        ->where('model_has_roles.model_type', get_class($user))
        ->pluck('roles.name')
        ->all();

    // Super Admin has highest priority - redirect to admin dashboard
    if (in_array('super-admin', $roles, true)) {
        return redirect()->route('admin.dashboard');
    }

    if (in_array('admin', $roles, true)) {
        return redirect()->route('admin.dashboard');
    }

    if (in_array('coach', $roles, true)) {
        return redirect()->route('coach.dashboard');
    }

    if (in_array('accountant', $roles, true)) {
        return redirect()->route('accountant.dashboard');
    }

    if (in_array('parent', $roles, true)) {
        return redirect()->route('parent.dashboard');
    }

    // Default for regular users
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Example protected route - requires auth and admin role
Route::get('/admin-only', function () {
    return 'Hello admin — you have access.';
})->middleware(['auth', 'role:admin']);

// Admin and User dashboards
Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    // User role management
    Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UsersController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UsersController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'updateFull'])->name('admin.users.updateFull');
    Route::patch('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/send-reset', [\App\Http\Controllers\Admin\UsersController::class, 'sendReset'])->name('admin.users.sendReset');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/restore', [\App\Http\Controllers\Admin\UsersController::class, 'restore'])->name('admin.users.restore');
    // Session management
    Route::get('/sessions', [\App\Http\Controllers\Admin\SessionsController::class, 'index'])->name('admin.sessions.index');
    Route::get('/sessions/create', [\App\Http\Controllers\Admin\SessionsController::class, 'create'])->name('admin.sessions.create');
    Route::post('/sessions', [\App\Http\Controllers\Admin\SessionsController::class, 'store'])->name('admin.sessions.store');
    Route::get('/sessions/{session}/edit', [\App\Http\Controllers\Admin\SessionsController::class, 'edit'])->name('admin.sessions.edit');
    Route::put('/sessions/{session}', [\App\Http\Controllers\Admin\SessionsController::class, 'update'])->name('admin.sessions.update');
    Route::delete('/sessions/{session}', [\App\Http\Controllers\Admin\SessionsController::class, 'destroy'])->name('admin.sessions.destroy');

    // Students
    Route::get('/students', [\App\Http\Controllers\Admin\StudentsController::class, 'index'])->name('admin.students.index');
    Route::get('/students/{student}', [\App\Http\Controllers\Admin\StudentsController::class, 'show'])->name('admin.students.show');
    Route::get('/students/create', [\App\Http\Controllers\Admin\StudentsController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [\App\Http\Controllers\Admin\StudentsController::class, 'store'])->name('admin.students.store');
    Route::get('/students/{student}/edit', [\App\Http\Controllers\Admin\StudentsController::class, 'edit'])->name('admin.students.edit');
    Route::put('/students/{student}', [\App\Http\Controllers\Admin\StudentsController::class, 'update'])->name('admin.students.update');
    Route::delete('/students/{student}', [\App\Http\Controllers\Admin\StudentsController::class, 'destroy'])->name('admin.students.destroy');
    Route::get('/students/import/photos', [\App\Http\Controllers\Admin\StudentsController::class, 'importForm'])->name('admin.students.importForm');
    Route::post('/students/import/photos', [\App\Http\Controllers\Admin\StudentsController::class, 'importProcess'])->name('admin.students.importProcess');

    // Subscription Plans
    Route::get('/plans', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'index'])->name('admin.plans.index');
    Route::get('/plans/create', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'create'])->name('admin.plans.create');
    Route::post('/plans', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'store'])->name('admin.plans.store');
    Route::get('/plans/{plan}/edit', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'edit'])->name('admin.plans.edit');
    Route::put('/plans/{plan}', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'update'])->name('admin.plans.update');
    Route::delete('/plans/{plan}', [\App\Http\Controllers\Admin\SubscriptionPlansController::class, 'destroy'])->name('admin.plans.destroy');

    // Expenses
    Route::get('/expenses', [\App\Http\Controllers\Admin\ExpensesController::class, 'index'])->name('admin.expenses.index');
    Route::get('/expenses/create', [\App\Http\Controllers\Admin\ExpensesController::class, 'create'])->name('admin.expenses.create');
    Route::post('/expenses', [\App\Http\Controllers\Admin\ExpensesController::class, 'store'])->name('admin.expenses.store');
    Route::get('/expenses/{expense}/edit', [\App\Http\Controllers\Admin\ExpensesController::class, 'edit'])->name('admin.expenses.edit');
    Route::put('/expenses/{expense}', [\App\Http\Controllers\Admin\ExpensesController::class, 'update'])->name('admin.expenses.update');
    Route::patch('/expenses/{expense}/approve', [\App\Http\Controllers\Admin\ExpensesController::class, 'approve'])->name('admin.expenses.approve');
    Route::patch('/expenses/{expense}/reject', [\App\Http\Controllers\Admin\ExpensesController::class, 'reject'])->name('admin.expenses.reject');
    Route::patch('/expenses/{expense}/mark-paid', [\App\Http\Controllers\Admin\ExpensesController::class, 'markPaid'])->name('admin.expenses.mark-paid');
    Route::delete('/expenses/{expense}', [\App\Http\Controllers\Admin\ExpensesController::class, 'destroy'])->name('admin.expenses.destroy');

    // Equipment Management
    Route::get('/equipment', [\App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('admin.equipment.index');
    Route::get('/equipment/create', [\App\Http\Controllers\Admin\EquipmentController::class, 'create'])->name('admin.equipment.create');
    Route::post('/equipment', [\App\Http\Controllers\Admin\EquipmentController::class, 'store'])->name('admin.equipment.store');
    Route::get('/equipment/{equipment}', [\App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('admin.equipment.show');
    Route::get('/equipment/{equipment}/edit', [\App\Http\Controllers\Admin\EquipmentController::class, 'edit'])->name('admin.equipment.edit');
    Route::put('/equipment/{equipment}', [\App\Http\Controllers\Admin\EquipmentController::class, 'update'])->name('admin.equipment.update');
    Route::delete('/equipment/{equipment}', [\App\Http\Controllers\Admin\EquipmentController::class, 'destroy'])->name('admin.equipment.destroy');

    // Branches Management
    Route::get('/branches', [\App\Http\Controllers\Admin\BranchesController::class, 'index'])->name('admin.branches.index');
    Route::get('/branches/create', [\App\Http\Controllers\Admin\BranchesController::class, 'create'])->name('admin.branches.create');
    Route::post('/branches', [\App\Http\Controllers\Admin\BranchesController::class, 'store'])->name('admin.branches.store');
    Route::get('/branches/{branch}', [\App\Http\Controllers\Admin\BranchesController::class, 'show'])->name('admin.branches.show');
    Route::get('/branches/{branch}/edit', [\App\Http\Controllers\Admin\BranchesController::class, 'edit'])->name('admin.branches.edit');
    Route::put('/branches/{branch}', [\App\Http\Controllers\Admin\BranchesController::class, 'update'])->name('admin.branches.update');
    Route::delete('/branches/{branch}', [\App\Http\Controllers\Admin\BranchesController::class, 'destroy'])->name('admin.branches.destroy');

    // Groups Management
    Route::get('/groups', [\App\Http\Controllers\Admin\GroupsController::class, 'index'])->name('admin.groups.index');
    Route::get('/groups/create', [\App\Http\Controllers\Admin\GroupsController::class, 'create'])->name('admin.groups.create');
    Route::post('/groups', [\App\Http\Controllers\Admin\GroupsController::class, 'store'])->name('admin.groups.store');
    Route::get('/groups/{group}', [\App\Http\Controllers\Admin\GroupsController::class, 'show'])->name('admin.groups.show');
    Route::get('/groups/{group}/edit', [\App\Http\Controllers\Admin\GroupsController::class, 'edit'])->name('admin.groups.edit');
    Route::put('/groups/{group}', [\App\Http\Controllers\Admin\GroupsController::class, 'update'])->name('admin.groups.update');
    Route::delete('/groups/{group}', [\App\Http\Controllers\Admin\GroupsController::class, 'destroy'])->name('admin.groups.destroy');
});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'index'])->name('user.dashboard');
});

// Role-based dashboards
Route::middleware(['auth', 'role:coach|admin|super-admin'])->prefix('coach')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CoachController::class, 'index'])->name('coach.dashboard');
    // Attendance management
    Route::get('/attendance', [\App\Http\Controllers\Coach\AttendanceController::class, 'index'])->name('coach.attendance.index');
    Route::get('/attendance/session/{session}', [\App\Http\Controllers\Coach\AttendanceController::class, 'show'])->name('coach.attendance.show');
    Route::post('/attendance/session/{session}', [\App\Http\Controllers\Coach\AttendanceController::class, 'store'])->name('coach.attendance.store');
    // Session scheduling
    Route::get('/sessions', [\App\Http\Controllers\Coach\SessionController::class, 'index'])->name('coach.sessions.index');
    Route::get('/sessions/create', [\App\Http\Controllers\Coach\SessionController::class, 'create'])->name('coach.sessions.create');
    Route::post('/sessions', [\App\Http\Controllers\Coach\SessionController::class, 'store'])->name('coach.sessions.store');
    // Students (coach scope)
    Route::get('/students', [\App\Http\Controllers\Coach\StudentsController::class, 'index'])->name('coach.students.index');
    Route::get('/students/{student}', [\App\Http\Controllers\Coach\StudentsController::class, 'show'])->name('coach.students.show');
    Route::get('/students/{student}/attendance', [\App\Http\Controllers\Coach\StudentsController::class, 'attendance'])->name('coach.students.attendance');
    // Equipment (view only)
    Route::get('/equipment', [\App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('coach.equipment.index');
    Route::get('/equipment/{equipment}', [\App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('coach.equipment.show');
});

Route::middleware(['auth', 'role:accountant|admin|super-admin'])->prefix('accountant')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AccountantController::class, 'index'])->name('accountant.dashboard');

    // Subscriptions
    Route::get('/subscriptions', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'index'])->name('accountant.subscriptions.index');
    Route::get('/subscriptions/create', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'create'])->name('accountant.subscriptions.create');
    Route::post('/subscriptions', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'store'])->name('accountant.subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'edit'])->name('accountant.subscriptions.edit');
    Route::put('/subscriptions/{subscription}', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'update'])->name('accountant.subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [\App\Http\Controllers\Accountant\SubscriptionsController::class, 'destroy'])->name('accountant.subscriptions.destroy');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\Accountant\PaymentsController::class, 'index'])->name('accountant.payments.index');
    Route::get('/payments/create', [\App\Http\Controllers\Accountant\PaymentsController::class, 'create'])->name('accountant.payments.create');
    Route::post('/payments', [\App\Http\Controllers\Accountant\PaymentsController::class, 'store'])->name('accountant.payments.store');
    Route::get('/payments/export', [\App\Http\Controllers\Accountant\PaymentsController::class, 'export'])->name('accountant.payments.export');

    // Invoices
    Route::get('/invoices', [\App\Http\Controllers\Accountant\InvoicesController::class, 'index'])->name('accountant.invoices.index');
    Route::get('/invoices/create', [\App\Http\Controllers\Accountant\InvoicesController::class, 'create'])->name('accountant.invoices.create');
    Route::post('/invoices', [\App\Http\Controllers\Accountant\InvoicesController::class, 'store'])->name('accountant.invoices.store');
    Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\Accountant\InvoicesController::class, 'edit'])->name('accountant.invoices.edit');
    Route::put('/invoices/{invoice}', [\App\Http\Controllers\Accountant\InvoicesController::class, 'update'])->name('accountant.invoices.update');
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\Accountant\InvoicesController::class, 'destroy'])->name('accountant.invoices.destroy');

    // Equipment (view only)
    Route::get('/equipment', [\App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('accountant.equipment.index');
    Route::get('/equipment/{equipment}', [\App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('accountant.equipment.show');
});

Route::middleware(['auth', 'role:parent|admin|super-admin'])->prefix('parent')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParentController::class, 'index'])->name('parent.dashboard');
    Route::get('/child/{student}/payments', [\App\Http\Controllers\ParentController::class, 'childPayments'])->name('parent.child-payments');
});

// Payment gateway webhooks (public routes - no auth)
Route::post('/webhooks/flutterwave', [\App\Http\Controllers\WebhooksController::class, 'flutterwave'])->name('webhooks.flutterwave');
Route::post('/webhooks/stripe', [\App\Http\Controllers\WebhooksController::class, 'stripe'])->name('webhooks.stripe');

require __DIR__.'/auth.php';

// Local-only helper to grant admin role to current user for development
if (app()->environment('local')) {
    Route::middleware(['auth'])->get('/dev/make-me-admin', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) abort(403);
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');
        }
        return redirect()->route('admin.dashboard')->with('status', 'You are now an admin');
    })->name('dev.makeMeAdmin');
}
