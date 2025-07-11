<?php

namespace App\Services;

use App\Models\Hall;

class HallService
{
    public function create(array $data): Hall
    {
        return Hall::create($data);
    }

    public function update(Hall $hall, array $data): Hall
    {
        $hall->update($data);
        return $hall;
    }

    public function delete(Hall $hall): bool
    {
        return $hall->delete();
    }

    public function getAll()
    {
        return Hall::with('cinema')->get();
    }

    public function getById($id): ?Hall
    {
        return Hall::with('cinema')->findOrFail($id);
    }
}