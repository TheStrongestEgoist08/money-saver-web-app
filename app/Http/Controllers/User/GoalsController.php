<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class GoalsController extends Controller
{
    public function index() {
        $goals = Goal::where('user_id', Auth::id())
            ->latest()
            ->paginate(50);

        $goalCounts = Goal::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('goals.index', [
            'goals' => $goals,
            'goalCounts' => $goalCounts,
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

        $goalCounts = Goal::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('goals.index', [
            'goals' => $goals,
            'goalCounts' => $goalCounts,
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

    public function cancel(Request $request, $id)
    {
        $goal = Goal::where('user_id', Auth::id())
                    ->findOrFail($id);

        // Prevent cancelling already completed goals
        if ($goal->status === 'completed') {
            return redirect()
                ->back()
                ->with('error', 'Completed goals cannot be cancelled.');
        }

        // Prevent cancelling if already cancelled
        if ($goal->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('error', 'This goal is already cancelled.');
        }

        try {
            DB::beginTransaction();

            $goal->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return redirect()
                ->route('user.goals')
                ->with('success', 'Goal has been cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while cancelling the goal.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $goal = Goal::where('user_id', Auth::id())
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete image from storage if exists
            if ($goal->image) {
                Storage::disk('public')->delete($goal->image);
            }

            $goal->delete();

            DB::commit();

            return redirect()
                ->route('user.goals')
                ->with('success', 'Goal deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Something went wrong while deleting the goal.');
        }
    }
}
