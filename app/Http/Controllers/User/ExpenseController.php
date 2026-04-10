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

        $user_id = Auth::id();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required'],
            'amount' => ['required', 'numeric', 'min:1', 'max:50000'],
            'description' => ['required', ''],
        ]);


    }
}
