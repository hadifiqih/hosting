<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Antrian;

class AntrianPolicy
{
    public function delete(User $user)
    {
        // Memeriksa apakah pengguna adalah admin
        if ($user->isAdmin()) {
            return true; // Admin diperbolehkan menghapus antrian
        }

        return false; // Pengguna tidak diizinkan menghapus antrian
    }

    public function store(User $user)
    {
        // Memeriksan pakah pengguna adalah sales
        if ($user->isSales()) {
            return true; // Sales diperbolehkan menambah antrian
        }

        return false; // Pengguna tidak diizinkan menambah antrian
    }
}
