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

        // === Detect Period from User's Message ===
        $detectedPeriod = $this->detectPeriodFromMessage($userMessage, $requestedPeriod);
        $dateRange = $this->getDateRange($detectedPeriod);
        $start = $dateRange['start'];
        $end = $dateRange['end'];

        // Fetch expenses for the detected period
        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalSpent = $expenses->sum('total') ?? 0;

        $summary = $expenses->groupBy('type')
            ->map(fn($group) => round($group->sum('total'), 2))
            ->toArray();

        // Get or create conversation
        $conversation = $conversationId
            ? Conversation::where('user_id', $user->id)->find($conversationId)
            : null;

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title'   => 'New Chat',
                'period'  => $detectedPeriod,
                'messages' => []
            ]);
        }

        $messages = $conversation->messages ?? [];

        // === Build Smart Prompt ===
        $prompt = "You are Luna, a friendly, warm, and intelligent personal finance advisor.\n\n";

        $prompt .= "User: {$user->name}\n";
        $prompt .= "Current Period Analyzed: {$detectedPeriod} ({$start->format('M d, Y')} - {$end->format('M d, Y')})\n";
        $prompt .= "Total spent in this period: ₱" . number_format($totalSpent, 2) . "\n";
        $prompt .= "Spending by category: " . json_encode($summary, JSON_PRETTY_PRINT) . "\n\n";

        $prompt .= "Rules:\n";
        $prompt .= "- Always base your answer on the data provided above.\n";
        $prompt .= "- If the user asks about a different period, acknowledge it and tell them you're showing data for the detected period.\n";
        $prompt .= "- Be helpful, conversational, and honest.\n";
        $prompt .= "- Do not make up expense numbers.\n\n";

        $prompt .= "Previous conversation:\n";
        foreach ($messages as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Luna';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        $prompt .= "User: {$userMessage}\n\n";
        $prompt .= "Respond naturally and helpfully.";

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.75)
                ->generate();

            $aiReply = $response->text ?? "Hey! How can I help you with your expenses?";

            // Save to conversation history
            $messages[] = ['role' => 'user', 'content' => $userMessage];
            $messages[] = ['role' => 'assistant', 'content' => $aiReply];

            $conversation->update([
                'messages' => $messages,
                'title'    => $conversation->title ?: substr($userMessage, 0, 45) . '...',
                'period'   => $detectedPeriod
            ]);

            return [
                'success'        => true,
                'response'       => $aiReply,
                'conversation_id'=> $conversation->id,
                'period'         => $detectedPeriod
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Sorry, I had trouble responding. Please try again.'];
        }
    }

    /**
     * Detect period from user's natural language message
     */
    private function detectPeriodFromMessage(string $message, string $defaultPeriod): string
    {
        $message = strtolower($message);

        // Specific months
        if (preg_match('/january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sep|october|oct|november|nov|december|dec/', $message)) {
            return 'month'; // We'll handle specific month later if needed
        }

        if (str_contains($message, 'last month') || str_contains($message, 'previous month')) {
            return 'last_month';
        }
        if (str_contains($message, 'this month') || str_contains($message, 'current month')) {
            return 'month';
        }
        if (str_contains($message, 'last week') || str_contains($message, 'previous week')) {
            return 'last_week';
        }
        if (str_contains($message, 'this week') || str_contains($message, 'current week')) {
            return 'week';
        }
        if (str_contains($message, 'last year') || str_contains($message, 'previous year')) {
            return 'last_year';
        }
        if (str_contains($message, 'this year') || str_contains($message, 'current year')) {
            return 'year';
        }
        if (str_contains($message, 'past 3 months') || str_contains($message, 'last 3 months')) {
            return '3months';
        }

        // Default fallback
        return $defaultPeriod;
    }

    /**
     * Updated getDateRange with more options
     */
    private function getDateRange(string $period): array
    {
        return match ($period) {
            'week' => [
                'start' => now()->startOfWeek(),
                'end'   => now()->endOfWeek()
            ],
            'last_week' => [
                'start' => now()->subWeek()->startOfWeek(),
                'end'   => now()->subWeek()->endOfWeek()
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end'   => now()->endOfMonth()
            ],
            'last_month' => [
                'start' => now()->subMonth()->startOfMonth(),
                'end'   => now()->subMonth()->endOfMonth()
            ],
            '3months' => [
                'start' => now()->subMonths(3)->startOfMonth(),
                'end'   => now()->endOfMonth()
            ],
            'year' => [
                'start' => now()->startOfYear(),
                'end'   => now()->endOfYear()
            ],
            'last_year' => [
                'start' => now()->subYear()->startOfYear(),
                'end'   => now()->subYear()->endOfYear()
            ],
            default => [
                'start' => now()->startOfMonth(),
                'end'   => now()->endOfMonth()
            ]
        };
    }
}
