<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // مثلا نضيف 30 مقعد لعرض معين (screening_id = 1)
        for ($i = 1; $i <= 30; $i++) {
            Seat::create([
                'screening_id' => 6,   // لازم يكون عندك Screening برقم 1
                'row' => chr(64 + ceil($i / 10)), // A, B, C
                'number' => $i % 10 === 0 ? 10 : $i % 10,
                'is_reserved' => false,
                'price' => 10.00,
            ]);
        }
    }
}
