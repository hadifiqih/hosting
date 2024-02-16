<?php

namespace App\Models;

use App\Models\Antrian;
use App\Models\DataAntrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentation extends Model
{
    use HasFactory;

    protected $table = 'documentations';

    public function antrian()
    {
        return $this->belongsTo(DataAntrian::class, 'ticket_order', 'ticket_order');
    }

    public function barang()
    {
        return $this->hasOne(Barang::class);
    }
}
