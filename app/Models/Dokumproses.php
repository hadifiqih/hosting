<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Antrian;

class Dokumproses extends Model
{
    use HasFactory;

    protected $table = 'dokumproses';

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }




}
