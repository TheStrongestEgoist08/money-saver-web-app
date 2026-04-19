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

    public function aiSuggestions(AISuggestionService $service)
    {
        $period = request('period', 'month');

        $result = $service->getSuggestions($period);

        return response()->json($result);
    }
}
