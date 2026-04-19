<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Facades\Ai;

class AISuggestionService
{
    public function getSuggestions(string $period = 'month')
    {
        $user = Auth::user();

        $query = Expense::where('user_id', $user->id);

        if ($period === 'month') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
        } elseif ($period === 'week') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
        } else {
            $start = now()->subMonths(3);
            $end = now();
        }

        $expenses = $query->whereBetween('created_at', [$start, $end])
            ->get();

        if ($expenses->isEmpty()) {
            return "You haven't added any expenses yet. Start tracking to get personalized AI suggestions!";
        }

        // Summarize by category (type)
        $summary = $expenses->groupBy('type')
            ->map(function ($items) {
                return [
                    'total_spent' => $items->sum('total'),
                    'count'       => $items->count(),
                    'avg_per_item'=> $items->avg('total'),
                ];
            });

        $totalSpent = $expenses->sum('total');
        $topExpenses = $expenses->sortByDesc('total')->take(5);

        $prompt = "You are an expert personal finance advisor.\n\n";
        $prompt .= "User: {$user->name}\n";
        $prompt .= "Period: {$period} ({$start->format('Y-m-d')} to {$end->format('Y-m-d')})\n\n";
        $prompt .= "Total spent: ₱" . number_format($totalSpent, 2) . "\n\n";
        $prompt .= "Expense Breakdown by Category:\n";
        $prompt .= json_encode($summary, JSON_PRETTY_PRINT) . "\n\n";

        $prompt .= "Top 5 highest expenses:\n";
        foreach ($topExpenses as $exp) {
            $prompt .= "- {$exp->expense_name} ({$exp->type}): ₱" . number_format($exp->total, 2) . "\n";
        }

        $prompt .= "\nGive me 5 practical, actionable suggestions to help this user save money and manage expenses better.";

        // Call AI (Gemini is recommended - free & powerful)
        $response = Ai::text()
            ->using('gemini', 'gemini-2.5-flash')   // or 'groq', 'llama3-70b'
            ->withPrompt($prompt)
            ->withMaxTokens(900)
            ->generate();

        return $response->text;
    }
}
