<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchesController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index(Request $request)
    {
        $query = Branch::withCount('groups', 'users', 'students');

        // Search by name or code
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $branches = $query->paginate(15);

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);

        Branch::create($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch created successfully with default groups (A-F)!');
    }

    /**
     * Display the specified branch.
     */
    public function show(Branch $branch)
    {
        $branch->load('groups', 'users', 'students');
        $branch->loadCount('groups', 'users', 'students');
        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);

        $branch->update($validated);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch updated successfully!');
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(Branch $branch)
    {
        // Check if branch has related data
        if ($branch->users()->exists() || $branch->students()->exists()) {
            return redirect()
                ->route('admin.branches.index')
                ->with('error', 'Cannot delete branch with associated users or students!');
        }

        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully!');
    }
}
