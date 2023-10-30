<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntrianDesain extends Model
{
    use HasFactory;

    protected $table = 'antrian_desain';

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
