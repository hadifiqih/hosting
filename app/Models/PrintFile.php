<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintFile extends Model
{
    use HasFactory;

    protected $table = 'printfile';

    public function desainer()
    {
        return $this->belongsTo(Employee::class, 'desainer_id', 'id');
    }
}
