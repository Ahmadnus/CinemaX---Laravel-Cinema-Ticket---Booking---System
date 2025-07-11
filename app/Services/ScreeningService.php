<?php

namespace App\Services;

use App\Models\Screening;

class ScreeningService
{
    /**
     * Create a new class instance.
     */
    public function getAllScreenings()
    {
        return Screening::with(['movie', 'cinema', 'hall'])->latest()->get();
    }

    public function getScreeningById(Screening $screening)
    {
        return $screening->load(['movie', 'cinema', 'hall']);
    }

    public function createScreening(array $data)
    {
        return Screening::create($data);
    }

    public function updateScreening(array $data, Screening $screening)
    {
        $screening->update($data);
        return $screening;
    }

    public function deleteScreening(Screening $screening): bool
    {
        return $screening->delete();
    }
}
