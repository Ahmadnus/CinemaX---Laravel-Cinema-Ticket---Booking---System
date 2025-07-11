<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BookingsService


{
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $userId = Auth::id();

            // تحقق من المقاعد غير محجوزة
            $seats = Seat::whereIn('id', $data['seat_ids'])
                ->where('is_reserved', false)
                ->get();

            if (count($seats) !== count($data['seat_ids'])) {
                throw new \Exception('Some seats are already reserved.');
            }

            $total = $seats->sum('price');

            // حساب سعر السناكات
            if (!empty($data['snacks'])) {
                foreach ($data['snacks'] as $snack) {
                    $snackModel = \App\Models\Snack::find($snack['snack_id']);
                    $total += $snackModel->price * $snack['quantity'];
                }
            }

            $booking = Booking::create([
                'user_id'       => $userId,
                'screening_id'  => $data['screening_id'],
                'total_price'   => $total,
                'status'        => 'confirmed', // أو pending لو بدك انتظار دفع
            ]);

            // ربط المقاعد وتحديثها كـ محجوزة
            $booking->seats()->attach($data['seat_ids']);
            Seat::whereIn('id', $data['seat_ids'])->update(['is_reserved' => true]);

            // ربط السناكات إن وُجدت
            if (!empty($data['snacks'])) {
                foreach ($data['snacks'] as $snack) {
                    $booking->snacks()->attach($snack['snack_id'], [
                        'quantity' => $snack['quantity'],
                    ]);
                }
            }

            return $booking;
        });
    }
    public function updateBooking(array $data, Booking $booking): Booking
{
    return DB::transaction(function () use ($data, $booking) {

        if (isset($data['seat_ids'])) {
            // رجّع المقاعد القديمة غير محجوزة
            Seat::whereIn($booking->seats->pluck('id'), [])->update(['is_reserved' => false]);

            // تأكد المقاعد الجديدة غير محجوزة
            $seats = Seat::whereIn('id', $data['seat_ids'])
                ->where('is_reserved', false)
                ->get();

            if (count($seats) !== count($data['seat_ids'])) {
                throw new \Exception('Some seats are already reserved.');
            }

            $booking->seats()->sync($data['seat_ids']);
            Seat::whereIn($data['seat_ids'], [])->update(['is_reserved' => true]);
        }

        // تحديث السناكات
        if (isset($data['snacks'])) {
            $snackData = [];
            foreach ($data['snacks'] as $snack) {
                $snackData[$snack['snack_id']] = ['quantity' => $snack['quantity']];
            }
            $booking->snacks()->sync($snackData);
        }

        // تحديث باقي الحقول
        $booking->update([

            'status'         => $data['status'] ?? $booking->status,
            'total_price'    => $this->calculateTotalPrice($booking), // دالة تحسب السعر من جديد
        ]);

        return $booking->fresh('seats', 'snacks');
    });
}
public function deleteBooking(Booking $booking): void
{
    DB::transaction(function () use ($booking) {
        // تحرير المقاعد من الحجز (تحديث is_reserved إلى false)
        Seat::whereIn('id', $booking->seats->pluck('id'))->update(['is_reserved' => false]);

        // حذف روابط المقاعد والسناكات (pivot)
        $booking->seats()->detach();
        $booking->snacks()->detach();

        // حذف الحجز نفسه
        $booking->delete();
    });
}
private function calculateTotalPrice(Booking $booking): float
{
    $seatTotal = $booking->seats->sum('price');
    $snackTotal = $booking->snacks->sum(function ($snack) {
        return $snack->price * $snack->pivot->quantity;
    });

    return $seatTotal + $snackTotal;
}

}