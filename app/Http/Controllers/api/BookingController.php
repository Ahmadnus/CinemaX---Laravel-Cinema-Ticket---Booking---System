<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingsService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    use ApiResponse;

    public function __construct(protected BookingsService $bookingService) {}

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->validated());
        return $this->successResponse(
            new BookingResource($booking->load(['seats', 'snacks'])),
            'Booking created successfully',
            201
        );
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $updated = $this->bookingService->updateBooking($request->validated(), $booking);
        return $this->successResponse(
            new BookingResource($updated->load(['seats', 'snacks'])),
            'Booking updated successfully'
        );
    }

    public function destroy(Booking $booking)
    {
        try {
            $this->bookingService->deleteBooking($booking);
            return $this->successResponse(null, 'Booking deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}