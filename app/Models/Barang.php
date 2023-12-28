<?php

namespace App\Models;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'ticket_order', 'ticket_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAccDesainAttribute($value)
    {
        return $value ? asset('storage/acc_desain/'.$value) : null;
    }
}
