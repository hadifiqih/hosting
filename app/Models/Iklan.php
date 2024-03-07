<?php

namespace App\Models;

use App\Models\Job;
use App\Models\User;
use App\Models\Sales;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iklan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'iklan';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

}
