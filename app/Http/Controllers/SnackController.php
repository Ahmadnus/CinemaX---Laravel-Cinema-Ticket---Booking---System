<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSnackRequest;
use App\Http\Requests\UpdateSnackRequest;
use App\Services\SnackService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;




class SnackController extends Controller
{
    use ApiResponse;

    protected $snackService;

    public function __construct(SnackService $snackService)
    {
        $this->snackService = $snackService;
    }

    public function index()
    {
        $snacks = $this->snackService->getAll();
        return $this->successResponse($snacks);
    }

    public function show(int $id)
    {
        $snack = $this->snackService->getById($id);

        if (!$snack) {
            return $this->errorResponse('Snack not found', 404);
        }

        return $this->successResponse($snack);
    }

    public function store(StoreSnackRequest $request)
    {
        $validated = $request->validated();
        $snack = $this->snackService->create($validated);
        return $this->successResponse($snack, 'Snack created successfully', 201);
    }

    public function update(UpdateSnackRequest $request, int $id)
    {
        $validated = $request->validated();
        $snack = $this->snackService->update($id, $validated);

        if (!$snack) {
            return $this->errorResponse('Snack not found', 404);
        }

        return $this->successResponse($snack, 'Snack updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->snackService->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Snack not found', 404);
        }

        return $this->successResponse(null, 'Snack deleted successfully');
    }
}