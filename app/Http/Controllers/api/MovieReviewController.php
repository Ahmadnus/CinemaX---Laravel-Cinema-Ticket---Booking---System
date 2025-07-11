<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieReviewRequest;
use App\Http\Requests\UpdateMovieReviewRequest;
use App\Models\MovieReview;
use App\Services\MovieReviewService;
use Illuminate\Http\Request;

class MovieReviewController extends Controller
{
    protected $service;

    public function __construct(MovieReviewService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store(StoreMovieReviewRequest $request)
    {
        $review = $this->service->create($request->validated());
        return response()->json($review, 201);
    }

    public function show(MovieReview $movieReview)
    {
        return response()->json($movieReview->load('user', 'movie'));
    }

    public function update(UpdateMovieReviewRequest $request, MovieReview $movieReview)
    {
        $updated = $this->service->update($movieReview, $request->validated());
        return response()->json($updated);
    }

    public function destroy(MovieReview $movieReview)
    {
        $this->service->delete($movieReview);
        return response()->json(['message' => 'Review deleted']);
    }

}
