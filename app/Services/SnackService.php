<?php

namespace App\Services;

use App\Models\Snack;

class SnackService
{
    public function getAll()
    {
        return Snack::all();
    }

    public function getById(int $id): ?Snack
    {
        return Snack::find($id);
    }

    public function create(array $data): Snack
    {
        return Snack::create($data);
    }

    public function update(int $id, array $data): ?Snack
    {
        $snack = Snack::find($id);
        if ($snack) {
            $snack->update($data);
        }
        return $snack;
    }

    public function delete(int $id): bool
    {
        $snack = Snack::find($id);
        if ($snack) {
            return $snack->delete();
        }
        return false;
    }
}
