<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatRequest;
use App\Services\SeatService;
use Illuminate\Http\Request;


    class SeatController extends Controller
{
    protected SeatService $seatService;

    public function __construct(SeatService $seatService)
    {
        $this->seatService = $seatService;
    }

    // عرض جميع المقاعد لعرض معين
    public function index(int $screeningId)
    {
        $seats = $this->seatService->getSeatsByScreening($screeningId);
        return response()->json($seats);
    }

    // إنشاء مقاعد جديدة لعرض معين
    public function store(SeatRequest $request, int $screeningId)
    {
        $this->seatService->createSeats($screeningId, $request->validated()['seats']);
        return response()->json(['message' => 'Seats created successfully'], 201);
    }

    // تحديث حالة حجز مقاعد (محجوزة / متاحة)
    public function updateReservation(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer|exists:seats,id',
            'is_reserved' => 'required|boolean',
        ]);

        $updatedCount = $this->seatService->updateSeatsReservation($request->seat_ids, $request->is_reserved);

        return response()->json([
            'message' => "Updated reservation status for {$updatedCount} seats."
        ]);
    }

    // حذف مقاعد معينة
    public function destroy(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer|exists:seats,id',
        ]);

        $deletedCount = $this->seatService->deleteSeats($request->seat_ids);

        return response()->json([
            'message' => "Deleted {$deletedCount} seats."
        ]);
    }

}
