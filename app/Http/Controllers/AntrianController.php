<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Machine;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Employee;

use App\Models\Anservice;
use App\Models\DataKerja;
use App\Models\Ekspedisi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\DataAntrian;
use App\Models\DesignQueue;
use App\Models\Dokumproses;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Models\BiayaProduksi;
use App\Models\Documentation;
use App\Models\BuktiPembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AntrianWorkshop;
use App\Http\Resources\AntrianResource;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AntrianDiantrikan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;


class AntrianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //--------------------------------------------------------------------------
    //Fungsi untuk menampilkan halaman tambah antrian workshop
    //--------------------------------------------------------------------------

    public function index()
    {
        $jobs = Job::all();
        $cabang = Cabang::all();
        $sales = Sales::all();
        return view('page.antrian-workshop.index', compact('jobs', 'cabang', 'sales'));
    }

    public function indexData(Request $request)
    {
        // Descriptive variable names
        $productId = $request->get('produk');
        $branchId = $request->get('cabang');
        $salesId = $request->get('sales');
     // Build the query with eager loading
        $antrians = DataAntrian::with('sales', 'customer', 'job', 'barang', 'dataKerja', 'cabang', 'buktiBayar')
            ->where('status', 1) // Ensure active entries
            ->orderByDesc('created_at');
     // Apply filters if any parameters are provided
        if ($request->has('kategori') || $request->has('cabang') || $request->has('sales')) {
            if ($productId !== null) {
                $antrians->whereHas('barang', function ($query) use ($productId) {
                    $query->where('job_id', $productId);
                });
            }
         if ($branchId !== null) {
                $antrians->where('cabang_id', $branchId);
            }
         if ($salesId !== null) {
                $antrians->where('sales_id', $salesId);
            }
        }
     // Execute the query and return results
        $antrians = $antrians->get();

        return DataTables::of($antrians)
            ->addIndexColumn()
            ->addColumn('ticket_order', function ($antrian) {
                return '<a href="' . route('antrian.show', $antrian->ticket_order) . '">' . $antrian->ticket_order . '</a>';
            })
            ->addColumn('sales', function ($antrian) {
                return $antrian->sales->sales_name;
            })
            ->addColumn('customer', function ($antrian) {
                return $antrian->customer->nama;
            })
            ->addColumn('endJob', function ($antrian) {
                if($antrian->dataKerja->tgl_selesai == null){
                    return '<span class="text-danger">BELUM DIANTRIKAN</span>';
                }else{
                    return '<span class="text-danger">'. $antrian->dataKerja->tgl_selesai .'</span>';
                }
            })
            ->addColumn('operator', function ($antrian) {
                if($antrian->dataKerja->operator_id == null){
                    return '<span class="text-danger">OPERATOR KOSONG</span>';
                }else{
                    //explode string operator
                    $operator = explode(',', $antrian->dataKerja->operator_id);
                    $namaOperator = [];
                    foreach($operator as $o){
                        if($o == 'r'){
                            $namaOperator[] = "<span class='text-primary'>Rekanan</span>";
                        }else{
                            $namaOperator[] = Employee::where('id', $o)->first()->name;
                        }
                    }
                    return implode(', ', $namaOperator);
                }
            })
            ->addColumn('finishing', function ($antrian) {
                if($antrian->dataKerja->finishing_id == null){
                    return '<span class="text-danger">FINISHING KOSONG</span>';
                }else{
                    //explode string finishing
                    $finishing = explode(',', $antrian->dataKerja->finishing_id);
                    $namaFinishing = [];
                    foreach($finishing as $f){
                        if($f == 'r'){
                            $namaFinishing[] = "<span class='text-primary'>Rekanan</span>";
                        }else{
                            $namaFinishing[] = Employee::where('id', $f)->first()->name;
                        }
                    }
                    return implode(', ', $namaFinishing);
                }
            })
            ->addColumn('qc', function ($antrian) {
                if($antrian->dataKerja->qc_id == null){
                    return '<span class="text-danger">QC KOSONG</span>';
                }else{
                    //explode string qc
                    $qc = explode(',', $antrian->dataKerja->qc_id);
                    $namaQc = [];
                    foreach($qc as $q){
                        $namaQc[] = Employee::where('id', $q)->first()->name;
                    }
                    return implode(', ', $namaQc);
                }
            })
            ->addColumn('tempat', function ($antrian) {
                if($antrian->cabang_id == null){
                    return '<span class="text-danger">TEMPAT KOSONG</span>';
                }else{
                    //explode string cabang
                    $cabang = explode(',', $antrian->cabang_id);
                    $namaCabang = [];
                    foreach($cabang as $c){
                        $namaCabang[] = Cabang::where('id', $c)->first()->nama_cabang;
                    }
                    return implode(', ', $namaCabang);
                }
            })
            ->addColumn('action', function ($antrian) {
                $btn = '<div class="btn-group">';
                if(auth()->user()->role == 'admin') {
                    if($antrian->dataKerja->tgl_selesai == null){
                        $btn .= '<a href="javascript:void(0)" class="btn btn-success btn-sm disabled"><i class="fas fa-download"></i> e-SPK</a>';
                    }else{
                        $btn .= '<a href="'.route('antrian.form-espk', $antrian->ticket_order).'"  class="btn btn-success btn-sm"><i class="fas fa-download"></i> e-SPK</a>';
                    }
                    $btn .= '<a href="' . route('antrian.edit', $antrian->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Penugasan</a>';
                    $btn .= '<a href="'.route('antrian.show', $antrian->ticket_order).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</a>';
                    $btn .= '<a href="javascript:void(0)" onclick="deleteAntrian('.$antrian->ticket_order.')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>';
                    
                }
                elseif(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising') {
                    $btn .= '<a href="' . route('documentation.uploadProduksi', $antrian->ticket_order) . '" class="btn btn-warning btn-sm"><i class="fas fa-camera"></i> Unggah Dokumentasi</a>';
                }
                else{
                    $btn .= '<a href="'.route('antrian.show', $antrian->ticket_order).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['ticket_order', 'endJob', 'operator', 'finishing', 'qc', 'tempat', 'action'])
            ->make(true);
    }

    public function indexSelesai(Request $request)
    {
        // Descriptive variable names
        $productId = $request->get('produk');
        $branchId = $request->get('cabang');
        $salesId = $request->get('sales');

        // Build the query with eager loading
        $antrians = DataAntrian::with('sales', 'customer', 'job', 'barang', 'dataKerja', 'cabang', 'buktiBayar')
            ->where('status', 2) // Ensure active entries
            ->orderByDesc('created_at');

        // Apply filters if any parameters are provided
        if ($request->has('kategori') || $request->has('cabang') || $request->has('sales')) {
            if ($productId !== null) {
                $antrians->whereHas('barang', function ($query) use ($productId) {
                    $query->where('job_id', $productId);
                });
            }

            if ($branchId !== null) {
                $antrians->where('cabang_id', $branchId);
            }

            if ($salesId !== null) {
                $antrians->where('sales_id', $salesId);
            }
        }

        // Execute the query and return results
        $antrians = $antrians->get();

        return DataTables::of($antrians)
            ->addIndexColumn()
            ->addColumn('tanggal_order', function ($antrian) {
                return $antrian->created_at->format('d-m-Y');
            })
            ->addColumn('ticket_order', function ($antrian) {
                return '<a href="' . route('antrian.show', $antrian->ticket_order) . '">' . $antrian->ticket_order . '</a>';
            })
            ->addColumn('sales', function ($antrian) {
                return $antrian->sales->sales_name;
            })
            ->addColumn('customer', function ($antrian) {
                return $antrian->customer->nama;
            })
            ->addColumn('keyword', function ($antrian) {
                return $antrian->order->title;
            })
            ->addColumn('action', function ($antrian) {
                $btn = '<div class="btn-group">';
                $btn .= '<a href="'.route('antrian.show', $antrian->ticket_order).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action','ticket_order'])
            ->make(true);
        
    }

    public function buatAntrianWorkshop()
    {
        $ekspedisi = Ekspedisi::all();
        $desain = DesignQueue::where('sales_id', auth()->user()->sales->id)->where('status', 2)->where('data_antrian_id', 0)->get();

        return view('page.antrian-workshop.create', compact('ekspedisi', 'desain'));
    }

    public function printeSpk($id){
        $antrian = DataAntrian::where('ticket_order', $id)->first();
        $dataKerja = DataKerja::where('ticket_order', $id)->first();
        $order = Order::where('ticket_order', $id)->first();
        $customer = Customer::where('id', $antrian->customer_id)->first();
        $sales = Sales::where('id', $antrian->sales_id)->first();
        $job = Job::where('id', $antrian->job_id)->first();
        $barang = Barang::where('ticket_order', $id)->get();
        $cabang = Cabang::where('id', $antrian->cabang_id)->first();

        return view('page.antrian-workshop.modal.modal-form-spk', compact('antrian', 'dataKerja', 'order', 'customer', 'sales', 'job', 'barang', 'cabang'));
    }

    //--------------------------------------------------------------------------
    //Filter antrian berdasarkan kategori pekerjaan
    //--------------------------------------------------------------------------

    public function filterProcess(Request $request)
    {
        $jobType = $request->input('kategori');

        $filtered = $jobType;

        return view('page.antrian-workshop.index', compact('filtered'));
    }

    //--------------------------------------------------------------------------
    //Fungsi untuk menampilkan halaman tambah antrian service
    //--------------------------------------------------------------------------

    public function serviceIndex(){
        $servisBaru = Anservice::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
        ->get();

        return view('page.antrian-service.index', compact('servisBaru'));
    }

    public function serviceCreate(){

        return view('page.antrian-service.create');
    }

    //--------------------------------------------------------------------------
    //Estimator
    //--------------------------------------------------------------------------

    public function estimatorIndex(Request $request)
    {
        if($request->input('kategori')){
            $jobType = $request->input('kategori');
            $filtered = $jobType;

            $fileBaruMasuk = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereHas('job', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->get();

            $progressProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->whereHas('job', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->get();

            $selesaiProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->whereHas('job', function ($query) use ($jobType) {
                $query->where('job_type', $jobType);
            })
            ->where('status', '2')
            ->orderByDesc('created_at')
            ->take(75)
            ->get();

            return view('page.antrian-workshop.estimator-index', compact('fileBaruMasuk', 'progressProduksi', 'selesaiProduksi', 'filtered'));

        }else{

            $filtered = null;

            $fileBaruMasuk = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->where('status', '1')
            ->where('is_aman', '0')
            ->orderByDesc('created_at')
            ->get();

            $progressProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->get();

            $selesaiProduksi = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'dokumproses')
            ->where('status', '2')
            ->orderByDesc('created_at')
            ->take(75)
            ->get();

            return view('page.antrian-workshop.estimator-index', compact('fileBaruMasuk', 'progressProduksi', 'selesaiProduksi'));
        }
    }

    //--------------------------------------------------------------------------
    //Admin Sales
    //--------------------------------------------------------------------------

    public function omsetGlobal()
    {
        $listSales = Sales::all();
        //mengambil tanggal awal dan tanggal akhir dari bulan ini
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        //menyimpan tanggal menjadi array
        $dateRange = [];
        $date = $startDate;
        while($date->lte($endDate)){
            $dateRange[] = $date->format('Y-m-d');
            $date->addDay();
        }

        //mengambil total omset per hari dari seluru sales
        $omsetPerHari = [];
        foreach($dateRange as $date){
            $omset = Antrian::whereDate('created_at', $date)->sum('omset');
            $omsetPerHari[] = $omset;
        }

        return view('page.admin-sales.omset-global', compact('listSales', 'omsetPerHari', 'dateRange'));
    }

    public function downloadPrintFile($id){
        $antrian = Barang::where('id', $id)->first();
        if($antrian->file_cetak == null){
            return redirect()->back()->with('error', 'File cetak tidak ditemukan !');
        }
        $file = $antrian->barang->file_cetak;
        $path = storage_path('app/public/file-cetak/' . $file);
        return response()->download($path);
    }

    public function downloadPrintFileCreate($id){
        $order = Order::where('id', $id)->first();
        $file = $order->file_cetak;
        $path = storage_path('app/public/file-cetak/' . $file);
        return response()->download($path);
    }

    public function downloadProduksiFile($id){
        $antrian = Antrian::where('id', $id)->first();
        $file = $antrian->design->filename;
        $path = storage_path('app/public/file-jadi/' . $file);
        return response()->download($path);
    }

    public function downloadFilePendukung($id){
        $antrian = DataAntrian::where('id', $id)->first();
        $file = $antrian->filePendukung->nama_file;
        $path = storage_path('app/public/file-pendukung/' . $file);
        return response()->download($path);
    }

    public function simpanAntrian(Request $request)
    {
        $lastId = DataAntrian::latest()->first();
        if($lastId == null){
            $lastId = 1;
        }else{
            $lastId = $lastId->id + 1;
        }
        $ticketOrder = Carbon::now()->format('Ymd') . $lastId;

        //simpan antrian
        $antrian = new DataAntrian();
        $antrian->ticket_order = $ticketOrder;
        $antrian->sales_id = auth()->user()->sales->id;
        $antrian->customer_id = $request->input('customer_id');
        $antrian->status = 1;
        $antrian->save();

        //simpan pembayaran
        $payment = new Pembayaran();
        $payment->ticket_order = $ticketOrder;
        $payment->metode_pembayaran = $request->input('metodePembayaran');
        $payment->biaya_packing = $request->input('biayaPacking') != null ? CustomHelper::removeCurrencyFormat($request->input('biayaPacking')) : 0;
        $payment->biaya_pasang = $request->input('biayaPasang') != null ? CustomHelper::removeCurrencyFormat($request->input('biayaPasang')) : 0;
        $payment->diskon = $request->input('diskon') != null ? CustomHelper::removeCurrencyFormat($request->input('diskon')) : 0;
        $payment->total_harga = CustomHelper::removeCurrencyFormat($request->input('totalAllInput'));
        $payment->dibayarkan = CustomHelper::removeCurrencyFormat($request->input('jumlahPembayaran'));
        $payment->status_pembayaran = $request->input('statusPembayaran');
        $payment->save();

        //simpan pengiriman
        //jika ada ekspedisi yang dipilih, maka simpan data pengiriman
        if($request->input('ekspedisi') != null && $request->input('ongkir') != null){
            $pengiriman = new Pengiriman();
            $pengiriman->ticket_order = $ticketOrder;
            $pengiriman->ongkir = CustomHelper::removeCurrencyFormat($request->input('ongkir'));
            $pengiriman->no_resi = $request->input('noResi');
            $pengiriman->ekspedisi = $request->input('ekspedisi');
            $pengiriman->alamat_pengiriman = $request->input('alamatKirim');
            if($request->input('ekspedisi') == 'LAIN'){
                $pengiriman->nama_ekspedisi = $request->input('namaEkspedisi');
            }
            $pengiriman->save();
        }

        //simpan bukti pembayaran
        $namaBaru = null;
        if($request->file('paymentImage') == null){
            $buktiPembayaran = null;
        }else{
            $buktiPembayaran = $request->file('paymentImage');
            $namaBuktiPembayaran = $buktiPembayaran->getClientOriginalName();
            $namaBaru = time() . '_' . $namaBuktiPembayaran;
            $path = 'bukti-pembayaran/' . $namaBaru;
            Storage::disk('public')->put($path, file_get_contents($buktiPembayaran));
        }

        $bukti = new BuktiPembayaran();
        $bukti->ticket_order = $ticketOrder;
        $bukti->gambar = $namaBaru;
        $bukti->save();

        //simpan data kerja
        $dataKerja = new DataKerja();
        $dataKerja->ticket_order = $ticketOrder;
        $dataKerja->save();

        //jika antrian berhasil disimpan, customer frekuensi order ditambah 1
        $customer = Customer::where('id', $request->input('customer_id'))->first();
        //Jika customer melakukan order lebih dari 1 kali pada hari yang sama, maka frekuensi order hanya bertambah 1 kali
        if($antrian->customer_id == $customer->id && $antrian->created_at->format('Y-m-d') == Carbon::now()->format('Y-m-d')){
            $customer->frekuensi_order += 0;
        }else{
            $customer->frekuensi_order = 1;
        }
        $customer->save();

        return redirect()->route('antrian.index')->with('success', 'Data antrian, berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        //Mencari data order berdasarkan id order yang diinputkan
        $order = Order::where('id', $request->input('idOrder'))->first();
        $ticketOrder = $order->ticket_order;

        //Melakukan Check Antrian
        $checkAntrian = Antrian::where('ticket_order', $ticketOrder)->first();
        if($checkAntrian){
            return redirect()->back()->with('error', 'Data antrian sudah ada !');
        }

        //Mengambil data customer berdasarkan nama customer yang diinputkan
        $idCustomer = Customer::where('id', $request->input('nama'))->first();
        if($idCustomer){
            //Jika customer sudah ada, maka frekuensi order ditambah 1
            $repeat = $idCustomer->frekuensi_order + 1;
            $idCustomer->frekuensi_order = $repeat;
            $idCustomer->save();
        }

        //Jika ada request file bukti pembayaran, maka simpan file tersebut
        if($request->file('buktiPembayaran')){
            $buktiPembayaran = $request->file('buktiPembayaran');
            $namaBuktiPembayaran = $buktiPembayaran->getClientOriginalName();
            $namaBuktiPembayaran = time() . '_' . $namaBuktiPembayaran;
            $path = 'bukti-pembayaran/' . $namaBuktiPembayaran;
            Storage::disk('public')->put($path, file_get_contents($buktiPembayaran));
        }else{
            $namaBuktiPembayaran = null;
        }
            //Membuat payment baru dan menyimpan data pembayaran
            $payment = new Payment();
            $payment->ticket_order = $ticketOrder;
            $totalPembayaran = str_replace(['Rp ', '.'], '', $request->input('totalPembayaran'));
            $pembayaran = str_replace(['Rp ', '.'], '', $request->input('jumlahPembayaran'));

            // menyimpan inputan biaya jasa pengiriman
            if($request->input('biayaPengiriman') == null){
                $biayaPengiriman = 0;
            }else{
                $biayaPengiriman = str_replace(['Rp ', '.'], '', $request->input('biayaPengiriman'));
            }

            // menyimpan inputan biaya jasa pemasangan
            if($request->input('biayaPemasangan') == null){
                $biayaPemasangan = 0;
            }else{
                $biayaPemasangan = str_replace(['Rp ', '.'], '', $request->input('biayaPemasangan'));
            }

            // menyimpan inputan biaya jasa pengemasan
            if($request->input('biayaPengemasan') == null){
                $biayaPengemasan = 0;
            }else{
                $biayaPengemasan = str_replace(['Rp ', '.'], '', $request->input('biayaPengemasan'));
            }

            // menyimpan inputan sisa pembayaran
            $sisaPembayaran = str_replace(['Rp ', '.'], '', $request->input('sisaPembayaran'));

            // Menyimpan file purcase order
            $namaPurchaseOrder = null;
            if($request->file('filePO')){
                $purchaseOrder = $request->file('filePO');
                $namaPurchaseOrder = $purchaseOrder->getClientOriginalName();
                $namaPurchaseOrder = time() . '_' . $namaPurchaseOrder;
                $path = 'purchase-order/' . $namaPurchaseOrder;
                Storage::disk('public')->put($path, file_get_contents($purchaseOrder));
            }else{
                $namaPurchaseOrder = null;
            }

            $payment->total_payment = $totalPembayaran;
            $payment->payment_amount = $pembayaran;
            $payment->shipping_cost = $biayaPengiriman;
            $payment->installation_cost = $biayaPemasangan;
            $payment->remaining_payment = $sisaPembayaran;
            $payment->payment_method = $request->input('jenisPembayaran');
            $payment->payment_status = $request->input('statusPembayaran');
            $payment->payment_proof = $namaBuktiPembayaran;
            $payment->save();


        $accDesain = $request->file('accDesain');
        $namaAccDesain = $accDesain->getClientOriginalName();
        $namaAccDesain = time() . '_' . $namaAccDesain;
        $path = 'acc-desain/' . $namaAccDesain;
        Storage::disk('public')->put($path, file_get_contents($accDesain));

        $order->acc_desain = $namaAccDesain;
        $order->toWorkshop = 1;
        $order->save();

        $hargaProduk = str_replace(['Rp ', '.'], '', $request->input('hargaProduk'));
        $omset = ((int)$hargaProduk * (int)$request->input('qty')) + (int)$biayaPemasangan + (int)$biayaPengemasan;

        $antrian = new Antrian();
        $antrian->ticket_order = $ticketOrder;
        $antrian->sales_id = $request->input('sales');
        $antrian->customer_id = $idCustomer->id;
        $antrian->job_id = $request->input('namaPekerjaan');
        $antrian->note = $request->input('keterangan');
        $antrian->omset = $omset;
        $antrian->qty = $request->input('qty');
        $antrian->order_id = $request->input('idOrder');
        $antrian->alamat_pengiriman = $request->input('alamatPengiriman') ? $request->input('alamatPengiriman') : null;
        $antrian->file_po = $namaPurchaseOrder == null ? null : $namaPurchaseOrder;
        $antrian->harga_produk = $hargaProduk;
        $antrian->packing_cost = $biayaPengemasan;
        $antrian->save();


        $user = User::where('role', 'admin')->first();
        $user->notify(new AntrianWorkshop($antrian, $order, $payment));
        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array('admin'),
            array("web" => array("notification" => array(
              "title" => "📣 Cek sekarang, ada antrian baru !",
              "body" => "Cek antrian workshop sekarang, jangan sampai lupa diantrikan ya !",
            )),
        ));

        return redirect()->route('antrian.index')->with('success', 'Data antrian berhasil ditambahkan!');
     }

    public function edit($id)
    {
        $antrian = DataAntrian::where('id', $id)->first();

        $operators = Employee::where('can_stempel', 1)->orWhere('can_adv', 1)->get();
        $qualitys = Employee::where('can_qc', 1)->get();

        //Melakukan explode pada operator_id, finisher_id, dan qc_id
        $operatorId = explode(',', $antrian->dataKerja->operator_id);
        $finishingId = explode(',', $antrian->dataKerja->finishing_id);
        $qualityId = explode(',', $antrian->dataKerja->qc_id);
        $cabangId = explode(',', $antrian->cabang_id);

        $machines = Machine::get();

        $totalHargaBarang = 0;
        $barangs = Barang::where('ticket_order', $antrian->ticket_order)->get();
        foreach($barangs as $barang){
            $totalHargaBarang += $barang->price * $barang->qty;
        }
        $totalHargaBarang = number_format($totalHargaBarang, 0, ',', '.');
        $totalBarang = $barangs->sum('qty') . ' pcs';

        $tempatCabang = Cabang::pluck('nama_cabang', 'id');

        if($antrian->end_job == null){
            $isEdited = 0;
        }else{
            $isEdited = 1;
        }

        return view('page.antrian-workshop.edit', compact('antrian', 'operatorId', 'finishingId', 'qualityId', 'cabangId', 'operators', 'qualitys', 'machines', 'tempatCabang', 'isEdited', 'totalHargaBarang', 'totalBarang'));
    }

    public function update(Request $request, $id)
    {

        $antrian = DataAntrian::find($id);
        $dataKerja = DataKerja::where('ticket_order', $antrian->ticket_order)->first();

        //Jika input operator adalah array, lakukan implode lalu simpan ke database
        $operator = implode(',', $request->input('operator_id'));
        $dataKerja->operator_id = $operator;

        //Jika input finisher adalah array, lakukan implode lalu simpan ke database
        $finisher = implode(',', $request->input('finishing_id'));
        $dataKerja->finishing_id = $finisher;

        //Jika input quality adalah array, lakukan implode lalu simpan ke database
        $quality = implode(',', $request->input('qc_id'));
        $dataKerja->qc_id = $quality;

        //start_job diisi dengan waktu sekarang
        $dataKerja->tgl_mulai = $request->input('start_job');
        $dataKerja->tgl_selesai = $request->input('end_job');

        //Jika input mesin adalah array, lakukan implode lalu simpan ke database
        if($request->input('jenisMesin')){
            $mesin = implode(',', $request->input('jenisMesin'));
            $dataKerja->machine_id = $mesin;
        }
        $dataKerja->save();

        //Jika input tempat adalah array, lakukan implode lalu simpan ke database
        $tempat = implode(',', $request->input('cabang_id'));
        $antrian->cabang_id = $tempat;

        $antrian->admin_note = $request->input('admin_note');
        $antrian->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $users = [];

        foreach($request->input('operator_id') as $operator){
            $user = 'user-' . $operator;
            $users[] = $user;
        }

        foreach($request->input('finishing_id') as $finisher){
            $user = 'user-' . $finisher;
            $users[] = $user;
        }

        foreach($request->input('qc_id') as $quality){
            $user = 'user-' . $quality;
            $users[] = $user;
        }
        // if($request->isEdited == 0){
        //     foreach($users as $user){
        //         $publishResponse = $beamsClient->publishToUsers(
        //             array($user),
        //             array("web" => array("notification" => array(
        //             "title" => "📣 Cek sekarang, ada pekerjaan baru !",
        //             "body" => "Cek pekerjaan baru sekarang, semangattt !",
        //             )),
        //         ));

        //         $user = str_replace('user-', '', $user);
        //         $user = User::find($user);
        //         if($user != 'rekananSBY' || $user != 'rekananKDR' || $user != 'rekananMLG'){
        //             $user->notify(new AntrianDiantrikan($antrian));
        //         }
        //     }
        // }else{
        //     foreach($users as $user){
        //         if($user != 'user-rekananSBY' || $user != 'user-rekananKDR' || $user != 'user-rekananMLG'){
        //             $publishResponse = $beamsClient->publishToUsers(
        //                 array($user),
        //                 array("web" => array("notification" => array(
        //                 "title" => "📣 Hai, ada update antrian!",
        //                 "body" => "Ada perubahan pada antrian " . $antrian->ticket_order . " (" . $antrian->order->title ."), cek sekarang !",
        //                 )),
        //             ));
        //         }

        //         $user = str_replace('user-', '', $user);
        //         $user = User::find($user);
        //         if($user != 'rekananSBY' || $user != 'rekananKDR' || $user != 'rekananMLG'){
        //             $user->notify(new AntrianDiantrikan($antrian));
        //         }
        //     }
        // }

        return redirect()->route('antrian.index')->with('success-update', 'Data antrian berhasil diupdate!');
    }

    public function show($id)
    {
        $antrian = DataAntrian::where('ticket_order', $id)->with('sales', 'customer', 'job', 'barang', 'dataKerja', 'pembayaran', 'estimator')->first();

        $items = Barang::where('ticket_order', $id)->get();

        $pembayaran = Pembayaran::where('ticket_order', $id)->first();

        $pengiriman = Pengiriman::where('ticket_order', $id)->first();

        $ekspedisi = Ekspedisi::all();

        $designQueue = DesignQueue::where('data_antrian_id', $antrian->id)->first();

        $omset = $pembayaran->total_harga;

        $satuPersen = 1;
        $duaPersen = 2;
        $duaSetengahPersen = 2.5;
        $tigaPersen = 3;
        $limaPersen = 5;
        
        $total = 0;

        foreach($items as $item){
            $subtotal = $item->price * $item->qty;
            $total += $subtotal;
        }

        $bahan = Bahan::where('ticket_order', $id)->get();

        $totalBahan = 0;

        foreach($bahan as $b){
            $totalBahan += $b->harga;
        }

        $biayaSales = ($omset * $tigaPersen) / 100;
        $biayaDesain = ($omset * $duaPersen) / 100;
        $biayaPenanggungJawab = ($omset * $tigaPersen) / 100;
        $biayaPekerjaan = ($omset * $limaPersen) / 100;
        $biayaBPJS = ($omset * $duaSetengahPersen) / 100;
        $biayaTransportasi = ($omset * $satuPersen) / 100;
        $biayaOverhead = ($omset * $duaSetengahPersen) / 100;
        $biayaAlatListrik = ($omset * $duaPersen) / 100;

        $totalBiaya = $biayaSales + $biayaDesain + $biayaPenanggungJawab + $biayaPekerjaan + $biayaBPJS + $biayaTransportasi + $biayaOverhead + $biayaAlatListrik;

        $profit = $omset - $totalBiaya;

        $sisaPembayaran = $total - $pembayaran->dibayarkan;

        return view('page.antrian-workshop.show', compact('antrian', 'total', 'items', 'pembayaran' , 'bahan', 'totalBahan', 'biayaSales', 'biayaDesain', 'biayaPenanggungJawab', 'biayaPekerjaan', 'biayaBPJS', 'biayaTransportasi', 'biayaOverhead', 'biayaAlatListrik', 'totalBiaya', 'profit', 'pengiriman', 'ekspedisi', 'sisaPembayaran'));
    }

    public function updateDeadline(Request $request)
    {
        $antrian = Antrian::find($request->id);
        if (now() > $antrian->end_job) {
            $status = 2;
        }
        $antrian->deadline_status = $status;
        $antrian->save();

        return response()->json(['message' => 'Success'], 200);
    }

    public function destroy($id)
    {
        // Melakukan pengecekan otorisasi sebelum menghapus antrian
        $this->authorize('delete', DataAntrian::class);

        $antrian = DataAntrian::where('ticket_order', $id)->first();

        $order = Order::where('ticket', $id)->first();
        $order->toWorkshop = 0;
        $order->save();

        if ($antrian) {
            $antrian->delete();
            return redirect()->route('antrian.index')->with('success-delete', 'Data antrian berhasil dihapus!');
        } else {
            return redirect()->route('antrian.index')->with('error-delete', 'Data antrian gagal dihapus!');
        }
    }
    //--------------------------------------------------------------------------

    public function design(){
        //Melarang akses langsung ke halaman ini sebelum login
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('belum-login', 'Silahkan login terlebih dahulu');
        }

        $list_desain = AntrianDesain::get();
        return view('antriandesain.index', compact('list_desain'));
    }

    public function tambahDesain(){
        $list_antrian = Antrian::get();
        return view('antriandesain.create', compact('list_antrian'));
    }

//fungsi untuk menggunggah & menyimpan file gambar dokumentasi
    public function showDokumentasi($id){
        $antrian = DataAntrian::where('ticket_order', $id)->first();
        return view ('page.antrian-workshop.dokumentasi' , compact('antrian'));
    }

    public function storeDokumentasi(Request $request){
        $files = $request->file('files');
        $id = $request->input('idAntrian');

        foreach($files as $file){
            $filename = time()."_".$file->getClientOriginalName();
            $path = 'dokumentasi/'.$filename;
            Storage::disk('public')->put($path, file_get_contents($file));

            $dokumentasi = new Documentation();
            $dokumentasi->antrian_id = $id;
            $dokumentasi->filename = $filename;
            $dokumentasi->type_file = $file->getClientOriginalExtension();
            $dokumentasi->path_file = $path;
            $dokumentasi->job_id = $request->input('jobType');
            $dokumentasi->save();
        }

        return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function getMachine(Request $request){
        //Menampilkan data mesin pada tabel Machines
        $search = $request->input('search');

        if($search == ''){
            $machines = Machine::get();
        }else{
            $machines = Machine::where('machine_name', 'like', '%'.$search.'%')->get();
        }

        $response = array();
        foreach($machines as $machine){
            $response[] = array(
                "id" => $machine->id,
                "text" => $machine->machine_name
            );
        }

        return response()->json($response);
    }

    public function showProgress($id){
        $antrian = Antrian::where('id', $id)->with('job', 'sales', 'order')
        ->first();

        return view('page.antrian-workshop.progress', compact('antrian'));
    }

    public function storeProgressProduksi(Request $request){
        $antrian = Antrian::where('id', $request->input('idAntrian'))->first();

        if($request->file('fileGambar')){
        $gambar = $request->file('fileGambar');
        $namaGambar = time()."_".$gambar->getClientOriginalName();
        $pathGambar = 'dokum-proses/'.$namaGambar;
        Storage::disk('public')->put($pathGambar, file_get_contents($gambar));
        }else{
            $namaGambar = null;
        }

        if($request->file('fileVideo')){
        $video = $request->file('fileVideo');
        $namaVideo = time()."_".$video->getClientOriginalName();
        $pathVideo = 'dokum-proses/'.$namaVideo;
        Storage::disk('public')->put($pathVideo, file_get_contents($video));
        }else{
            $namaVideo = null;
        }

        $dokumProses = new Dokumproses();
        $dokumProses->note = $request->input('note');
        $dokumProses->file_gambar = $namaGambar;
        $dokumProses->file_video = $namaVideo;
        $dokumProses->antrian_id = $request->input('idAntrian');
        $dokumProses->save();

        return redirect()->route('antrian.index');
    }

    public function markAman($id)
    {
        $design = Antrian::find($id);
        $design->is_aman = 1;
        $design->save();

        return redirect()->back()->with('success', 'File berhasil di tandai aman');
    }

    public function markSelesai($id){
        //cek apakah documentasi sudah diupload
        $barang = Barang::where('ticket_order', $id)->get();

        foreach($barang as $b){
            if($b->documentation_id == null){
                return redirect()->back()->with('error', 'Ada barang yang belum di dokumentasi !');
            }
        }

        $datakerja = DataKerja::where('ticket_order', $id)->first();
        if($datakerja->operator_id == null || $datakerja->finishing_id == null || $datakerja->qc_id == null){
            return redirect()->back()->with('error', 'Data penugasan belum lengkap !');
        }

        //cek apakah waktu sekarang sudah melebihi waktu deadline
        $antrian = DataAntrian::where('ticket_order', $id)->first();
        $antrian->finish_date = Carbon::now();
        $antrian->status = 2;
        $antrian->save();

         // Menampilkan push notifikasi saat selesai
         $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("sales"),
            array("web" => array("notification" => array(
              "title" => "Antree",
              "body" => "Yuhuu! Pekerjaan dengan tiket " . $antrian->ticket_order . " (" . $antrian->order->title ."), dari sales ". $antrian->sales->sales_name ." udah selesai !",
              "deep_link" => "https://app.kassabsyariah.com/",
            )),
        ));

        return redirect()->route('antrian.index')->with('success', 'Berhasil ditandai selesai !');
    }

    public function biayaProduksiSelesai(Request $request, $id)
    {
        $antrian = DataAntrian::where('ticket_order', $id)->first();
        $antrian->done_production_at = Carbon::now();
        $antrian->estimator_id = auth()->user()->employee->id;

        $omset = $request->input('omsetTotal');
        $omset = str_replace(['Rp', '.'], '', $omset);
        $omset = (int)$omset;

        $bproduksi = new BiayaProduksi();
        $bproduksi->ticket_order = $id;
        $bproduksi->biaya_sales = $omset * 0.03;
        $bproduksi->biaya_desain = $omset * 0.02;
        $bproduksi->biaya_penanggung_jawab = $omset * 0.03;
        $bproduksi->biaya_pekerjaan = $omset * 0.05;
        $bproduksi->biaya_bpjs = $omset * 0.025;
        $bproduksi->biaya_transportasi = $omset * 0.01;
        $bproduksi->biaya_overhead = $omset * 0.025;
        $bproduksi->biaya_alat_listrik = $omset * 0.02;
        $bproduksi->save();
        $antrian->save();

        return response()->json(['message' => 'Biaya Produksi berhasil disimpan !'], 200);
    }
}
