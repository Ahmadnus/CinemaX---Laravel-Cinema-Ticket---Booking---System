<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieReviewRequest;
use App\Http\Requests\UpdateMovieReviewRequest;
use App\Http\Resources\MovieReviewResource;
use App\Models\MovieReview;
use App\Services\MovieReviewService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


    class MovieReviewController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(MovieReviewService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $reviews = $this->service->getAll();
        return $this->successResponse(MovieReviewResource::collection($reviews));
    }

    public function store(StoreMovieReviewRequest $request)
    {
        $review = $this->service->create($request->validated());
        return $this->successResponse(new MovieReviewResource($review), 'Review created successfully', 201);
    }

    public function show(MovieReview $movieReview)
    {
        return $this->successResponse(new MovieReviewResource($movieReview->load('user', 'movie')));
    }

    public function update(UpdateMovieReviewRequest $request, MovieReview $movieReview)
    {
        $updated = $this->service->update($movieReview, $request->validated());
        return $this->successResponse(new MovieReviewResource($updated), 'Review updated successfully');
    }

    public function destroy(MovieReview $movieReview)
    {
        $this->service->delete($movieReview);
        return $this->successResponse(null, 'Review deleted successfully');
    }
}

