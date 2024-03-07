<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Barang;
use App\Models\RefDesain;
use App\Models\BarangIklan;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\DB;
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
        if($request->file('acc_desain') == null ){
            $fileName = null;
        }
        else{
            $file = $request->file('acc_desain');
            $fileName = time().'_'.$file->getClientOriginalName();
            $pathGambar = 'acc_desain/'.$fileName;
            Storage::disk('public')->put($pathGambar, file_get_contents($file));
        }

        //cek apakah user memiliki relasi dengan tabel sales
        
        $barang = new Barang();
        $barang->ticket_order = $request->ticket_order;
        $barang->kategori_id = $request->kategoriProduk;
        $barang->job_id = $request->namaProduk;
        $barang->user_id = auth()->user()->id;
        $barang->price = $harga;
        $barang->qty = $request->qty;
        $barang->note = $request->keterangan;
        $barang->accdesain = $fileName;
        $barang->iklan_id = $request->periode_iklan ? $request->periode_iklan : null;
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
            return '<div class="spesifikasi">'. $row->note .'</div>';
        })
        ->rawColumns(['nama_produk', 'note', 'subtotal'])
        ->make(true);
    }

    public function showCreate(string $id)
    {
        $items = Barang::where('ticket_order', $id)->with('job')->get();

        return DataTables::of($items)
        ->addIndexColumn()
        ->addColumn('kategori', function($row){
            return $row->job->kategori->nama_kategori;
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
        ->addColumn('accdesain', function($row){
            if($row->accdesain == null){
                return '<span class="text-danger">Tidak ada file</span>';
            }else{
                return '<a href="'. asset($row->accdesain).'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
            }
        })
        ->addColumn('action', function($row){
            $btn = '<div class="btn-group">';
            $btn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="editBarang('.$row->id.')"><i class="fas fa-edit"></i></a>';
            $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteBarang('.$row->id.')"><i class="fas fa-trash"></i></a>';
            $btn .= '</div>';
            return $btn;
        })
        ->rawColumns(['action', 'hargaTotal', 'accdesain'])
        ->make(true);
    }

    public function getTotalBarang(string $id)
    {
        $totalBarang = Barang::where('ticket_order', $id)->get();

        $total = 0;
        foreach($totalBarang as $item){
            $total += $item->qty * $item->price;
        }

        $total = 'Rp '.number_format($total, 0, ',', '.');

        return response()->json([
            'success' => true,
            'totalBarang' => $total,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCreate(string $id)
    {
        $barang = Barang::with('job')->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'barang' => $barang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCreate(Request $request)
    {
        $harga = CustomHelper::removeCurrencyFormat($request->harga);

        //rename file acc_desain
        if($request->file('acc_desain') == null ){
            $fileName = null;
        }
        else{
            $file = $request->file('acc_desain');
            $fileName = time().'_'.$file->getClientOriginalName();
            $pathGambar = 'acc_desain/'.$fileName;
            Storage::disk('public')->put($pathGambar, file_get_contents($file));
        }
        
        //cek apakah user memiliki relasi dengan tabel sales
        $barang = Barang::find($request->id);
        if($barang != null){
            $barang->price = $harga;
            $barang->qty = $request->qty;
            $barang->note = $request->keterangan;
            $barang->accdesain = $fileName;
            $barang->save();
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan !',
            ]);
        }

        if(isset($request->tahunIklan) || $request->tahunIklan != null){
            $tahun = $request->tahunIklan;
            $bulan = $request->bulanIklan;
            $periode = $tahun.'-'.$bulan;

            $cekIklan = BarangIklan::where('barang_id', $request->id)->first();
            if($cekIklan != null){
                $iklan = BarangIklan::find($cekIklan->id);
                $iklan->periode_iklan = $periode;
                $iklan->save();
            }else{
                $iklan = new BarangIklan;
                $iklan->periode_iklan = $periode;
                $iklan->barang_id = $request->id;
                $iklan->sales_id = $barang->user->sales->id;
                $iklan->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        
        //hapus file acc_desain
        if($barang->accdesain != null){
            Storage::disk('public')->delete($barang->accdesain);
        }

        //hapus file refdesain
        if($barang->refdesain != null){
            Storage::disk('public')->delete($barang->refdesain->path);
            $barang->refdesain->delete();
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus !',
        ]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
    }

    public function getBarangById(string $id)
    {
        $barang = Barang::with('documentation')->find($id);

        return response()->json([
            'success' => true,
            'barang' => $barang,
        ]);
    }

    public function simpanBarangDariDesain(Request $request)
    {
        $fileRefDesain = $request->file('refdesain');
        $fileName = time().'_'.$fileRefDesain->getClientOriginalName();
        $pathGambar = 'ref-desain/'.$fileName;

        $refdesain = new RefDesain();
        $refdesain->filename = $fileName;
        $refdesain->path = $pathGambar;
        $refdesain->ticket_order = $request->ticket_order;
        $refdesain->save();

        $barang = new Barang();
        $barang->ticket_order = $request->ticket_order;
        $barang->kategori_id = $request->kategoriProduk;
        $barang->job_id = $request->jenisProduk;
        $barang->user_id = auth()->user()->id;
        $barang->qty = $request->qty;
        $barang->note = $request->note;
        $barang->refdesain_id = $refdesain->id;
        $barang->save();
        
        Storage::disk('public')->put($pathGambar, file_get_contents($fileRefDesain));

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan !',
        ]);
    }

    public function getBarangByTicket($id)
    {
        $barang = Barang::where('ticket_order', $id)->with('refdesain')->get();

        return Datatables::of($barang)
        ->addIndexColumn()
        ->addColumn('jenis_produk', function($row){
            return $row->job->job_name;
        })
        ->addColumn('kategori_produk', function($row){
            return $row->job->kategori->nama_kategori;
        })
        ->addColumn('refdesain', function($row){
            if($row->refdesain == null){
                return '<span class="text-danger">Tidak ada file</span>';
            }else{
                return '<a href="'. asset('storage/'. $row->refdesain->path).'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
            }
        })
        ->addColumn('keterangan', function($row){
            return $row->note;
        })
        ->addColumn('jumlah', function($row){
            return $row->qty;
        })
        ->addColumn('action', function($row){
            $btn = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="deleteBarang('.$row->id.')"><i class="fas fa-trash"></i></a>';
            return $btn;
        })
        ->rawColumns(['refdesain', 'action'])
        ->make(true);
    }

    public function uploadCetak($id)
    {
        $barang = Barang::find($id);

        return view('page.antrian-desain.upload-file-cetak', compact('barang'));
    }

    public function unggahCetak(Request $request)
    {
        if($request->file('fileCetak')){
            $fileCetak = $request->file('fileCetak');
            $namaFile = time().'_'.$fileCetak->getClientOriginalName();
            $pathGambar = 'file-cetak/'.$namaFile;
            Storage::disk('public')->put($pathGambar, file_get_contents($fileCetak));
        }else{
            $pathGambar = null;
        }

        if($request->linkFile){
            $url = $request->linkFile;
        }
        else{
            $url = null;
        }

        $barang = Barang::findOrFail($request->barangId);
        $barang->file_cetak = $pathGambar;
        $barang->link_file_cetak = $url;
        $barang->save();

        //check apakah file cetak sudah diunggah semua
        $cekBarang = Barang::where('ticket_order', $barang->ticket_order)->get();
        $cek = 0;
        foreach($cekBarang as $item){
            if($item->file_cetak != null || $item->link_file_cetak != null){
                $cek++;
            }
        }

        if($cek == $cekBarang->count()){
            $order = Order::where('ticket_order', $barang->ticket_order)->first();
            $order->status = 2;
            $order->save();
        }else{
            $order = Order::where('ticket_order', $barang->ticket_order)->first();
            $order->status = 1;
            $order->save();
        }

        return redirect()->route('design.index')->with('success', 'File cetak berhasil diunggah !');
    }

    public function tugaskanDesainer(Request $request)
    {
        $desainer = $request->desainer_id;
        $barang = Barang::findOrFail($request->barang_id);
        $barang->desainer_id = $desainer;
        $barang->save();

        $user = User::findOrFail($desainer);
        $user->design_load += 1;
        $user->save();

        //return json response status success
        return response()->json([
            'success' => true,
        ]);
    }

    public function ubahDesainer(Request $request)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $barang = Barang::findOrFail($request->barangId);
            
            // Kurangi design load desainer lama
            $oldDesainer = User::findOrFail($barang->desainer_id);
            $oldDesainer->decrement('design_load');

            // Tambah design load desainer baru
            $newDesainer = User::findOrFail($request->desainer);
            $newDesainer->increment('design_load');

            // Ganti desainer pada barang
            $barang->desainer_id = $request->desainer;
            $barang->save();

            // Commit transaksi
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Desainer berhasil diganti!'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengganti desainer.'
            ]);
        }
    }
}
