<?php

namespace App\Models;

use App\Models\Antrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    
    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function antriandesain()
    {
        return $this->hasMany(AntrianDesain::class);
    }

    public function design()
    {
        return $this->hasMany(Design::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
