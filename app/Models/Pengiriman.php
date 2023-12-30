<?php

namespace App\Models;

use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
