<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Antrian;
use App\Models\Employee;

class Design extends Model
{
    use HasFactory;

    protected $table = 'designs';

    public function antrian()
    {
        return $this->hasMany(Antrian::class);
    }

    // Fungsi untuk menyimpan file desain
    public function saveFile($file)
    {
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->storeAs('public/ref-desain', $filename);

        return $filename;
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function sales(){
        return $this->belongsTo(Sales::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
