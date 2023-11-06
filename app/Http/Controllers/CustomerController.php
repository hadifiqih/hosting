<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::with('sales')->get();

        return view('page.customer.index', compact('customers'));
    }

    public function indexJson()
    {
        $customers = Customer::with('sales')->get();
        return Datatables::of($customers)
        ->addIndexColumn()
        ->addColumn('sales', function ($customers) {
            return $customers->sales_id == 0 ? '-' : $customers->sales->sales_name;
        })
        ->addColumn('telepon', function ($customers) {
            return $customers->telepon == null ? '-' : $customers->telepon;
        })
        ->addColumn('nama', function ($customers) {
            return $customers->nama == null ? '-' : $customers->nama;
        })
        ->addColumn('alamat', function ($customers) {
            return $customers->alamat == null ? '-' : $customers->alamat;
        })
        ->addColumn('instansi', function ($customers) {
            return $customers->instansi == null ? '-' : $customers->instansi;
        })
        ->addColumn('infoPelanggan', function ($customers) {
            return $customers->infoPelanggan == null ? '-' : $customers->infoPelanggan;
        })
        ->addColumn('wilayah', function ($customers) {
            return $customers->wilayah == null ? '-' : $customers->wilayah;
        })
        ->addColumn('action', function ($customers) {
            return '
            <div class="btn-group">
                <button class="btn btn-sm btn-primary edit" data-id="'.$customers->id.'" data-toggle="modal" data-target="#modal-form" onclick=("{{ route("customer.update",'. $customers->id .' ) }}")><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger delete" data-id="'.$customers->id.'" data-toggle="modal" data-target="#modal-form" onclick=("{{ route("customer.des",'. $customers->id .' ) }}"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->make(true);
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

    public function edit(Request $request)
    {
        $customer = Customer::find($request->id);
        return response()->json($customer);
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

        $salesID = $request->salesID;

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

        $customer->sales_id = $salesID;

        $customer->save();

        return response()->json(['success' => 'true', 'message' => 'Data berhasil ditambahkan']);
    }

}
