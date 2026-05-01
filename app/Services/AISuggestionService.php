<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;

use Illuminate\Support\Facades\Log;

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

        $expenses = $query->whereBetween('created_at', [$start, $end])->get();

        if ($expenses->isEmpty()) {
            return [
                'success'     => true,
                'period'      => ucfirst($period),
                'suggestions' => "You haven't added any expenses yet. Start tracking to get AI suggestions! 💡"
            ];
        }

        $totalSpent = $expenses->sum('total') ?? 0;

        $summary = $expenses->groupBy('type')
            ->map(fn($items) => [
                'total_spent' => round($items->sum('total') ?? 0, 2),
                'count'       => $items->count(),
                'avg_per_item'=> round($items->avg('total') ?? 0, 2),
            ]);

        $topExpenses = $expenses->sortByDesc(fn($exp) => $exp->total ?? 0)->take(5);

        $prompt = "You are an expert personal finance advisor.\n\n";
        $prompt .= "User: {$user->name}\n";
        $prompt .= "Period: {$period} ({$start->format('Y-m-d')} to {$end->format('Y-m-d')})\n\n";
        $prompt .= "Total spent: ₱" . number_format($totalSpent, 2) . "\n\n";
        $prompt .= "Expense Breakdown:\n" . json_encode($summary, JSON_PRETTY_PRINT) . "\n\n";
        $prompt .= "Top 5 highest expenses:\n";

        foreach ($topExpenses as $exp) {
            $amount = number_format($exp->total ?? 0, 2);
            $prompt .= "- {$exp->expense_name} ({$exp->type}): ₱{$amount}\n";
        }

        $prompt .= "\nGive 5 practical, actionable suggestions to help this user save money.";

        try {
            Log::info('AI Suggestion: Starting request', [
                'user_id' => $user->id,
                'period' => $period,
                'total_spent' => $totalSpent,
            ]);

            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->generate();

            Log::info('AI Suggestion: Raw response received', [
                'text_preview' => substr($response->text ?? '', 0, 500),
            ]);

            # dd($response);

            return [
                'success'     => true,
                'period'      => ucfirst($period),
                'suggestions' => $response->text ?? 'No response from AI.',
            ];

        } catch (\Exception $e) {
            Log::error('AI Suggestion Error: ' . $e->getMessage());

            return [
                'success'     => false,
                'message'     => 'AI service is temporarily unavailable. Please try again later.',
                'error'       => config('app.debug') ? $e->getMessage() : null,
            ];
        }
    }
}
