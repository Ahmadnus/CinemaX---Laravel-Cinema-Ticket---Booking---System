<?php

namespace App\Http\Controllers;

use App\Services\MoodService;
use Illuminate\Http\Request;
use App\Services\OpenAIService;

class ChatController extends Controller
{


    protected $moodService;

    public function __construct(MoodService $moodService)
    {
        $this->moodService = $moodService;
    }

    public function suggestMovieGenre(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = $request->input('message');
        $genres = $this->moodService->getGenreFromMood($message);

        return response()->json([
            'recommended_genres' => $genres
        ]);
    }
}