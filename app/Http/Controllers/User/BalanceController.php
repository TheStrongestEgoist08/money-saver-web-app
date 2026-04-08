<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

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
}
