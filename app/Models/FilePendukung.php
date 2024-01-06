<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePendukung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'file_pendukung';
}
