<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieService
{
    /**
     * Create a new class instance.
     */
    public function createMovie(array $data): Movie
{
    $movie = Movie::create($data);

    // Poster from URL
    if (!empty($data['poster_url'])) {
        $movie->addMediaFromUrl($data['poster_url'])->toMediaCollection('posters');
    }

    // Trailer from URL (لو كان عندك رابط فيديو مثلاً)
    if (!empty($data['trailer_url'])) {
        $movie->addMediaFromUrl($data['trailer_url'])->toMediaCollection('trailers');
    }

    return $movie;
}
    public function updateMovie(int $id, array $data): Movie
    {
        $movie = Movie::findOrFail($id);
        $movie->update($data);

        // Replace poster if new one provided
        if (!empty($data['poster_url']) && file_exists($data['poster_url'])) {
            $movie->clearMediaCollection('posters');
            $movie->addMedia($data['poster_url'])->toMediaCollection('posters');
        }

        // Replace trailer if new one provided
        if (!empty($data['trailer_url']) && file_exists($data['trailer_url'])) {
            $movie->clearMediaCollection('trailers');
            $movie->addMedia($data['trailer_url'])->toMediaCollection('trailers');
        }

        return $movie;
    }

    public function deleteMovie(int $id): bool
    {
        $movie = Movie::findOrFail($id);

        // Delete media
        $movie->clearMediaCollection('posters');
        $movie->clearMediaCollection('trailers');

        return $movie->delete();
    }

    public function showMovie(int $id): Movie
    {
        return Movie::with(['media'])->findOrFail($id);
    }
    public function getPaginatedMovies(array $filters = [], int $perPage = 10)
    {
        $query = Movie::query();

        // 🔍 بحث بالعنوان
        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        // 🎯 فلترة حسب النوع
        if (!empty($filters['genre'])) {
            $query->where('genre', $filters['genre']);
        }

        // 🈯 فلترة حسب اللغة
        if (!empty($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        // 📅 فلترة حسب سنة الإصدار
        if (!empty($filters['year'])) {
            $query->whereYear('release_date', $filters['year']);
        }

        // 🔃 ترتيب
        if (!empty($filters['sort_by'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function fetchMovieDetailsById(int $tmdbId): ?array
{
    $apiKey = config('services.tmdb.key');

    $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
        'api_key' => $apiKey,
        'language' => 'en-US',
    ]);

    if ($response->failed()) {
        return null;
    }

    $movie = $response->json();

    return [
        'title' => $movie['title'],
        'description' => $movie['overview'],
        'poster_url' => $movie['poster_path']
            ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
            : null,
        'release_date' => $movie['release_date'],
        'rating' => $movie['vote_average'],
    ];
}
public function searchMoviesFromTMDb(string $query): array
{
    $apiKey = config('services.tmdb.key');

    $response = Http::get("https://api.themoviedb.org/3/search/movie", [
        'api_key' => $apiKey,
        'query'   => $query,
        'language' => 'en-US',
        'include_adult' => false,
        'page' => 1,
    ]);

    if ($response->failed() || empty($response['results'])) {
        return [];
    }

    return collect($response['results'])->map(function ($movie) {
        return [
            'tmdb_id' => $movie['id'],
            'title' => $movie['title'],
            'original_title' => $movie['original_title'],
            'overview' => $movie['overview'],
            'release_date' => $movie['release_date'],
            'rating' => $movie['vote_average'],
            'poster_url' => $movie['poster_path']
                ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                : null,
        ];
    })->toArray();
}
}