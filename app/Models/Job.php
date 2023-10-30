<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
