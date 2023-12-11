<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class BarangController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $harga = CustomHelper::removeCurrencyFormat($request->harga);

        //rename file acc_desain
        $file = $request->file('acc_desain');
        $fileName = time().'_'.$file->getClientOriginalName();
        $pathGambar = 'acc_desain/'.$fileName;
        Storage::disk('public')->put($pathGambar, file_get_contents($file));
        
        $barang = new Barang();
        $barang->ticket_order = $request->ticket_order;
        $barang->job_id = $request->namaProduk;
        $barang->user_id = auth()->user()->id;
        $barang->price = $harga;
        $barang->qty = $request->qty;
        $barang->note = $request->keterangan;
        $barang->acc_desain = $fileName;
        $barang->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan !',
        ]);
    }

    public function getTotalHarga($id)
    {
        $totalHarga = Barang::where('ticket_order', $id)->sum('price');

        return response()->json([
            'success' => true,
            'totalHarga' => $totalHarga,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $items = Barang::where('ticket_order', $id)->get();

        return DataTables::of($items)
        ->addIndexColumn()
        ->addColumn('nama_produk', function($row){
            return '<a class="text-primary" onclick="modalRefACC('.$row->ticket_order.')">' .$row->job->job_name. '</a>' ;
        })
        ->addColumn('harga', function($row){
            return 'Rp '.number_format($row->price, 0, ',', '.');
        })
        ->addColumn('qty', function($row){
            return $row->qty;
        })
        ->addColumn('subtotal', function($row){
            //Hitung subtotal = harga * qty
            $subtotal = $row->price * $row->qty;
            return 'Rp '.number_format($subtotal, 0, ',', '.');
        })
        ->addColumn('note', function($row){
            return $row->note;
        })
        ->rawColumns(['nama_produk'])
        ->make(true);
    }

    public function showCreate(string $id)
    {
        $items = Barang::where('ticket_order', $id)->with('job')->get();

        return DataTables::of($items)
        ->addIndexColumn()
        ->addColumn('kategori', function($row){
            return $row->job->job_type;
        })
        ->addColumn('namaProduk', function($row){
            return $row->job->job_name; ;
        })
        ->addColumn('qty', function($row){
            return $row->qty;
        })
        ->addColumn('harga', function($row){
            return 'Rp '.number_format($row->price, 0, ',', '.');
        })
        ->addColumn('hargaTotal', function($row){
            //Hitung subtotal = harga * qty
            $subtotal = $row->price * $row->qty;
            return 'Rp '.number_format($subtotal, 0, ',', '.');
        })
        ->addColumn('keterangan', function($row){
            return $row->note;
        })
        ->addColumn('action', function($row){
            $btn = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteBarang('.$row->id.')"><i class="fas fa-trash"></i></a>';
            return $btn;
        })
        ->rawColumns(['action', 'hargaTotal'])
        ->make(true);
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
