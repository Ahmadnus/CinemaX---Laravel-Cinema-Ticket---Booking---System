<?php

namespace App\Services;

use App\Models\Seat;

class SeatService
{
    /**
     * جلب كل المقاعد الخاصة بعرض معيّن مع ترتيبها حسب الصف والرقم
     *
     * @param int $screeningId

     */
    public function getSeatsByScreening(int $screeningId)
    {
        return Seat::where('screening_id', $screeningId)
            ->orderBy('row')
            ->orderBy('number')
            ->get();
    }

    /**
     * إنشاء مقاعد جديدة لعرض معيّن
     *
     * @param int $screeningId
     * @param array $seatsData   // مثال: [['row' => 'A', 'number' => 1, 'price' => 20.00], ...]
     * @return void
     */
    public function createSeats(int $screeningId, array $seatsData): void
    {
        $seats = [];

        foreach ($seatsData as $seat) {
            $seats[] = [
                'screening_id' => $screeningId,
                'row' => $seat['row'],
                'number' => $seat['number'],
                'price' => $seat['price'],
                'is_reserved' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // إدخال دفعة مقاعد دفعة واحدة
        Seat::insert($seats);
    }

    /**
     * تحديث حالة حجز مقاعد (محجوزة / متاحة)
     *
     * @param array $seatIds
     * @param bool $reserved
     * @return int عدد المقاعد التي تم تحديثها
     */
    public function updateSeatsReservation(array $seatIds, bool $reserved): int
    {
        return Seat::whereIn('id', $seatIds)->update(['is_reserved' => $reserved]);
    }

    /**
     * حذف مقاعد معينة
     *
     * @param array $seatIds
     * @return int عدد المقاعد التي تم حذفها
     */
    public function deleteSeats(array $seatIds): int
    {
        return Seat::whereIn('id', $seatIds)->delete();
    }
}
