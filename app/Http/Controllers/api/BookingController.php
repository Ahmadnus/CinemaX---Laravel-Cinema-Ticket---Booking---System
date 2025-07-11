<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingsService;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    public function __construct(protected BookingsService $bookingService) {}

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->validated());
        return new BookingResource($booking->load(['seats', 'snacks']));
    }
    public function update(UpdateBookingRequest $request, Booking $booking)
{
    $updated = $this->bookingService->updateBooking($request->validated(), $booking);
    return new BookingResource($updated);
}
public function destroy(Booking $booking)
{
    try {
        $this->bookingService->deleteBooking($booking);
        return response()->json(['message' => 'Booking deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}