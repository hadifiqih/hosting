<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Iklan;
use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\SumberPelanggan;

class IklanController extends Controller
{
    public function index()
    {
        $expiredAds = Iklan::where('tanggal_selesai', '<=', now())->where('status', 1)->get();

        if($expiredAds != null){
            foreach($expiredAds as $ads){
                $ads->status = 2;
                $ads->save();
            }
        }

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
                $sumber = SumberPelanggan::where('code_sumber', 'LIKE', $row->platform)->first();
                return $sumber->nama_sumber;
            })
            ->addColumn('biaya_iklan', function($row){
                $biaya_iklan = 'Rp. '.number_format($row->biaya_iklan,0,',','.');
                return $biaya_iklan;
            })
            ->addColumn('status', function($row){
                $status = $row->status;
                if($status == 1){
                    return '<div class="text-center"><span class="badge bg-success">Aktif</span></div>';
                }else{
                    return '<div class="text-center"><span class="badge bg-secondary">Selesai</span></div>';
                }
            })
            ->addColumn('action', function($row){
                $actionBtn = '<a href="'. route('iklan.edit', $row->id) .'" class="edit btn btn-warning btn-sm"><i class="fas fa-pen"></i> Edit</a> <a onclick="deleteDataIklan('.$row->id.')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>';
                return $actionBtn;
            })
            ->rawColumns(['marol', 'nama_sales', 'nama_produk', 'action', 'platform', 'status'])
            ->make(true);
    }

    public function create()
    {
        $produk = Job::all();
        $sales = Sales::all();
        $platform = SumberPelanggan::where('nama_sumber', 'LIKE' , '%iklan%')->get();
        $marol = User::where('role_id', 12)->get();
        return view('page.marol.create', compact('produk', 'sales', 'platform', 'marol'));
    }

    public function store(Request $request)
    {
        try{
        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'nama_produk' => 'required',
            'nama_sales' => 'required',
            'platform' => 'required',
            'biaya_iklan' => 'required',
        ]);

        //Generate Nomor Iklan - Format: platform-tanggal-angkaUnik
        $tanggal = date('dmy');
        $angkaUnik = rand(100,999);
        $nomorIklan = $request->platform.'-'.$tanggal.'-'.$angkaUnik;

        //hapus Rp dan titik pada biaya iklan
        $biayaIklan = str_replace(['Rp', '.'], '', $request->biaya_iklan);
        //string to decimal biaya iklan
        $biayaIklan = (float) $biayaIklan;


        $iklan = new Iklan;
        $iklan->user_id = $request->marol;
        $iklan->nomor_iklan = $nomorIklan;
        $iklan->tanggal_mulai = $request->tanggal_mulai;
        $iklan->tanggal_selesai = $request->tanggal_selesai;
        $iklan->job_id = $request->nama_produk;
        $iklan->sales_id = $request->nama_sales;
        $iklan->platform = $request->platform;
        $iklan->biaya_iklan = $biayaIklan;
        $iklan->save();

        return redirect()->route('iklan.index')
            ->with('success','Data Iklan berhasil ditambahkan !');
        } catch (\Exception $e) {
            return redirect()->route('iklan.index')
            ->with('error','Data Iklan gagal ditambahkan !');
        }
    }

    public function edit($id)
    {
        $iklan = Iklan::find($id);
        $produk = Job::all();
        $sales = Sales::all();
        $marol = User::where('role_id', 12)->get();
        $platform = SumberPelanggan::where('nama_sumber', 'LIKE' , '%iklan%')->get();

        return view('page.marol.edit', compact('iklan', 'produk', 'sales', 'platform', 'marol'));
    }

    public function update($id)
    {
        //hapus Rp dan titik pada biaya iklan
        $biayaIklan = str_replace(['Rp', '.'], '', request('biaya_iklan'));
        //string to decimal biaya iklan
        $biayaIklan = (float) $biayaIklan;

        try{
        $iklan = Iklan::find($id);
        $iklan->user_id = request('marol');
        $iklan->tanggal_mulai = request('tanggal_mulai');
        $iklan->tanggal_selesai = request('tanggal_selesai');
        $iklan->job_id = request('nama_produk');
        $iklan->sales_id = request('nama_sales');
        $iklan->platform = request('platform');
        $iklan->biaya_iklan = $biayaIklan;
        $iklan->save();

        return redirect()->route('iklan.index')
            ->with('success','Data Iklan berhasil diupdate !');
        } catch (\Exception $e) {
            return redirect()->route('iklan.index')
            ->with('error','Data Iklan gagal diupdate !');
        }
    }

    public function show($id)
    {
        $iklan = Iklan::with('job')->where('sales_id', $id)->where('status', 1)->get();

        return response()->json($iklan);
    } 
}
