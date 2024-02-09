<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Iklan;
use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\SumberPelanggan;

class IklanController extends Controller
{
    public function index()
    {
        return view('page.marol.index');
    }

    public function tableIklan()
    {
        $iklans = Iklan::with('user','job','sales')->get();
        
        return Datatables()->of($iklans)
            ->addIndexColumn()
            ->addColumn('marol', function($row){
                return $row->user->name;
            })
            ->addColumn('nomor_iklan', function($row){
                return $row->nomor_iklan;
            })
            ->addColumn('tanggal_mulai', function($row){
                return $row->tanggal_mulai;
            })
            ->addColumn('tanggal_selesai', function($row){
                return $row->tanggal_selesai;
            })
            ->addColumn('nama_produk', function($row){
                return $row->job->job_name;
            })
            ->addColumn('nama_sales', function($row){
                return $row->sales->sales_name;
            })
            ->addColumn('platform', function($row){
                return $row->platform;
            })
            ->addColumn('biaya_iklan', function($row){
                $biaya_iklan = 'Rp. '.number_format($row->biaya_iklan,0,',','.');
                return $biaya_iklan;
            })
            ->addColumn('action', function($row){
                $actionBtn = '<a href="'. route('iklan.edit', $row->id) .'" class="edit btn btn-warning btn-sm"><i class="fas fa-pen"></i> Edit</a> <a onclick="deleteDataIklan()" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>';
                return $actionBtn;
            })
            ->rawColumns(['marol','nama_sales','nama_produk','action'])
            ->make(true);
    }

    public function create()
    {
        return view('page.marol.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_iklan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'nama_produk' => 'required',
            'nama_sales' => 'required',
            'platform' => 'required',
            'biaya_iklan' => 'required',
        ]);

        Iklan::create($request->all());

        return redirect()->route('iklan.index')
            ->with('success','Iklan created successfully.');
    }

    public function edit($id)
    {
        $iklan = Iklan::find($id);
        $produk = Job::all();
        $sales = Sales::all();
        $platform = SumberPelanggan::where('nama_sumber', 'LIKE' , '%iklan%')->get();

        return view('page.marol.edit', compact('iklan', 'produk', 'sales', 'platform'));
    }
}
