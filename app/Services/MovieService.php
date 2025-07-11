<?php

namespace App\Services;

use App\Models\Movie;

class MovieService
{
    /**
     * Create a new class instance.
     */
    public function createMovie(array $data): Movie
    {
        $movie = Movie::create($data);

        // Poster
        if (!empty($data['poster_url']) && file_exists($data['poster_url'])) {
            $movie->addMedia($data['poster_url'])->toMediaCollection('posters');
        }

        // Trailer
        if (!empty($data['trailer_url']) && file_exists($data['trailer_url'])) {
            $movie->addMedia($data['trailer_url'])->toMediaCollection('trailers');
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
}
