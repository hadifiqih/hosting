<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function documentation()
    {
        return $this->hasMany(Documentation::class);
    }
}
