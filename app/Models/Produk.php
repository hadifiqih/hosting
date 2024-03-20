<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga_kulak',
        'harga_jual',
        'stok'
    ];

    public static function getProducts()
    {
        return Produk::all();
    }

    public static function storeProduct($request)
    {
        $harga_kulak = CustomHelper::removeCurrencyFormat($request->harga_kulak);
        $harga_jual = CustomHelper::removeCurrencyFormat($request->harga_jual);

        $produk = new Produk;
        $produk->kode_produk = $request->kode_produk;
        $produk->nama_produk = $request->nama_produk;
        $produk->harga_kulak = $harga_kulak;
        $produk->harga_jual = $harga_jual;
        $produk->stok = $request->stok;
        $produk->save();
    }

    public static function updateProduct($request, $id)
    {
        $harga_kulak = CustomHelper::removeCurrencyFormat($request->harga_kulak);
        $harga_jual = CustomHelper::removeCurrencyFormat($request->harga_jual);

        $produk = Produk::find($id);
        $produk->kode_produk = $request->kode_produk;
        $produk->nama_produk = $request->nama_produk;
        $produk->harga_kulak = $harga_kulak;
        $produk->harga_jual = $harga_jual;
        $produk->stok = $request->stok;
        $produk->save();
    }

    public static function deleteProduct($id)
    {
        $produk = Produk::find($id);
        $produk->delete();
    }

    public static function getProduct($id)
    {
        return Produk::find($id);
    }

    public static function getProductsForSelect2()
    {
        return Produk::all()->map(function ($produk) {
            return [
                'id' => $produk->id,
                'text' => $produk->nama_produk . ' - ' . CustomHelper::currencyFormat($produk->harga_jual)
            ];
        });
    }

    public static function chooseStok($id, $cabang_id)
    {
        $field_stok = "stok_" . $cabang_id;
        return Produk::find($id)->$field_stok;
    }

    public static function stockByBranch($cabang_id)
    {
        return $cabang_id;
    }
}
