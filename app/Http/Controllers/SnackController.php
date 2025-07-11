<?php

namespace App\Http\Controllers;

use App\Services\SnackService;
use Illuminate\Http\Request;


class SnackController extends Controller
{
    protected $snackService;

    public function __construct(SnackService $snackService)
    {
        $this->snackService = $snackService;
    }

    public function index()
    {
        $snacks = $this->snackService->getAll();
        return response()->json($snacks);
    }

    public function show(int $id)
    {
        $snack = $this->snackService->getById($id);
        if (!$snack) {
            return response()->json(['message' => 'Snack not found'], 404);
        }
        return response()->json($snack);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
        ]);

        $snack = $this->snackService->create($validated);
        return response()->json($snack, 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|string|max:255',
        ]);

        $snack = $this->snackService->update($id, $validated);

        if (!$snack) {
            return response()->json(['message' => 'Snack not found'], 404);
        }

        return response()->json($snack);
    }

    public function destroy(int $id)
    {
        $deleted = $this->snackService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Snack not found'], 404);
        }

        return response()->json(['message' => 'Snack deleted successfully']);
    }
}