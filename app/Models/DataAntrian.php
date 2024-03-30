<?php

namespace App\Models;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\DataKerja;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\BuktiPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataAntrian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_antrian';

    public function estimator()
    {
        return $this->belongsTo(Employee::class, 'estimator_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo(Employee::class, 'operator_id', 'id');
    }

    public function finishing()
    {
        return $this->belongsTo(Employee::class, 'finishing_id', 'id');
    }

    public function quality()
    {
        return $this->belongsTo(Employee::class, 'qc_id', 'id');
    }

    public function filePendukung()
    {
        return $this->belongsTo(FilePendukung::class, 'ticket_order', 'ticket_order');
    }

    public function buktiBayar()
    {
        return $this->belongsTo(BuktiPembayaran::class, 'ticket_order', 'ticket_order');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

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

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'ticket_order', 'ticket_order');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'ticket_order', 'ticket_order');
    }
}
