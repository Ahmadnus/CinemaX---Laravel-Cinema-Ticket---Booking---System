<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;

class MovieService
{
    public function createMovie(array $data): Movie
{
    // تحقق إذا كان الفيلم موجود مسبقاً
    $exists = DB::table('movie_translations')
        ->whereIn('title', [
            $data['en']['title'],
            $data['ar']['title']
        ])
        ->exists();

    if ($exists) {
        throw ValidationException::withMessages([
            'title' => [trans('A movie with the same title already exists.')],
        ]);
    }

    $movie = new Movie();

    $movie->translateOrNew('en')->title = $data['en']['title'];
    $movie->translateOrNew('ar')->title = $data['ar']['title'];
    $movie->translateOrNew('en')->description = $data['en']['description'];
    $movie->translateOrNew('ar')->description = $data['ar']['description'];

    $movie->release_date = $data['release_date'];
    $movie->rating = $data['rating'];
    $movie->save();

    if (!empty($data['poster_url'])) {
        $movie->addMediaFromUrl($data['poster_url'])->toMediaCollection('posters');
    }

    return $movie;
}

public function updateMovie(int $id, array $data): Movie
{
    $movie = Movie::findOrFail($id);

    $movie->translateOrNew('en')->title = $data['en']['title'];
    $movie->translateOrNew('ar')->title = $data['ar']['title'];

    $movie->translateOrNew('en')->description = $data['en']['description'];
    $movie->translateOrNew('ar')->description = $data['ar']['description'];

    $movie->release_date = $data['release_date'];
    $movie->rating = $data['rating'];
    $movie->genre = $data['genre'];
    $movie->language = $data['language'];
    $movie->duration_min = $data['duration_min'];
    $movie->save();

    // Poster
    if (!empty($data['poster_url'])) {
        $movie->clearMediaCollection('posters');
        $movie->addMediaFromUrl($data['poster_url'])->toMediaCollection('posters');
    }

    // Trailer
    if (!empty($data['trailer_url'])) {
        $movie->clearMediaCollection('trailers');
        $movie->addMediaFromUrl($data['trailer_url'])->toMediaCollection('trailers');
    }

    return $movie;
}

    public function deleteMovie(int $id): bool
    {
        $movie = Movie::findOrFail($id);

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

        if (!empty($filters['search'])) {
            $query->whereTranslationLike('title', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['genre'])) {
            $query->where('genre', $filters['genre']);
        }

        if (!empty($filters['language'])) {
            $query->where('language', $filters['language']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('release_date', $filters['year']);
        }

        if (!empty($filters['sort_by'])) {
            $direction = $filters['direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function fetchMovieDetailsById(int $tmdbId, string $locale = 'en'): ?array
    {
        $apiKey = config('services.tmdb.key');
        $language = $locale === 'ar' ? 'ar-SA' : 'en-US';

        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
            'api_key' => $apiKey,
            'language' => $language,
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
            'locale' => $locale,
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
