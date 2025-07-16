<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MovieService;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'genre', 'language', 'year', 'sort_by', 'direction']);
        $perPage = $request->get('per_page', 10);

        $movies = $this->movieService->getPaginatedMovies($filters, $perPage);

        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        try {
            $movie = $this->movieService->createMovie($data);
            return redirect()->route('movies.index')->with('success', __('Movie created successfully.'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->all();

        $this->movieService->updateMovie($movie->id, $data);

        return redirect()->route('movies.index')->with('success', __('Movie updated successfully.'));
    }

    public function destroy(Movie $movie)
    {
        $this->movieService->deleteMovie($movie->id);
        return redirect()->route('movies.index')->with('success', __('Movie deleted successfully.'));
    }
}
