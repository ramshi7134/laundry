<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;

        $query = Expense::where('branch_id', $branchId)->with('creator:id,name');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->orderByDesc('date')->paginate(20);

        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'category'    => 'required|in:' . implode(',', Expense::categories()),
            'description' => 'nullable|string',
            'reference'   => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $expense = Expense::create([
            'branch_id'   => $request->user()->branch_id,
            'created_by'  => $request->user()->id,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
            'reference'   => $request->reference,
            'date'        => $request->date,
        ]);

        return response()->json($expense->load('creator:id,name'), 201);
    }

    public function show(string $id)
    {
        $expense = Expense::with('creator:id,name')->find($id);
        if (!$expense) return response()->json(['message' => 'Expense not found'], 404);

        return response()->json($expense);
    }

    public function update(Request $request, string $id)
    {
        $expense = Expense::find($id);
        if (!$expense) return response()->json(['message' => 'Expense not found'], 404);

        $request->validate([
            'amount'      => 'sometimes|required|numeric|min:0.01',
            'category'    => 'sometimes|required|in:' . implode(',', Expense::categories()),
            'description' => 'nullable|string',
            'reference'   => 'nullable|string',
            'date'        => 'sometimes|required|date',
        ]);

        $expense->update($request->only(['amount', 'category', 'description', 'reference', 'date']));

        return response()->json($expense->fresh()->load('creator:id,name'));
    }

    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        if (!$expense) return response()->json(['message' => 'Expense not found'], 404);

        $expense->delete();

        return response()->json(['message' => 'Expense deleted']);
    }

    public function categories()
    {
        return response()->json(Expense::categories());
    }
}
