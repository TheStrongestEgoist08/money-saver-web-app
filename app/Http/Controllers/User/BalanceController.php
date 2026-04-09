<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $userBalance = $user->balance;

        return view('balance.index' , compact('userBalance'));
    }

    public function addBalance(Request $request)
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user->balance += $validatedData['amount'];
            $user->save();

            DB::commit();

            return Redirect::route('user.balance')
                ->with('success', 'Balance added successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return Redirect::route('user.balance')
                ->with('error', 'Failed to add balance. Please try again.');
        }
    }
}
