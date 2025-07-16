<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Services\MovieService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    use ApiResponse;

    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));

        $filters = $request->only([
            'search', 'genre', 'language', 'year', 'sort_by', 'direction'
        ]);

        $perPage = $request->get('per_page', 10);

        $movies = $this->movieService->getPaginatedMovies($filters, $perPage);

        return MovieResource::collection($movies);
    }

    public function show(int $id)
    {
        app()->setLocale(request()->header('Accept-Language', 'en'));

        $movie = $this->movieService->showMovie($id);

        return new MovieResource($movie);
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();

        $movie = $this->movieService->createMovie($data);

        return $this->successResponse($movie, trans('Movie created successfully'), 201);
    }

    public function destroy(int $id)
    {
        $deleted = $this->movieService->deleteMovie($id);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted
                ? trans('Movie deleted successfully')
                : trans('Failed to delete movie')
        ]);
    }


    public function update(UpdateMovieRequest $request, int $id)
{
    $data = $request->validated();

    $movie = $this->movieService->updateMovie($id, [
        'en' => [
            'title' => $data['title']['en'],
            'description' => $data['description']['en'] ?? '',
        ],
        'ar' => [
            'title' => $data['title']['ar'],
            'description' => $data['description']['ar'] ?? '',
        ],
        'release_date' => $data['release_date'] ?? null,
        'rating' => $data['rating'] ?? null,
        'genre' => $data['genre'] ?? null,
        'language' => $data['language'] ?? null,
        'duration_min' => $data['duration_min'] ?? null,
        'poster_url' => $data['poster_url'] ?? null,
        'trailer_url' => $data['trailer_url'] ?? null,
    ]);

    return new MovieResource($movie);
}

    public function storeFromTMDb(Request $request, MovieService $movieService)
    {
        $request->validate([
            'tmdb_id' => 'required|integer',
        ]);

        $enData = $movieService->fetchMovieDetailsById($request->tmdb_id, 'en');
        $arData = $movieService->fetchMovieDetailsById($request->tmdb_id, 'ar');

        if (!$enData) {
            return response()->json(['message' => trans('Movie not found on TMDb')], 404);
        }

        $data = [
            'en' => [
                'title' => $enData['title'],
                'description' => $enData['description'],
            ],
            'ar' => [
                'title' => $arData['title'] ?? $enData['title'],
                'description' => $arData['description'] ?? $enData['description'],
            ],
            'release_date' => $enData['release_date'],
            'rating' => $enData['rating'],
            'poster_url' => $enData['poster_url'],
        ];

        $movie = $movieService->createMovie($data);

        return response()->json($movie, 201);
    }

    public function searchFromTMDb(Request $request, MovieService $movieService)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $results = $movieService->searchMoviesFromTMDb($request->input('query'));

        return response()->json([
            'count' => count($results),
            'results' => $results,
        ]);
    }
}
