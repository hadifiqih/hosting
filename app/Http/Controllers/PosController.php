<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\StokBahan;
use App\Models\ProdukHarga;
use App\Models\ProdukGrosir;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Models\KeranjangItem;
use Yajra\DataTables\Facades\DataTables;

class PosController extends Controller
{
    public function addOrder()
    {
        $cabang = auth()->user()->cabang_id;
        $sales = auth()->user()->sales->id;
        $user = auth()->user()->id;
        $keranjang_id = Keranjang::getKeranjang()->last();
        if($keranjang_id == null){
            $keranjang_id = 1;
        }else{
            $keranjang_id = $keranjang_id->id + 1;
        }
        return view('page.kasir.pos', compact('cabang', 'sales', 'user', 'keranjang_id'));
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
            ->addColumn('kode_produk', function($row){
                return $row->kode_produk;
            })
            ->addColumn('nama_produk', function($row){
                return $row->nama_produk;
            })
            ->addColumn('harga_kulak', function($row){
                $cabang = auth()->user()->cabang_id;
                if($cabang == 1){
                    $kulak = ProdukHarga::where('produk_id', $row->id)->where('cabang_id', 1)->first();
                }else{
                    $kulak = ProdukHarga::where('produk_id', $row->id)->where('cabang_id', 2)->first();
                }

                if($kulak == null){
                    return 'Belum Diatur';
                }else{
                    return CustomHelper::addCurrencyFormat($kulak->harga_kulak);
                }
            })
            ->addColumn('harga_jual', function($row){
                $cabang = auth()->user()->cabang_id;
                if($cabang == 1){
                    $jual = ProdukHarga::where('produk_id', $row->id)->where('cabang_id', 1)->first();
                }else{
                    $jual = ProdukHarga::where('produk_id', $row->id)->where('cabang_id', 2)->first();
                }

                if($jual == null){
                    return 'Belum Diatur';
                }else{
                    return CustomHelper::addCurrencyFormat($jual->harga_jual);
                }
            })
            ->addColumn('stok_bahan', function($row){
                $cabang = auth()->user()->cabang_id;
                $stok = StokBahan::where('produk_id', $row->id)->where('cabang_id', $cabang)->first();
                if($stok == null){
                    return 'Belum Diatur';
                }
                return $stok->jumlah_stok;
            })
            ->addColumn('action', function($row){
                $actionBtn = '<div class="btn-group">';
                $actionBtn .= '<a href="'.route('pos.editProduct', $row->id).'" class="edit btn btn-warning btn-sm"><i class="fas fa-edit"></i> Ubah</a>';
                $actionBtn .= '<button type="button" class="delete btn btn-danger btn-sm" onclick="hapusProduk('.$row->id.')"><i class="fas fa-trash"></i> Hapus</button>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'stok_bahan'])
            ->make(true);
    }

    public function pilihProduk()
    {
        $produk = Produk::getProducts();

        return Datatables::of($produk)
            ->addIndexColumn()
            ->addColumn('harga_jual', function($row){
                return CustomHelper::addCurrencyFormat($row->harga_jual);
            })
            ->addColumn('stok', function($row){;
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
                $actionBtn = '<button type="button" class="btn btn-primary btn-sm" onclick="pilihProduk('.$row->id.')"><i class="fas fa-plus"></i> Pilih</button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function createProduct()
    {
        return view('page.kasir.tambah-produk');
    }

    public function simpanProduk(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk',
            'nama_produk' => 'required|max:255',
            'harga_kulak' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        $cabang_id = auth()->user()->cabang_id;

        $produk = new Produk;
        $produk->kode_produk = $validated['kode_produk'];
        $produk->nama_produk = ucwords(strtolower($validated['nama_produk']));
        $produk->save();

        $harga = new ProdukHarga;
        $harga->produk_id = $produk->id;
        $harga->cabang_id = $cabang_id;
        $harga->harga_kulak = $validated['harga_kulak'];
        $harga->harga_jual = $validated['harga_jual'];
        $harga->save();

        $stok = new StokBahan;
        $stok->produk_id = $produk->id;
        $stok->cabang_id = $cabang_id;
        $stok->jumlah_stok = $validated['stok'];
        $stok->save();

        if(isset($request->min) && isset($request->max)){
            //perulangan untuk menambahkan harga grosir
            for($i = 0; $i < count($request->min); $i++){
                $grosir = new ProdukGrosir;
                $grosir->produk_id = $produk->id;
                $grosir->cabang_id = $cabang_id;
                $grosir->min_qty = $request->min[$i];
                $grosir->max_qty = $request->max[$i];
                $grosir->harga_grosir = $request->harga[$i];
                $grosir->save();
            }
        }

        if($produk->save() && $harga->save() && $stok->save() && $grosir->save()){
            return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil ditambahkan');
        }elseif($produk->save() && $harga->save() && $stok->save()){
            return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil ditambahkan');
        }else{
            return redirect()->route('pos.manageProduct')->with('error', 'Produk gagal ditambahkan');
        }
    }


    public function show(string $id)
    {
        //
    }

    public function editProduct(string $id)
    {
        $produk = Produk::getProduct($id);
        $cabang = auth()->user()->cabang_id;
        $harga = ProdukHarga::where('produk_id', $id)->where('cabang_id', $cabang)->first();
        $grosir = ProdukGrosir::where('produk_id', $id)->where('cabang_id', $cabang)->get();
        $stok = StokBahan::where('produk_id', $id)->where('cabang_id', $cabang)->first();

        return view('page.kasir.ubah-produk', compact('produk', 'cabang', 'harga', 'grosir', 'stok'));
    }

    public function updateProduct(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_produk' => 'required',
            'kode_produk' => 'required|unique:produk,kode_produk',
            'nama_produk' => 'required|max:255',
            'harga_kulak' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        $cabang_id = auth()->user()->cabang_id;
        $idProduk = $validated['id_produk'];

        $produk = Produk::find($idProduk);
        $produk->kode_produk = $validated['kode_produk'];
        $produk->nama_produk = ucwords(strtolower($validated['nama_produk']));
        $produk->save();

        $harga = ProdukHarga::where('produk_id', $idProduk)->where('cabang_id', $cabang_id)->first();
        $harga->harga_kulak = $validated['harga_kulak'];
        $harga->harga_jual = $validated['harga_jual'];
        $harga->save();

        $stok = StokBahan::where('produk_id', $idProduk)->where('cabang_id', $cabang_id)->first();
        $stok->jumlah_stok = $validated['stok'];
        $stok->save();

        if(isset($request->min) && isset($request->max)){
            //perulangan untuk menambahkan harga grosir
            for($i = 0; $i < count($request->min); $i++){
                $grosir = ProdukGrosir::where('produk_id', $idProduk)->where('cabang_id', $cabang_id)->get();
                $grosir[$i]->min_qty = $request->min[$i];
                $grosir[$i]->max_qty = $request->max[$i];
                $grosir[$i]->harga_grosir = $request->harga[$i];
                $grosir[$i]->save();
            }
        }

        if($produk->save() && $harga->save() && $stok->save() && $grosir->save()){
            return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil ditambahkan');
        }elseif($produk->save() && $harga->save() && $stok->save()){
            return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil ditambahkan');
        }else{
            return redirect()->route('pos.manageProduct')->with('error', 'Produk gagal ditambahkan');
        }
    }

    public function destroyProduct(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect()->route('pos.manageProduct')->with('success', 'Produk berhasil dihapus!');
    }

    //--------------------------------------------------------------------------------------------
    //Fungsi untuk Keranjang
    //--------------------------------------------------------------------------------------------
    public function tambahKeranjang(Request $request)
    {
        $keranjang = new Keranjang;
        $keranjang->cabang_id = $request->cabang_id;
        $keranjang->sales_id = $request->sales_id;
        $keranjang->user_id = $request->user_id;
        $keranjang->save();

        $produk = Produk::find($request->produk_id);

        $item = new KeranjangItem;
        $item->keranjang_id = $keranjang->id;
        $item->produk_id = $request->produk_id;
        $item->jumlah = 1;
        if($request->cabang_id == 1){
            $item->harga = $produk->harga_1;
        }else{
            $item->harga = $produk->harga_2;
        }
        $item->harga = $produk->harga;
        $item->diskon = $request->diskon;
        $item->save();

        return response()->json(['success' => 'Produk berhasil ditambahkan ke keranjang']);
    }

    public function tampilkanKeranjang(string $id_cart)
    {
        $items = KeranjangItem::getItemByIdCart($id_cart);

        return Datatables::of($items)
            ->addColumn('harga', function($row){
                return CustomHelper::addCurrencyFormat($row->harga);
            })
            ->addColumn('diskon', function($row){
                return CustomHelper::addCurrencyFormat($row->diskon);
            })
            ->addColumn('total', function($row){
                return CustomHelper::addCurrencyFormat($row->harga * $row->jumlah - $row->diskon);
            })
            ->addColumn('action', function($row){
                $actionBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="hapusItem('.$row->id.')"><i class="fas fa-trash"></i> Hapus</button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
