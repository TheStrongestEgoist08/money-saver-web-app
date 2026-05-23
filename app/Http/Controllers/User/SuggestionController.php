<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AISuggestionService;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;

class SuggestionController extends Controller
{
    protected $aiService;

    public function __construct(AISuggestionService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('suggestions.index');
    }

    public function getConversations()
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->select('id', 'title', 'updated_at')
            ->latest()
            ->get();

        return response()->json($conversations);
    }

    public function showConversation($id)
    {
        $conversation = Conversation::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'id' => $conversation->id,
            'title' => $conversation->title,
            'messages' => $conversation->messages ?? []
        ]);
    }

    public function getSuggestions(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'nullable|integer',
            'period' => 'nullable|in:month,week,3months'
        ]);

        $result = $this->aiService->getSuggestions($data);

        return response()->json($result);
    }

    public function deleteConversation($id)
    {
        $conversation = Conversation::where('user_id', Auth::id())
            ->findOrFail($id);

        $conversation->delete();

        return response()->json(['success' => true]);
    }
}
