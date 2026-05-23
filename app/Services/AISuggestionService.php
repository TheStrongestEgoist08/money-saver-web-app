<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;
use Illuminate\Support\Facades\Log;

class AISuggestionService
{
    public function getSuggestions(array $data)
    {
        $user = Auth::user();
        $conversationId = $data['conversation_id'] ?? null;
        $message = $data['message'] ?? null;
        $period = $data['period'] ?? 'month';   // 'week', 'month', '3months', 'year'

        if (!$message) {
            return ['success' => false, 'message' => 'Message is required.'];
        }

        // Get or create conversation
        $conversation = $conversationId
            ? Conversation::where('user_id', $user->id)->find($conversationId)
            : null;

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title'   => 'New Chat',
                'period'  => $period,
                'messages' => []
            ]);
        }

        $messages = $conversation->messages ?? [];

        // === DYNAMIC PERIOD HANDLING ===
        $dateRange = $this->getDateRange($period);
        $start = $dateRange['start'];
        $end = $dateRange['end'];

        // Fetch expenses ONLY for the selected period
        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalSpent = $expenses->sum('total') ?? 0;

        $summary = $expenses->groupBy('type')
            ->map(fn($group) => round($group->sum('total'), 2))
            ->toArray();

        // Natural & Smart Prompt
        $prompt = "You are Luna, a friendly, warm, and intelligent personal finance advisor.\n\n";

        $prompt .= "User: {$user->name}\n";
        $prompt .= "Period: {$period} ({$start->format('M d, Y')} - {$end->format('M d, Y')})\n";
        $prompt .= "Total spent: ₱" . number_format($totalSpent, 2) . "\n";
        $prompt .= "Spending by category: " . json_encode($summary) . "\n\n";

        $prompt .= "Rules:\n";
        $prompt .= "- Respond naturally. If user says hi/hello, just greet back.\n";
        $prompt .= "- Only give financial advice when asked.\n";
        $prompt .= "- Be helpful, concise, and conversational.\n\n";

        $prompt .= "Previous conversation:\n";
        foreach ($messages as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Luna';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        $prompt .= "User: {$message}\n\n";
        $prompt .= "Respond appropriately.";

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.8)
                ->generate();

            $aiReply = $response->text ?? "Hey! How can I help you?";

            // Save history
            $messages[] = ['role' => 'user', 'content' => $message];
            $messages[] = ['role' => 'assistant', 'content' => $aiReply];

            $conversation->update([
                'messages' => $messages,
                'title' => $conversation->title ?: substr($message, 0, 50) . '...',
                'period' => $period
            ]);

            return [
                'success' => true,
                'response' => $aiReply,
                'conversation_id' => $conversation->id,
                'period' => $period
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Sorry, I had trouble responding.'];
        }
    }

    /**
     * Helper function to get date range based on period
     */
    private function getDateRange(string $period): array
    {
        return match ($period) {
            'week' => [
                'start' => now()->startOfWeek(),
                'end'   => now()->endOfWeek()
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end'   => now()->endOfMonth()
            ],
            '3months' => [
                'start' => now()->subMonths(3)->startOfMonth(),
                'end'   => now()
            ],
            'year' => [
                'start' => now()->startOfYear(),
                'end'   => now()->endOfYear()
            ],
            default => [  // fallback to month
                'start' => now()->startOfMonth(),
                'end'   => now()->endOfMonth()
            ]
        };
    }
}
