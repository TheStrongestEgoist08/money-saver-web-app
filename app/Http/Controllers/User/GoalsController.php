<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public function filter(Request $request)
    {
        $query = Goal::where('user_id', Auth::id());

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('goal_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Type / Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $goals = $query->latest()
                       ->paginate(50)
                       ->appends($request->query());

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
            ->route('user.goals')
            ->with('success', 'Goal created successfully!');
    }

    public function addMoney(Request $request)
    {
        $validated = $request->validate([
            'goal_id' => 'required|exists:goals,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $goal = Goal::where('user_id', Auth::id())
                    ->findOrFail($validated['goal_id']);

        try {
            DB::beginTransaction();

            $newSavedAmount = $goal->saved_amount + $validated['amount'];

            $goal->update([
                'saved_amount' => $newSavedAmount,
                'progress'     => min(100, round(($newSavedAmount / $goal->target_amount) * 100, 2)),
            ]);

            // Auto-complete if target is reached
            if ($newSavedAmount >= $goal->target_amount && $goal->status !== 'Completed') {
                $goal->update([
                    'status' => 'Completed',
                ]);
            }

            DB::commit();

            return redirect()
                ->route('user.goals')
                ->with('success', 'Money added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}
