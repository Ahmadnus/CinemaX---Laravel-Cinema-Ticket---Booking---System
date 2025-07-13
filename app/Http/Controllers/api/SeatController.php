<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSeatsRequest;
use App\Http\Requests\SeatRequest;
use App\Http\Requests\UpdateSeatRequest;
use App\Http\Resources\SeatResource;
use App\Services\SeatService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


class SeatController extends Controller
{
    use ApiResponse;

    protected SeatService $seatService;

    public function __construct(SeatService $seatService)
    {
        $this->seatService = $seatService;
    }

    /**
     * عرض جميع المقاعد لعرض معيّن
     */
    public function index(int $screeningId)
    {
        $seats = $this->seatService->getSeatsByScreening($screeningId);
        return $this->successResponse(SeatResource::collection($seats));
    }

    /**
     * إنشاء مقاعد جديدة لعرض معيّن
     */
    public function store(SeatRequest $request, int $screeningId)
    {
        $this->seatService->createSeats($screeningId, $request->validated()['seats']);
        return $this->successResponse(null, 'Seats created successfully', 201);
    }

    /**
     * تحديث حالة الحجز لمقاعد معيّنة
     */
    public function updateReservation(UpdateSeatRequest $request)
    {
        $validated = $request->validated();

        $count = $this->seatService->updateSeatsReservation($validated['seat_ids'], $validated['is_reserved']);

        return $this->successResponse([
            'updated_seats_count' => $count
        ], "Updated reservation status for {$count} seats.");
    }

    /**
     * حذف مجموعة من المقاعد
     */
    public function destroy(DeleteSeatsRequest $request)
    {
        $validated = $request->validated();

        $count = $this->seatService->deleteSeats($validated['seat_ids']);

        return $this->successResponse([
            'deleted_seats_count' => $count
        ], "Deleted {$count} seats.");
    }
}
