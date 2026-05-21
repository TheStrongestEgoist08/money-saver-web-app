<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class GoalsController extends Controller
{
    public function index() {
        $goals = Goal::where('user_id', Auth::id())
            ->latest()
            ->paginate(50);

        return view('goals.index', [
            'goals' => $goals,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goal_name'     => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'target_date'   => 'nullable|date|after:today',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'user_id'       => auth()->id(),
            'goal_name'     => $validated['goal_name'],
            'target_amount' => $validated['target_amount'],
            'target_date'   => $validated['target_date'] ?? null,
            'description'   => $validated['description'] ?? null,
            'saved_amount'  => 0,
            'status'        => 'active',
        ];

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('goals', 'public');
            $data['image'] = $imagePath;
        }

        Goal::create($data);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal created successfully!');
    }
}
