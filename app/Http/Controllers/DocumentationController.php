<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Models\DataAntrian; // Import the DataAntrian model

use Yajra\DataTables\Facades\DataTables;

class DocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //index
    public function index()
    {
        $dokum = Documentation::all();
        return view('page.dokumentasi.index', compact('dokum'));
    }


    public function indexJson()
    {
        try {
            $barang = Barang::with('antrian')->whereHas('antrian', function ($query) {
                $query->where('status', 1);
            })->get(); // Fetch the $barang variable correctly
        
            return Datatables::of($barang)
                ->addIndexColumn()
                ->addColumn('ticket_order', function ($barang) {
                    return $barang->ticket_order;
                })
                ->addColumn('sales', function ($barang) {
                    return $barang->user->name;
                })
                ->addColumn('accdesain', function ($barang) {
                    if (isset($barang->accdesain)) {
                        return '<img width="150" src="'. asset($barang->accdesain) .'" class="img-thumbnail">';
                    } else {
                        return 'Tidak ada data';
                    }
                })
                ->addColumn('nama_produk', function ($barang) {
                    if (isset($barang->job->job_name)) {
                        return $barang->job->job_name;
                    } else {
                        return 'Tidak ada data';
                    }
                })
                ->addColumn('action', function ($barang) {
                    return '<a href="'.route('documentation.edit', $barang->ticket_order).'" class="btn btn-primary btn-sm">Upload</a>';
                })
                ->rawColumns(['action', 'accdesain'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }        
    }
}
