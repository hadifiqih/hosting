<?php

namespace App\Models;

use App\Models\Ekspedisi;
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

    public function ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'kode_ekspedisi', 'kode_ekspedisi');
    }
}
