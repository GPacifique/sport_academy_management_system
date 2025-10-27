<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Group;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
class UsersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $roleFilter = $request->get('role');

        $users = User::withTrashed()->with(['roles', 'branch', 'group'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%");
                });
            })
            ->when($roleFilter, function ($query) use ($roleFilter) {
                $query->whereHas('roles', function ($q) use ($roleFilter) {
                    $q->where('name', $roleFilter);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Load all roles for the web guard (deduped)
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'q' => $q,
            'roleFilter' => $roleFilter,
        ]);
    }

    // Show create form
    public function create()
    {
        // Ensure core branches exist so the dropdown is populated (idempotent)
        foreach ([
            ['name' => 'MASAKA', 'code' => 'MSK'],
            ['name' => 'KICUKIRO', 'code' => 'KCK'],
            ['name' => 'MWANZA', 'code' => 'MWZ'],
        ] as $b) {
            Branch::firstOrCreate(['name' => $b['name']], ['code' => $b['code']]);
        }

        // Load all roles for the web guard (deduped)
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'branches', 'groups'));
    }

    // Store a new user and assign roles
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'roles' => ['nullable','array'],
            'roles.*' => ['string', Rule::exists('roles','name')],
            'branch_id' => ['nullable', Rule::exists('branches','id')],
            'group_id' => ['nullable', Rule::exists('groups','id')->where(function($q) use ($request) {
                if ($request->filled('branch_id')) { $q->where('branch_id', $request->input('branch_id')); }
            })],
            'password' => ['nullable','string','min:8'],
        ]);

    $tempPassword = $data['password'] ?? Str::random(16);
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (array_key_exists('branch_id', $data)) { $user->branch_id = $data['branch_id']; }
        if (array_key_exists('group_id', $data)) { $user->group_id = $data['group_id']; }
    $user->password = Hash::make($tempPassword);
        $user->save();

        $user->syncRoles($data['roles'] ?? []);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.created',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => ['email' => $user->email, 'roles' => $data['roles'] ?? []],
        ]);

        // Optionally send reset link
        if (!$data['password'] && $request->boolean('send_reset', true)) {
            Password::sendResetLink(['email' => $user->email]);
        }

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    // Show edit form
    public function edit(User $user)
    {
        // Load all roles for the web guard (deduped)
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();
        $branches = Branch::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        return view('admin.users.edit', compact('user','roles','branches','groups'));
    }

    // Full update via PUT
    public function updateFull(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'roles' => ['nullable','array'],
            'roles.*' => ['string', Rule::exists('roles','name')],
            'branch_id' => ['nullable', Rule::exists('branches','id')],
            'group_id' => ['nullable', Rule::exists('groups','id')->where(function($q) use ($request) {
                if ($request->filled('branch_id')) { $q->where('branch_id', $request->input('branch_id')); }
            })],
            'password' => ['nullable','string','min:8'],
        ]);

        $dirty = false;
        if ($user->name !== $data['name']) { $user->name = $data['name']; $dirty = true; }
        if ($user->email !== $data['email']) { $user->email = $data['email']; $dirty = true; }
        if (array_key_exists('branch_id', $data) && $user->branch_id !== ($data['branch_id'] ?? null)) { $user->branch_id = $data['branch_id'] ?? null; $dirty = true; }
        if (array_key_exists('group_id', $data) && $user->group_id !== ($data['group_id'] ?? null)) { $user->group_id = $data['group_id'] ?? null; $dirty = true; }
    if (!empty($data['password'])) { $user->password = Hash::make($data['password']); $dirty = true; }
    if ($dirty) { $user->save(); }

        if (array_key_exists('roles', $data)) {
            $user->syncRoles($data['roles'] ?? []);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.updated',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => ['email' => $user->email, 'roles' => $data['roles'] ?? null],
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    // Inline/partial update via PATCH (e.g., roles or branch/group)
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['nullable','array'],
            'roles.*' => ['string', Rule::exists('roles','name')],
            'branch_id' => ['nullable', Rule::exists('branches','id')],
            'group_id' => ['nullable', Rule::exists('groups','id')->where(function($q) use ($request) {
                if ($request->filled('branch_id')) { $q->where('branch_id', $request->input('branch_id')); }
            })],
        ]);

        $changed = false;
        if (array_key_exists('roles', $data)) {
            $user->syncRoles($data['roles'] ?? []);
            $changed = true;
        }
        if (array_key_exists('branch_id', $data) && $user->branch_id !== ($data['branch_id'] ?? null)) { $user->branch_id = $data['branch_id'] ?? null; $changed = true; }
        if (array_key_exists('group_id', $data) && $user->group_id !== ($data['group_id'] ?? null)) { $user->group_id = $data['group_id'] ?? null; $changed = true; }
        if ($changed) { $user->save(); }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.patched',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => [
                'roles' => $data['roles'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
                'group_id' => $data['group_id'] ?? null,
            ],
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Changes saved.');
    }

    // Soft delete user with audit log
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors('You cannot delete your own account.');
        }
        $user->delete();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.deleted',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => ['email' => $user->email],
        ]);
        return redirect()->route('admin.users.index')->with('status', 'User deactivated.');
    }

    // Restore soft deleted user with audit log
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.restored',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => ['email' => $user->email],
        ]);
        return redirect()->route('admin.users.index')->with('status', 'User restored.');
    }

    // Send password reset link to user
    public function sendReset(User $user)
    {
        Password::sendResetLink(['email' => $user->email]);
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.password_reset_link_sent',
            'target_type' => User::class,
            'target_id' => $user->id,
            'meta' => ['email' => $user->email],
        ]);
        return redirect()->back()->with('status', 'Password reset link sent.');
    }
}

