<?php

namespace App\Models;

use App\Models\Antrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiayaProduksi extends Model
{
    use HasFactory;

    protected $table = 'biaya_produksi';

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'ticket_order', 'ticket_order');
    }
}
