<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
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


    public function store(StoreMovieRequest $request)
{
    $data = $request->validated();



    $movie = $this->movieService->createMovie($data);

    return $this->successResponse($movie, 'Movie created successfully', 201);
}
public function update(UpdateMovieRequest $request, int $id)
{
    $movie = $this->movieService->updateMovie($id, $request->validated());
    return new MovieResource($movie);
}

public function destroy(int $id)
{
    $deleted = $this->movieService->deleteMovie($id);
    return response()->json(['success' => $deleted]);
}

public function show(int $id)
{
    $movie = $this->movieService->showMovie($id);
    return new MovieResource($movie);
}
public function index(Request $request)
{
    $filters = $request->only([
        'search', 'genre', 'language', 'year', 'sort_by', 'direction'
    ]);

    $perPage = $request->get('per_page', 10);

    $movies = $this->movieService->getPaginatedMovies($filters, $perPage);

    return MovieResource::collection($movies);
}
}
