<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Antrian;

class Documentation extends Model
{
    use HasFactory;

    protected $table = 'documentations';

    public function antrian()
    {
        return $this->belongTo(Antrian::class);
    }

}
