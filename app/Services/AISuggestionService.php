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
        $period = $data['period'] ?? 'month';

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

        // Get recent expenses for context
        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('created_at', [now()->subMonths(3), now()])
            ->get();

        $totalSpent = $expenses->sum('total') ?? 0;
        $summary = $expenses->groupBy('type')
            ->map(fn($group) => round($group->sum('total'), 2))
            ->toArray();

        // Improved Natural Prompt
        $prompt = "You are Luna, a friendly, warm, and witty personal finance advisor. You talk like a smart and helpful friend.\n\n";

        $prompt .= "User: {$user->name}\n";
        $prompt .= "Total spent in last 3 months: ₱" . number_format($totalSpent, 2) . "\n";
        $prompt .= "Category spending: " . json_encode($summary) . "\n\n";

        $prompt .= "Rules:\n";
        $prompt .= "- If the user says hi, hello, or just greets you, respond naturally and friendly. Do NOT give financial advice immediately.\n";
        $prompt .= "- Only give finance advice when the user asks for it.\n";
        $prompt .= "- Be conversational, concise, and natural.\n";
        $prompt .= "- Never say you are an AI unless asked.\n\n";

        $prompt .= "Previous conversation:\n";

        foreach ($messages as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Luna';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        $prompt .= "User: {$message}\n\n";
        $prompt .= "Respond in a natural, friendly way.";

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.8)        // ← Fixed here
                ->generate();

            $aiReply = $response->text ?? "Hey! How's it going?";

            // Save to history
            $messages[] = ['role' => 'user', 'content' => $message];
            $messages[] = ['role' => 'assistant', 'content' => $aiReply];

            $conversation->update([
                'messages' => $messages,
                'title' => $conversation->title ?: substr($message, 0, 50) . '...'
            ]);

            return [
                'success' => true,
                'response' => $aiReply,
                'conversation_id' => $conversation->id
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Sorry, I had trouble responding. Try again.'];
        }
    }
}
