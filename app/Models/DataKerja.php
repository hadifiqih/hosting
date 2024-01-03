<?php

namespace App\Models;

use App\Models\DataAntrian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataKerja extends Model
{
    use HasFactory;

    public $table = 'data_kerja';

    public function antrian()
    {
        return $this->belongsTo(DataAntrian::class, 'ticket_order', 'ticket_order');
    }

    public function operator()
    {
        return $this->belongsTo(Employee::class, 'operator_id', 'id');
    }

    public function desainer()
    {
        return $this->belongsTo(Employee::class, 'desainer_id', 'id');
    }

    public function finishing()
    {
        return $this->belongsTo(Employee::class, 'finishing_id', 'id');
    }

    public function qc()
    {
        return $this->belongsTo(Employee::class, 'qc_id', 'id');
    }
}
