<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Yajra\DataTables\Facades\DataTables;

class PosController extends Controller
{
    public function addOrder()
    {
        return view('page.kasir.pos');
    }

    public function manageProduct()
    {
        return view('page.kasir.manage-product');
    }

    public function manageProductJson()
    {
        //Give example 5 data for product with id, kode_produk, nama_produk, price, sell price, stok
        $products = Produk::getProducts();

        return Datatables::of($products)
            ->addIndexColumn()
            ->addColumn('harga_kulak', function($row){
                return CustomHelper::addCurrencyFormat($row->harga_kulak);
            })
            ->addColumn('harga_jual', function($row){
                return CustomHelper::addCurrencyFormat($row->harga_jual);
            })
            ->addColumn('stok_pusat', function($row){
                return $row->stok_1;
            })
            ->addColumn('stok', function($row){
                if(auth()->user()->cabang_id == 1){
                    return $row->stok_1;
                }
                elseif(auth()->user()->cabang_id == 2){
                    return $row->stok_2;
                }
                elseif(auth()->user()->cabang_id == 3){
                    return $row->stok_3;
                }
                elseif(auth()->user()->cabang_id == 4){
                    return $row->stok_4;
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<div class="btn-group">';
                $actionBtn .= '<button type="button" class="edit btn btn-warning btn-sm" onclick="ubahProduk('.$row->id.')"><i class="fas fa-edit"></i> Ubah</button>';
                $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" onclick="hapusProduk('.$row->id.')"><i class="fas fa-trash"></i> Hapus</button>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getProductName()
    {
        $products = Produk::getProducts();
        return response()->json($products);
    }

    public function create()
    {
        //
    }

    public function storeProduct(Request $request)
    {
        $harga_kulak = CustomHelper::removeCurrencyFormat($request->harga_kulak);
        $harga_jual = CustomHelper::removeCurrencyFormat($request->harga_jual);

        $produk = new Produk;
        $produk->kode_produk = $request->kode_produk;
        $produk->nama_produk = ucwords(strtolower($request->nama_produk));
        $produk->harga_kulak = $harga_kulak;
        $produk->harga_jual = $harga_jual;
        
        $cabang_id = auth()->user()->cabang_id;
        $field_stok = "stok_" . $cabang_id;
        $produk->$field_stok = $request->stok;

        $produk->save();

        return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil ditambahkan');
    }


    public function show(string $id)
    {
        //
    }

    public function editProduct(string $id)
    {
        $produk = Produk::getProduct($id);
        return response()->json($produk);
    }

    public function updateProduct(Request $request, string $id)
    {
        $harga_kulak = CustomHelper::removeCurrencyFormat($request->harga_kulak);
        $harga_jual = CustomHelper::removeCurrencyFormat($request->harga_jual);

        $produk = Produk::find($id);
        $produk->kode_produk = $request->kode_produk;
        $produk->nama_produk = ucwords(strtolower($request->nama_produk));
        $produk->harga_kulak = $harga_kulak;
        $produk->harga_jual = $harga_jual;

        $produk->save();

        return response()->json($produk);
    }

    public function destroyProduct(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil dihapus!');
    }
}
