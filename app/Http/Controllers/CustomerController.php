<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function search(Request $request)
    {
        $data = Customer::where('nama', 'LIKE', "%".request('q')."%")->get();
        return response()->json($data);
    }

    public function searchById(Request $request)
    {
        $data = Customer::where('id', 'LIKE', "%".request('id')."%")->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        //Menyimpan no.telp dalam format seperti berikut 081234567890, tanpa spasi. strip, titik, dll
        $telp = preg_replace('/\D/', '', $request->modalTelepon);

        if(substr($telp, 0, 1) == '0'){
            $telp = '62'.substr($telp, 1);
        }else{
            $telp = $telp;
        }

        $customer = new Customer;

        $customer->telepon = $telp;

        if($request->modalNama){
            $customer->nama = $request->modalNama;
        }

        if($request->modalAlamat){
            $customer->alamat = $request->modalAlamat;
        }

        if($request->modalInstansi){
            $customer->instansi = $request->modalInstansi;
        }

        if($request->modalInfoPelanggan){
            $customer->infoPelanggan = $request->modalInfoPelanggan;
        }

        $customer->save();

        return response()->json(['success' => 'true', 'message' => 'Data berhasil ditambahkan']);
    }

}
