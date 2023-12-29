<?php

namespace App\Models;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
