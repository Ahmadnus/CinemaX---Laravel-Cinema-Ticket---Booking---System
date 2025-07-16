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

public function show(string|int $id)
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
public function fetchFromTMDb(Request $request, MovieService $movieService)
{
    $request->validate([
        'title' => 'required|string',
    ]);

    $data = $movieService->fetchMovieFromTMDb($request->title);

    if (!$data) {
        return response()->json(['message' => 'Movie not found on TMDb'], 404);
    }

    return response()->json($data);
}
public function storeFromTMDb(Request $request, MovieService $movieService)
{
    // 1. تحقق من أن tmdb_id موجود ومُرسل بشكل صحيح
    $request->validate([
        'tmdb_id' => 'required|integer',
    ]);

    // 2. جلب بيانات الفيلم من TMDb عبر الـ ID
    $data = $movieService->fetchMovieDetailsById($request->tmdb_id);

    // 3. في حال لم يتم العثور عليه
    if (!$data) {
        return response()->json(['message' => 'Movie not found on TMDb'], 404);
    }

    // 4. إنشاء الفيلم وتخزين الصورة إن وجدت
    $movie = $movieService->createMovie($data);

    // 5. إرجاع نتيجة ناجحة
    return response()->json($movie, 201);
}
public function searchFromTMDb(Request $request, MovieService $movieService)
{
    $request->validate([
        'query' => 'required|string|min:2',
    ]);

    // ✅ استخدم query string من request
    $query = $request->input('query');

    $results = $movieService->searchMoviesFromTMDb($query);

    return response()->json([
        'count' => count($results),
        'results' => $results,
    ]);
}
}
