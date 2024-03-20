<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangItem extends Model
{
    use HasFactory;

    protected $table = 'keranjang_item';

    public static function getKeranjang()
    {
        return KeranjangItem::all();
    }

    public static function getItemByIdCart($id)
    {
        return KeranjangItem::where('keranjang_id', $id)->get();
    }
}
