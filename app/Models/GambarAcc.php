<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GambarAcc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accdesain';

    protected $fillable = [
        'ticket_order',
        'sales_id',
        'filename',
        'filepath',
        'filetype',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'ticket_order', 'ticket_order');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }
}
