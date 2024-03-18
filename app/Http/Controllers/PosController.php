<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function addOrder()
    {
        return view('page.kasir.pos');
    }

    public function manageProduct()
    {
        //Give example 5 data for product with id, kode_produk, nama_produk, price, sell price, stok
        $products = Produk::getProducts();

        return Datatables::of($products)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeProduct(Request $request)
    {
        $harga_kulak = CustomHelper::removeCurrencyFormat($request->harga_kulak);
        $harga_jual = CustomHelper::removeCurrencyFormat($request->harga_jual);

        //Validation


        //Save to database
        return redirect()->route('manage.product')->with('success', 'Product has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
