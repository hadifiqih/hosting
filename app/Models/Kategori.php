<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function job()
    {
        return $this->hasMany(Job::class);
    }
}
