<?php

namespace App\Services;

use App\Models\Screening;
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
        $screening = Screening::with('hall')->findOrFail($screeningId);
        $hallCapacity = $screening->hall->capacity;

        $existingSeatCount = Seat::where('screening_id', $screeningId)->count();

        $newSeatsCount = collect($seatsData)->sum('count');

        if ($existingSeatCount + $newSeatsCount > $hallCapacity) {
            throw new \Exception(trans('Total seats exceed the hall capacity.'));
        }

        $seats = [];

        foreach ($seatsData as $seatGroup) {
            $row = $seatGroup['row'];
            $count = $seatGroup['count'];
            $price = $seatGroup['price'];

            for ($i = 1; $i <= $count; $i++) {
                $seats[] = [
                    'screening_id' => $screeningId,
                    'row' => $row,
                    'number' => $i,
                    'price' => $price,
                    'is_reserved' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }
    /**
     * تحديث حالة حجز مقاعد (محجوزة / متاحة)
     *
     * @param array $seatIds
     * @param bool $reserved
     * @return int عدد المقاعد التي تم تحديثها
     */
    public function updateSeats(int $screeningId, array $seatsData): void
    {
        // 1. احذف الكراسي السابقة لهذا العرض
        Seat::where('screening_id', $screeningId)->delete();

        // 2. أضف الكراسي الجديدة
        $seats = [];

        foreach ($seatsData as $seatGroup) {
            $row = $seatGroup['row'];
            $count = $seatGroup['count'];
            $price = $seatGroup['price'];

            for ($i = 1; $i <= $count; $i++) {
                $seats[] = [
                    'screening_id' => $screeningId,
                    'row' => $row,
                    'number' => $i,
                    'price' => $price,
                    'is_reserved' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
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
