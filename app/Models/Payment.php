<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Antrian;

class Payment extends Model
{
    use HasFactory;

    //relasi dengan tabel antrian dengan foreign key ticket_order
    public function antrian()
    {
        return $this->belongsTo(Antrian::class, 'ticket_order', 'ticket_order');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'ticket_order');
    }

}
