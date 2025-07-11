<?php

namespace App\Services;

use App\Models\MovieReview;
use Illuminate\Support\Facades\Auth;

class MovieReviewService
{
    public function create(array $data): MovieReview
    {
        $data['user_id'] = Auth::id();
        return MovieReview::create($data);
    }

    public function update(MovieReview $review, array $data): MovieReview
    {
        $review->update($data);
        return $review;
    }

    public function delete(MovieReview $review): bool
    {
        return $review->delete();
    }

    public function getAll()
    {
        return MovieReview::with(['user', 'movie'])->get();
    }

    public function getById($id): ?MovieReview
    {
        return MovieReview::with(['user', 'movie'])->findOrFail($id);
    }
}