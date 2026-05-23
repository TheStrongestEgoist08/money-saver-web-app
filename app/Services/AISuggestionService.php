<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AISuggestionService
{
    public function getSuggestions(array $data)
    {
        $user = Auth::user();
        $conversationId = $data['conversation_id'] ?? null;
        $userMessage = $data['message'] ?? null;
        $requestedPeriod = $data['period'] ?? 'month';

        if (!$userMessage) {
            return ['success' => false, 'message' => 'Message is required.'];
        }

        // === Enhanced Period Detection ===
        $periodInfo = $this->detectPeriodFromMessage($userMessage, $requestedPeriod);
        $dateRanges = $this->getDateRanges($periodInfo);

        // Fetch expenses for all relevant periods
        $allExpenses = collect();
        $periodSummaries = [];

        foreach ($dateRanges as $key => $range) {
            $expenses = Expense::where('user_id', $user->id)
                ->whereBetween('created_at', [$range['start'], $range['end']])
                ->get();

            $total = $expenses->sum('total') ?? 0;

            $summary = $expenses->groupBy('type')
                ->map(fn($group) => round($group->sum('total'), 2))
                ->toArray();

            $periodSummaries[$key] = [
                'period' => $range['label'],
                'total' => $total,
                'summary' => $summary,
                'start' => $range['start'],
                'end' => $range['end']
            ];

            $allExpenses = $allExpenses->merge($expenses);
        }

        // Get or create conversation
        $conversation = $conversationId
            ? Conversation::where('user_id', $user->id)->find($conversationId)
            : null;

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title'   => 'New Chat',
                'period'  => $periodInfo['main_period'],
                'messages' => []
            ]);
        }

        $messages = $conversation->messages ?? [];

        // === Build Smart Prompt ===
        $prompt = "You are Luna, a friendly, warm, and intelligent personal finance advisor.\n\n";
        $prompt .= "User: {$user->name}\n\n";

        $prompt .= "I have analyzed the following periods for you:\n\n";

        foreach ($periodSummaries as $info) {
            $prompt .= "📅 {$info['period']} ({$info['start']->format('M d, Y')} - {$info['end']->format('M d, Y')})\n";
            $prompt .= "Total Spent: ₱" . number_format($info['total'], 2) . "\n";
            $prompt .= "By Category: " . json_encode($info['summary'], JSON_PRETTY_PRINT) . "\n\n";
        }

        $prompt .= "Rules:\n";
        $prompt .= "- Always base your answers strictly on the data provided above.\n";
        $prompt .= "- Be conversational, warm, and honest.\n";
        $prompt .= "- If user asks for a period not shown, tell them honestly and offer to analyze it.\n";
        $prompt .= "- You can compare periods when relevant.\n\n";

        $prompt .= "Previous conversation:\n";
        foreach ($messages as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Luna';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        $prompt .= "User: {$userMessage}\n\n";
        $prompt .= "Respond naturally and helpfully as Luna.";

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.75)
                ->generate();

            $aiReply = $response->text ?? "Hey! I'd love to help you with your expenses.";

            // Save conversation
            $messages[] = ['role' => 'user', 'content' => $userMessage];
            $messages[] = ['role' => 'assistant', 'content' => $aiReply];

            $conversation->update([
                'messages' => $messages,
                'title'    => $conversation->title ?: substr($userMessage, 0, 50) . '...',
                'period'   => $periodInfo['main_period']
            ]);

            return [
                'success'        => true,
                'response'       => $aiReply,
                'conversation_id'=> $conversation->id,
                'period'         => $periodInfo['main_period']
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Sorry, I had trouble responding. Please try again.'];
        }
    }

    /**
     * Enhanced Period Detection
     */
    private function detectPeriodFromMessage(string $message, string $defaultPeriod): array
    {
        $message = strtolower(trim($message));

        $mainPeriod = $defaultPeriod;

        // Specific month names
        $months = [
            'january' => 1, 'jan' => 1,
            'february' => 2, 'feb' => 2,
            'march' => 3, 'mar' => 3,
            'april' => 4, 'apr' => 4,
            'may' => 5,
            'june' => 6, 'jun' => 6,
            'july' => 7, 'jul' => 7,
            'august' => 8, 'aug' => 8,
            'september' => 9, 'sep' => 9,
            'october' => 10, 'oct' => 10,
            'november' => 11, 'nov' => 11,
            'december' => 12, 'dec' => 12,
        ];

        foreach ($months as $name => $num) {
            if (str_contains($message, $name)) {
                return [
                    'main_period' => 'specific_month',
                    'month' => $num,
                    'year' => now()->year
                ];
            }
        }

        // Last month + month name (e.g., "last march")
        if (preg_match('/last\s+([a-z]+)/', $message, $matches)) {
            $monthName = $matches[1];
            foreach ($months as $name => $num) {
                if ($monthName === $name) {
                    return [
                        'main_period' => 'specific_month',
                        'month' => $num,
                        'year' => now()->year - (now()->month < $num ? 1 : 0)
                    ];
                }
            }
        }

        // This / Last / Previous
        if (str_contains($message, 'last month') || str_contains($message, 'previous month')) {
            return ['main_period' => 'last_month'];
        }
        if (str_contains($message, 'this month') || str_contains($message, 'current month')) {
            return ['main_period' => 'month'];
        }
        if (str_contains($message, 'last week') || str_contains($message, 'previous week')) {
            return ['main_period' => 'last_week'];
        }
        if (str_contains($message, 'this week')) {
            return ['main_period' => 'week'];
        }
        if (str_contains($message, 'last year')) {
            return ['main_period' => 'last_year'];
        }
        if (str_contains($message, 'this year')) {
            return ['main_period' => 'year'];
        }

        return ['main_period' => $defaultPeriod];
    }

    /**
     * Get one or more date ranges based on detected period
     */
    private function getDateRanges(array $periodInfo): array
    {
        $now = now();

        return match ($periodInfo['main_period']) {
            'specific_month' => [[
                'start' => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->startOfMonth(),
                'end'   => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->endOfMonth(),
                'label' => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->format('F Y')
            ]],

            'week' => [[
                'start' => $now->startOfWeek(),
                'end'   => $now->endOfWeek(),
                'label' => 'This Week'
            ]],

            'last_week' => [[
                'start' => $now->subWeek()->startOfWeek(),
                'end'   => $now->subWeek()->endOfWeek(),
                'label' => 'Last Week'
            ]],

            'month' => [[
                'start' => $now->startOfMonth(),
                'end'   => $now->endOfMonth(),
                'label' => 'This Month'
            ]],

            'last_month' => [[
                'start' => $now->subMonth()->startOfMonth(),
                'end'   => $now->subMonth()->endOfMonth(),
                'label' => 'Last Month'
            ]],

            '3months' => [[
                'start' => $now->subMonths(3)->startOfMonth(),
                'end'   => $now->endOfMonth(),
                'label' => 'Last 3 Months'
            ]],

            'year' => [[
                'start' => $now->startOfYear(),
                'end'   => $now->endOfYear(),
                'label' => 'This Year'
            ]],

            'last_year' => [[
                'start' => $now->subYear()->startOfYear(),
                'end'   => $now->subYear()->endOfYear(),
                'label' => 'Last Year'
            ]],

            default => [[
                'start' => $now->startOfMonth(),
                'end'   => $now->endOfMonth(),
                'label' => 'This Month'
            ]]
        };
    }
}
