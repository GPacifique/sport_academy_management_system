<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * Display a listing of groups.
     */
    public function index(Request $request)
    {
        $query = Group::with('branch', 'students', 'trainingSessions');

        // Filter by branch if provided
        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        // Search by name (case-insensitive)
        if ($request->has('search') && $request->search) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%']);
        }

        // Preserve query parameters in pagination links
        $groups = $query->paginate(15)->appends($request->query());
        $branches = Branch::all();

        return view('admin.groups.index', compact('groups', 'branches'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('admin.groups.create', compact('branches'));
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255|unique:groups,name,NULL,id,branch_id,' . $request->branch_id,
        ]);

        Group::create($validated);

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Group created successfully!');
    }

    /**
     * Display the specified group.
     */
    public function show(Group $group)
    {
        $group->load('branch', 'students', 'trainingSessions');
        return view('admin.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        $branches = Branch::all();
        $group->load('branch');
        return view('admin.groups.edit', compact('group', 'branches'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id . ',id,branch_id,' . $request->branch_id,
        ]);

        $group->update($validated);

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Group updated successfully!');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()
            ->route('admin.groups.index')
            ->with('success', 'Group deleted successfully!');
    }
}
