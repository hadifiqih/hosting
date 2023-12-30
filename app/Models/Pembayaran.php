<?php

namespace App\Models;

use App\Models\Pengiriman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
}
