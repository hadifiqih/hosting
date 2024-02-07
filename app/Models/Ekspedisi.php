<?php

namespace App\Models;

use App\Models\Pengiriman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ekspedisi extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'ekspedisi';

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'kode_ekspedisi', 'kode_ekspedisi');
    }
}
