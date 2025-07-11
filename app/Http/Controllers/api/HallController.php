<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateHallRequest;
use App\Models\Hall;
use App\Services\HallService;
use Illuminate\Http\Request;

class HallController extends Controller
{
    protected $hallService;

    public function __construct(HallService $hallService)
    {
        $this->hallService = $hallService;
    }

    public function index()
    {
        return response()->json($this->hallService->getAll());
    }

    public function store(StoreHallRequest $request)
    {
        $hall = $this->hallService->create($request->validated());
        return response()->json($hall, 201);
    }

    public function show(Hall $hall)
    {
        return response()->json($hall);
    }

    public function update(UpdateHallRequest $request, Hall $hall)
    {
        $updated = $this->hallService->update($hall, $request->validated());
        return response()->json($updated);
    }

    public function destroy(Hall $hall)
    {
        $this->hallService->delete($hall);
        return response()->json(['message' => 'Hall deleted']);
    }
}