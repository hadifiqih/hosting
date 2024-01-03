<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\DataKerja;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataAntrian extends Model
{
    use HasFactory;

    protected $table = 'data_antrian';

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ticket_order', 'ticket_order');
    }

    public function dataKerja()
    {
        return $this->belongsTo(DataKerja::class, 'ticket_order', 'ticket_order');
    }

    public function printfile()
    {
        return $this->belongsTo(PrintFile::class, 'ticket_order', 'ticket_order');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }
}
