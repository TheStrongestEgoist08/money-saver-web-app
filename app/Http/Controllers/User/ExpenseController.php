<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        return view('expenses.index');
    }

    public function addExpenses(Request $request) {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);
    }
}
