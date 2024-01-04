<?php

namespace App\Http\Controllers;

use App\Models\Bahan;

use App\Models\Antrian;

use App\Models\Pembayaran;
use App\Models\DataAntrian;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

        $antrian = Antrian::where('ticket_order', $request->ticket_order)->first();


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

        $antrian = DataAntrian::where('ticket_order', $id)->first();

        $done = $antrian->done_production_at;

        $bahanTotal = Bahan::where('ticket_order', $id)->sum('harga');

        if($done == null) {
            $dataTable = Datatables::of($bahan)
            ->addIndexColumn()
            ->addColumn('nama_bahan', function ($row) {
                return $row->nama_bahan;
            })
            ->addColumn('harga', function ($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->addColumn('note', function ($row) {
                return $row->note;
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteBahan(' . $row->id . ')"><i class="fas fa-trash"></i></a>';
                return $btn;
            });

            return $dataTable->rawColumns(['aksi'])->make(true);
        }else{
            $dataTable = Datatables::of($bahan)
            ->addIndexColumn()
            ->addColumn('nama_bahan', function ($row) {
                return $row->nama_bahan;
            })
            ->addColumn('harga', function ($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->addColumn('note', function ($row) {
                return $row->note;
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<a href="javascript:void(0)" class="btn btn-secondary btn-sm disabled"><i class="fas fa-trash"></i></a>';
                return $btn;
            });

            return $dataTable->rawColumns(['aksi'])->make(true);
        }
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

    public function totalBahan(string $id)
    {
        $antrian = DataAntrian::where('ticket_order', $id)->first();

        $omset = Pembayaran::where('ticket_order', $id)->first()->total_harga;

        $bahanTotal = Bahan::where('ticket_order', $id)->sum('harga');

        $bahanLain = ($omset * 0.03) + ($omset * 0.02) + ($omset * 0.03) + ($omset * 0.05) + ($omset * 0.025) + ($omset * 0.01) + ($omset * 0.025) + ($omset * 0.02);

        $totalProduksi = $bahanTotal + $bahanLain;

        $profit = $omset - $totalProduksi;

        //format menjadi rupiah dengan number_format
        $bahanTotal = 'Rp'.number_format($bahanTotal, 0, ',', '.');

        $profit = 'Rp'.number_format($profit, 0, ',', '.');

        return response()->json([
            'success' => true,
            'message' => 'Bahan berhasil dihapus',
            'total' => $bahanTotal,
            'profit' => $profit,
        ]);
    }

}
