<?php

namespace App\Models;

use App\Models\Job;
use App\Models\User;
use App\Models\Sales;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Employee;
use App\Models\Kategori;
use App\Models\PrintFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='orders';

    public function barang(){
        return $this->hasMany(Barang::class, 'ticket_order', 'ticket_order');
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function sales(){
        return $this->belongsTo(Sales::class);
    }

    public function design(){
        return $this->hasOne(Design::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function antrian(){
        return $this->hasOne(Antrian::class);
    }

    public function payments(){
        return $this->hasOne(Payment::class, 'ticket_order');
    }

    public function kategori(){
        return $this->belongsTo(Kategori::class);
    }

    public function dataAntrian(){
        return $this->hasOne(DataAntrian::class, 'ticket_order', 'ticket_order');
    }
}
