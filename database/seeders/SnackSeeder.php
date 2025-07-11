<?php

namespace Database\Seeders;

use App\Models\Snack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SnackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $snacks = [
            ['name' => 'Popcorn', 'price' => 5.00],
            ['name' => 'Soda', 'price' => 3.00],
            ['name' => 'Nachos', 'price' => 6.50],
            ['name' => 'Candy', 'price' => 4.00],
        ];

        foreach ($snacks as $snack) {
            Snack::create($snack);
        }
    }
}
