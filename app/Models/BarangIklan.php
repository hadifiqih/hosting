<?php

namespace App\Models;

use App\Models\Iklan;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangIklan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang_iklan';

    protected $fillable = [
        'tahun_iklan',
        'bulan_iklan',
        'sales_id',
        'job_id',
        'barang_id',
    ];

    public function iklan()
    {
        return $this->belongsTo(Iklan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
