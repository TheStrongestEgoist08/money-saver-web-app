<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AISuggestionService;

class SuggestionController extends Controller
{
    public function index()
    {
        return view('suggestions.index');
    }

    public function aiSuggestions()
    {
        try {
            $suggestions = app(AISuggestionService::class)->getSuggestions('month');

            return response()->json([
                'success'     => true,
                'suggestions' => $suggestions,
                'period'      => 'This Month'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get AI suggestions. Please try again later.'
            ], 500);
        }
    }
}
