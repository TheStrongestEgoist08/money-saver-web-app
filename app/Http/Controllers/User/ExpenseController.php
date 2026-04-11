<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $expenses = Expense::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('expenses.index', [
            'expenses' => $expenses,
        ]);
    }

    public function addExpensess(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'expense_name' => ['required', 'string', 'max:50'],
            'type' => ['required'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $quantity = $validatedData['quantity'] ?? 1;

        // compute total
        $total = $quantity * $validatedData['price'];

        if ($user->balance < $total) {
            return Redirect::back()->with('Error', 'Insufficient Balance');
        }

        try {
            DB::beginTransaction();

            Expense::create([
                'user_id' => $user->id,
                'expense_name' => $validatedData['expense_name'],
                'type' => $validatedData['type'],
                'quantity' => $quantity,
                'price' => $validatedData['price'],
                'total' => $total,
                'description' => $validatedData['description'],
            ]);

            // deduct balance
            $user->decrement('balance', $total);

            DB::commit();

            return Redirect::back()->with('Success', 'Expense Added Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('Error', $e->getMessage());
        }
    }

public function addExpenses(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    $validatedData = $request->validate([
        'expenses' => ['required', 'array', 'min:1'],
        'expenses.*.expense_name' => ['required', 'string', 'max:50'],
        'expenses.*.type' => ['required', 'string', 'in:Food,Transportation,Bills,Clothing,Entertainment,Others'],
        'expenses.*.quantity' => ['nullable', 'integer', 'min:1'],
        'expenses.*.price' => ['required', 'numeric', 'min:0.01'],
        'expenses.*.description' => ['nullable', 'string', 'max:500'],
    ]);

    $totalAllExpenses = 0;
    $expensesToCreate = [];

    foreach ($validatedData['expenses'] as $expense) {
        $quantity = $expense['quantity'] ?? 1;
        $price    = $expense['price'];
        $total    = $quantity * $price;

        $totalAllExpenses += $total;

        $expensesToCreate[] = [
            'user_id'      => $user->id,
            'expense_name' => $expense['expense_name'],
            'type'         => $expense['type'],
            'quantity'     => $quantity,
            'price'        => $price,
            'total'        => $total,
            'description'  => $expense['description'] ?? null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }

    if ($user->balance < $totalAllExpenses) {
        return Redirect::back()->with('Error', 'Insufficient Balance');
    }

    try {
        DB::beginTransaction();

        Expense::insert($expensesToCreate);
        $user->decrement('balance', $totalAllExpenses);

        DB::commit();

        return Redirect::back()->with('Success', count($expensesToCreate) . ' Expense(s) Added Successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        return Redirect::back()->with('Error', 'Something went wrong. Please try again.');
    }
}
}
