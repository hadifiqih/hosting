<?php

namespace App\Models;

use App\Models\Antrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machines';

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'machine_code', 'machine_code');
    }

}
