<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefDesain extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'refdesain';

    protected $fillable = [
        'filename',
        'path',
        'ticket_order'
    ];
    
}
