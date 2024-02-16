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
            })->where('documentation_id', null)->get(); // Fetch the $barang variable correctly
        
            return Datatables::of($barang)
                ->addIndexColumn()
                ->addColumn('ticket_order', function ($barang) {
                    return $barang->ticket_order;
                })
                ->addColumn('sales', function ($barang) {
                    return $barang->user->sales->sales_name;
                })
                ->addColumn('accdesain', function ($barang) {
                    if (isset($barang->accdesain)) {
                        return '<a class=""><img width="150" src="'. asset($barang->accdesain) .'" class="img-thumbnail"></a>';
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
                    return '<a href="'.route('documentation.edit', $barang->id).'" class="btn btn-primary btn-sm">Upload</a>';
                })
                ->rawColumns(['action', 'accdesain'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }        
    }

    public function selesaiJson()
    {
        try {
            $barang = Barang::with('antrian')->where('documentation_id', '!=', null)->get(); // Fetch the $barang variable correctly
        
            return Datatables::of($barang)
                ->addIndexColumn()
                ->addColumn('ticket_order', function ($barang) {
                    return $barang->ticket_order;
                })
                ->addColumn('sales', function ($barang) {
                    return $barang->user->sales->sales_name;
                })
                ->addColumn('accdesain', function ($barang) {
                    if (isset($barang->accdesain)) {
                        return '<a class=""><img width="150" src="'. asset($barang->accdesain) .'" class="img-thumbnail"></a>';
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
                    return '<button onclick="tampilDokumentasi('.$barang->documentation_id.')" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>';
                })
                ->rawColumns(['action', 'accdesain'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $dokum = Documentation::find($id);
        return response()->json(['data' => $dokum, 'status' => 200]);
    }

    public function downloadGambar($id)
    {
        $dokum = Documentation::find($id);
        //download file
        return response()->download(storage_path('app/public/dokumentasi/'.$dokum->filename));
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        
        return view('page.dokumentasi.edit', compact('barang'));
    }
}
