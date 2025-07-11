<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScreening;
use App\Http\Requests\UpdateScreening;
use App\Http\Resources\ScreeningResource;
use App\Models\Screening;
use App\Services\ScreeningService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    use ApiResponse;
    public function __construct(protected ScreeningService $service) {}

    public function index()
    {
        $screenings = $this->service->getAllScreenings();
        return $this->successResponse(ScreeningResource::collection($screenings));
    }

    public function store(StoreScreening $request)
    {
        $screening = $this->service->createScreening($request->validated());
        return $this->successResponse(new ScreeningResource($screening), 'Screening created successfully', 201);
    }

    public function show(Screening $screening)
    {
        $screening = $this->service->getScreeningById($screening);
        return $this->successResponse(new ScreeningResource($screening));
    }

    public function update(UpdateScreening $request, Screening $screening)
    {
        $screening = $this->service->updateScreening($request->validated(), $screening);
        return $this->successResponse(new ScreeningResource($screening), 'Screening updated successfully');
    }

    public function destroy(Screening $screening)
    {
        $this->service->deleteScreening($screening);
        return $this->successResponse(null, 'Screening deleted successfully');
    }


}
