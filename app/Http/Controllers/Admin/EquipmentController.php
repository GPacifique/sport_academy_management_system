<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Branch;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('branch');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $equipment = $query->orderBy('name')->paginate(20);
        $branches = Branch::all();

        return view('admin.equipment.index', compact('equipment', 'branches'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.equipment.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:balls,nets,training,safety,facility,other',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        Equipment::create($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment added successfully!');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('branch');
        return view('admin.equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $branches = Branch::all();
        return view('admin.equipment.edit', compact('equipment', 'branches'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:balls,nets,training,safety,facility,other',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:available,in_use,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment updated successfully!');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment deleted successfully!');
    }
}
