<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHallRequest;
use App\Http\Requests\UpdateHallRequest;
use App\Http\Resources\HallResource;
use App\Models\Hall;
use App\Services\HallService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HallController extends Controller
{
    use ApiResponse;

    protected $hallService;

    public function __construct(HallService $hallService)
    {
        $this->hallService = $hallService;
    }

    public function index()
    {
        $halls = $this->hallService->getAll();
        return $this->successResponse(HallResource::collection($halls));
    }

    public function store(StoreHallRequest $request)
    {
        $hall = $this->hallService->create($request->validated());
        return $this->successResponse(new HallResource($hall), 'Hall created successfully', 201);
    }

    public function show(Hall $hall)
    {
        return $this->successResponse(new HallResource($hall));
    }

    public function update(UpdateHallRequest $request, Hall $hall)
    {
        $updated = $this->hallService->update($hall, $request->validated());
        return $this->successResponse(new HallResource($updated), 'Hall updated successfully');
    }

    public function destroy(Hall $hall)
    {
        $this->hallService->delete($hall);
        return $this->successResponse(null, 'Hall deleted successfully');
    }
}