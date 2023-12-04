<?php

namespace App\Http\Controllers;

use App\Models\Bahan;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class BahanController extends Controller
{
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
        $validated = $request->validate([
            'nama_bahan' => 'required',
            'harga_bahan' => 'required',
            'ticket_order' => 'required',
        ]);

        //remove Rp dan titik dari harga
        $harga = str_replace('Rp ', '', $request->harga_bahan);
        $harga = str_replace('.', '', $harga);

        $bahan = new Bahan();
        $bahan->nama_bahan = $request->nama_bahan;
        $bahan->harga = $harga;
        $bahan->ticket_order = $request->ticket_order;
        $bahan->note = $request->note != null ? $request->note : '-';
        $bahan->save();

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil ditambahkan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bahan = Bahan::where('ticket_order', $id)->get();

        $totalBahan = 0;

        foreach($bahan as $b){
            $totalBahan += $b->harga;
        }

        return Datatables::of($bahan)
        ->addIndexColumn()
        ->addColumn('nama_bahan', function($row){
            return $row->nama_bahan;
        })
        ->addColumn('harga', function($row){
            return 'Rp '.number_format($row->harga, 0, ',', '.');
        })
        ->addColumn('note', function($row){
            return $row->note;
        })
        ->addColumn('aksi', function($row){
            $btn = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteBahan('.$row->id.')"><i class="fas fa-trash"></i></a>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->with('total', $totalBahan)
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
        $bahan = Bahan::find($id);
        $bahan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil dihapus',
        ]);
    }
}
