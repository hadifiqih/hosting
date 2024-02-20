<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Job;
use App\Models\User;
use App\Models\Iklan;
use App\Models\Order;
use App\Models\Sales;

use App\Models\Barang;
use App\Models\Design;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Ekspedisi;

use App\Models\GambarAcc;
use App\Models\PrintFile;
use App\Models\DataAntrian;
use Illuminate\Http\Request;
use App\Notifications\AntrianDesain;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Events\SendGlobalNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id){
        $order = Order::with('sales', 'user', 'employee', 'job', 'barang')->where('id', $id)->first();

        $jenisProduk = '';
        if($order->job_id != 0){
            //explode string to array
            $job = explode(',', $order->job_id);
            foreach($job as $j){
                $jobName = Job::where('id', $j)->first();
                $jobName = $jobName->job_name . ', ';
                $jenisProduk .= $jobName;
            }
        }else{
            $jenisProduk = 'Belum dipilih';
        }

        return response()->json([
            'status' => 200,
            'data' => $order,
            'jenisProduk' => $jenisProduk
        ]);
    }

    public function cobaPush()
    {
        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("sales"),
            array("web" => array("notification" => array(
                "title" => "Ada desain baru menunggu !",
                "body" => "📣 Cek brief sekarang, jangan sampai diambil orang lain !",
            )),
        ));
    }

    public function notifTest(){
        $users = User::all();
        $notification = new AntrianNew();

        Notification::send($users, $notification);
    }

    public function antrianDesain(){
        return view('page.antrian-desain.index');
    }

    public function listMenunggu()
    {
        $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 0)->get();

        return Datatables()->of($listOrder)
        ->addIndexColumn()
        ->addColumn('ticket_order', function($data){
            if($data->is_priority == 1){
                return $data->ticket_order .'<span class="text-warning"><i class="fas fa-star"></i></span>';
            }else{
                return $data->ticket_order;
            }
        })
        ->addColumn('title', function($data){
            return $data->title;
        })
        ->addColumn('sales', function($data){
            return $data->sales->sales_name;
        })
        ->addColumn('ref_desain', function($data){
            if($data->desain == '-'){
                return '-';
            }else{
                return '<a href="'. asset('storage/ref-desain/' . $data->desain) .'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
            }
        })
        ->addColumn('job_name', function($data){
            $job = explode(',', $data->job_id);
            if($data->job_id == 0 || $data->job_id == null){
                return 'Belum dipilih';
            }else{
                $jenisProduk = '';
                foreach($job as $j){
                    $jobName = Job::where('id', $j)->first();
                    if(end($job) == $j){
                        $jobName = $jobName->job_name;
                    }else{
                        $jobName = $jobName->job_name . ', ';
                    }
                    $jenisProduk .= $jobName;
                }
                return $jenisProduk;
            }
        })
        ->addColumn('status', function($data){
            return $data->status == 0 ? '<span class="badge badge-primary">Menunggu</span>' : '<span class="badge badge-success">Dikerjakan</span>';
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';

            if(auth()->user()->role == 'sales'){
                $button .= '<a href="'. route('order.edit', $data->id) .'" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Ubah</a>';
            }

            $button .= '<a href="javascript:void(0)" onclick="showDetailDesain('. $data->id .')" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Detail</a>';

            if(auth()->user()->role == 'supervisor'){
            $button .= '<a href="javascript:void(0)" onclick="showDesainer('. $data->ticket_order .')" class="btn btn-sm btn-dark"><i class="fas fa-user"></i> Pilih Desainer</a>';
            }

            $button .= '<a href="javascript:void(0)" onclick="deleteOrder('. $data->id .')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>';
            $button .= '</div>';
            return $button;
        })
        ->rawColumns(['action', 'ref_desain', 'status', 'job_name', 'ticket_order'])
        ->make(true);
    }

    public function listDalamProses()
    {
        if(auth()->user()->role_id == 11){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 1)->where('sales_id', auth()->user()->sales->id)->get();
        }elseif(auth()->user()->role_id == 5){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 1)->get();
        }elseif(auth()->user()->role_id == 16 || auth()->user()->role_id == 17){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 1)->where('employee_id', auth()->user()->employee->id)->get();
        }

        return Datatables()->of($listOrder)
        ->addIndexColumn()
        ->addColumn('ticket_order', function($data){
            if($data->is_priority == 1){
                return $data->ticket_order .'<span class="text-warning"><i class="fas fa-star"></i></span>';
            }else{
                return $data->ticket_order;
            }
        })
        ->addColumn('title', function($data){
            return $data->title;
        })
        ->addColumn('sales', function($data){
            return $data->sales->sales_name;
        })
        ->addColumn('ref_desain', function($data){
            if($data->desain == '-'){
                return '-';
            }else{
                return '<a href="'. asset('storage/ref-desain/' . $data->desain) .'" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>';
            }
        })
        ->addColumn('job_name', function($data){
            //explode string to array
            $job = explode(',', $data->job_id);
            if($data->job_id == 0 || $data->job_id == null){
                return 'Belum dipilih';
            }else{
                $jenisProduk = '';
                foreach($job as $j){
                    $jobName = Job::where('id', $j)->first();
                    if(end($job) == $j){
                        $jobName = $jobName->job_name;
                    }else{
                        $jobName = $jobName->job_name . ', ';
                    }
                    $jenisProduk .= $jobName;
                }
                return $jenisProduk;
            }
        })
        ->addColumn('desainer', function($data){
            if($data->employee_id == null){
                return '<span class="badge badge-danger">Belum dipilih</span>';
            }else{
                return $data->employee->name;
            }
        })
        ->addColumn('status', function($data){
            return $data->status == 0 ? '<span class="badge badge-primary">Menunggu</span>' : '<span class="badge badge-warning">Dikerjakan</span>';
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';
            $button .= '<a href="javascript:void(0)" onclick="showDetailDesain('. $data->id .')" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Detail</a>';
            if(auth()->user()->role == 'sales'){
                $button .= '<a href="'. route('order.edit', $data->id) .'" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Ubah</a>';
            }
            if(auth()->user()->employee->can_design == 1 && $data->employee_id == auth()->user()->employee->id){
                $button .= '<a href="javascript:void(0)" onclick="showUploadCetak('. $data->ticket_order .')" class="btn btn-sm btn-dark"><i class="fas fa-upload"></i> File Cetak </a>';
            }
            if(auth()->user()->role == 'supervisor' || auth()->user()->role == 'sales'){
                $button .= '<a href="javascript:void(0)" onclick="deleteOrder('. $data->id .')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>';
            }
            $button .= '</div>';
            return $button;
        })
        ->rawColumns(['action', 'ref_desain', 'status', 'desainer', 'job_name', 'ticket_order'])
        ->make(true);
    }

    public function listSelesai()
    {
        if(auth()->user()->role_id == 11){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 2)->where('sales_id', auth()->user()->sales->id)->get();
        }elseif(auth()->user()->role_id == 5){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 2)->get();
        }elseif(auth()->user()->role_id == 16 || auth()->user()->role_id == 17){
            $listOrder = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->orderByDesc('created_at')->where('status', 2)->where('employee_id', auth()->user()->employee->id)->get();
        }

        return Datatables()->of($listOrder)
        ->addIndexColumn()
        ->addColumn('ticket_order', function($data){
            if($data->is_priority == 1){
                return $data->ticket_order .'<span class="text-warning"><i class="fas fa-star"></i></span>';
            }else{
                return $data->ticket_order;
            }
        })
        ->addColumn('sales', function($data){
            return $data->sales->sales_name;
        })
        ->addColumn('title', function($data){
            return $data->title;
        })
        ->addColumn('selesai', function($data){
            return $data->time_end;
        })
        ->addColumn('periode', function($data){
            //hitung selisih waktu selesai dan waktu mulai
            $timeStart = strtotime($data->time_taken);
            $timeEnd = strtotime($data->time_end);
            $diff = $timeEnd - $timeStart;

            //hitung selisih hari
            $days = floor($diff / (60 * 60 * 24));

            //hitung selisih jam
            $hours = floor(($diff - $days * (60 * 60 * 24)) / (60 * 60));

            //hitung selisih menit
            $minutes = floor(($diff - $days * (60 * 60 * 24) - $hours * (60 * 60)) / 60);

            //hitung selisih detik
            $seconds = floor(($diff - $days * (60 * 60 * 24) - $hours * (60 * 60) - $minutes * 60));

            return $days . ' Hari ' . $hours . ' Jam ' . $minutes . ' Menit ' . $seconds . ' Detik';
        })
        ->addColumn('produk', function($data){
            //explode string to array
            $job = explode(',', $data->job_id);
            if($data->job_id == 0 || $data->job_id == null){
                return 'Belum dipilih';
            }else{
                $jenisProduk = '';
                foreach($job as $j){
                    $jobName = Job::where('id', $j)->first();
                    if(end($job) == $j){
                        $jobName = $jobName->job_name;
                    }else{
                        $jobName = $jobName->job_name . ', ';
                    }
                    $jenisProduk .= $jobName;
                }
                return $jenisProduk;
            }
        })
        ->addColumn('desainer', function($data){
            return $data->employee->name;
        })
        ->addColumn('status', function($data){
            return '<span class="badge badge-success">Selesai</span>';
        })
        ->addColumn('file_cetak', function($data){
            if(!isset($data->printfile->nama_file)){
                return '-';
            }else{
                return '<a href="'. asset('storage/file-cetak/' . $data->printfile->nama_file) .'" target="_blank" class="btn btn-sm btn-primary">'. $data->printfile->nama_file .' <i class="fas fa-download"></i></a>';
            }
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';
            if(auth()->user()->role == 'sales' && $data->toWorkshop == 0){
                $button .= '<a href="'. route('order.toAntrian', $data->id) .'" class="btn btn-sm btn-danger"><i class="fas fa-fire"></i> Antrikan</a>';
            }elseif(auth()->user()->role == 'sales' && $data->toWorkshop == 1){
                $button .= '<a href="'. route('order.notaOrderView', $data->ticket_order) .'" class="btn btn-sm btn-warning"><i class="fas fa-download"></i> Nota Order</a>';
            }
            else{
                $button .= '<a href="javascript:void(0)" onclick="showDetailDesain('. $data->id .')" class="btn btn-sm btn-warning"><i class="fas fa-eye"></i> Detail</a>';
            }
            $button .= '</div>';
            return $button;
        })
        ->rawColumns(['ref_desain', 'status', 'desainer', 'periode', 'file_cetak', 'action', 'ticket_order'])
        ->make(true);
    }

    public function hapus($id)
    {
        $order = Order::find($id);
        $order->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Order berhasil dihapus !'
        ]);
    }

    public function bagiDesain(Request $request){

        $order = Order::where('ticket_order', $request->ticket)->first();
        $order->status = 1;
        $order->employee_id = $request->idDesainer;
        $order->time_taken = now();
        $order->save();

        $employee = Employee::find($request->idDesainer);
        $employee->design_load += 1;
        $employee->save();

        return response()->json([
            'status' => 200,
            'message' => 'Desain berhasil diberikan ke desainer!'
        ])->with('success', 'Desain berhasil diberikan ke desainer!');

        $user = User::find($employee->user_id);
        $sales = Sales::find($order->sales_id);
        $user->notify(new AntrianDesain($user, $sales, $order));

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToUsers(
            array("user-". $order->employee->user->id),
            array("web" => array("notification" => array(
                "title" => "Pengumuman!, Ada desain baru menunggu!",
                "body" => "📣 Cek brief dulu, pastikan ga ada revisi !✨",
            )),
        ));
    }

    public function create()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $jobs = Job::all();

        $lastId = Order::latest()->first();
        if($lastId == null){
            $lastId = 1;
        }else{
            $lastId = $lastId->id + 1;
        }
        $ticketOrder = date('Ymd') . $lastId;

        return view('page.order.add', compact('sales', 'jobs', 'ticketOrder'));
    }

    public function getAccDesain($id)
    {
        $order = GambarAcc::where('ticket_order', $id)->get();

        return response()->json($order);
    }

    public function hapusAcc($id)
    {
        $gambar = GambarAcc::find($id);
        $gambar->delete();

        //hapus file
        Storage::disk('public')->delete('acc-desain/' . $gambar->filename);

        return response()->json([
            'status' => 200,
            'message' => 'Gambar berhasil dihapus !'
        ]);
    }

    public function edit($id)
    {
        $order = Order::find($id);
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $job = Job::where('id', $order->job_id)->first();
        $jobs = Job::all();

        if($sales == null){
            return redirect()->back()->with('error', 'Oops! Akses "Ubah" hanya dapat diakses oleh Sales.');
        }

        return view('page.order.edit', compact('order', 'sales', 'job', 'jobs'));
    }

    public function update(Request $request, $id){
        // Validasi form add.blade.php
        $rules = [
            'title' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //ubah nama file
        if($request->file('refdesain')){
            //Hapus file lama / sebelumnya diupload
            $orderLama = Order::find($id);
            $oldFile = $orderLama->desain;
            if($oldFile != '-'){
                Storage::disk('public')->delete('ref-desain/' . $oldFile);
            }

            $file = $request->file('refdesain');
            $fileName = time() . '.' . $orderLama->title . $file->getClientOriginalExtension();
            $path = 'ref-desain/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }

        $job = $request->job;
        $job = implode(',', $job);

        // Jika validasi berhasil, simpan data ke database
        $order = Order::find($id);
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $job;
        $order->description = $request->description;
        $order->type_work = $request->jenisPekerjaan;
        if($request->file('refdesain')){
            $order->desain = $fileName;
        }
        $order->is_priority = $request->priority ? '1' : '0';
        $order->save();

        return redirect()->route('design.index')->with('success', 'Design berhasil diupdate');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'description' => 'required',
            'jenisPekerjaan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $lastId = Order::latest()->first();
        if($lastId == null){
            $lastId = 1;
        }else{
            $lastId = $lastId->id + 1;
        }
        $ticketOrder = date('Ymd') . $lastId;

        $checkTicket = Order::where('ticket_order', $ticketOrder)->first();
        if($checkTicket){
            return redirect()->back()->with('error', 'Oops! Tiket order sudah terdaftar, silahkan ulangi proses tambah order / refresh halaman');
        }else{

        //ubah nama file
        if($request->file('refdesain')){
            $file = $request->file('refdesain');
            $fileName = time() . '_' . $request->title . '.' . $file->getClientOriginalExtension();
            $path = 'ref-desain/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }else{
            $fileName = '-';
        }

        //hasil input job from array to string
        $job = $request->job;
        $job = implode(',', $job);

        // Jika validasi berhasil, simpan data ke database
        $order = new Order;
        $order->ticket_order = $ticketOrder;
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $job;
        $order->description = $request->description != null ? $request->description : '-';
        $order->type_work = $request->jenisPekerjaan;
        $order->desain = $fileName;
        $order->status = '0';
        $order->is_priority = $request->priority ? '1' : '0';
        $order->save();

        return redirect()->route('design.index')->with('success', 'Project berhasil ditambahkan !');
        }
    }

    //-------------------------------------------------------------------------------------------------------------
    // Upload Desain Cetak
    //-------------------------------------------------------------------------------------------------------------

    //Dropzone untuk upload file cetak
    public function uploadPrintFile(Request $request)
    {
        try {
            $id = $request->idOrder;
            $order = Order::where('ticket_order', $id)->first();
            $order->status = 2;
            $order->time_end = now();
            $order->save();

            $file = $request->file('fileCetak');
            $fileName = time() . '_' . $order->title . '.' . $file->getClientOriginalExtension();
            $path = 'file-cetak/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            $file = PrintFile::where('ticket_order', $id)->first();
            if(!$file){
                $file = new PrintFile;
                $file->ticket_order = $id;
                $file->nama_file = $fileName;
                $file->desainer_id = $order->employee_id;
                $file->save();
            }else{
                $file->nama_file = $fileName;
                $file->save();
            }

            return response()->json(['success' => $fileName, 'message' => 'File berhasil diupload !']);
        } catch (\Throwable $th) {
            return redirect()->route('design.index')->with('error', 'Terjadi Kesalahan'. $th->getMessage());
        }
    }

    public function revisiUpload(Request $request)
    {
        try {
            $id = $request->idOrder;
            $order = Order::where('ticket_order', $id)->first();

            $file = $request->file('fileRevisi');
            $fileName = time() . '_' . $order->title . '.' . $file->getClientOriginalExtension();
            $path = 'file-cetak/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            $printFile = PrintFile::where('ticket_order', $id)->first();
            if($printFile == null){
                $printFile = new PrintFile;
                $printFile->ticket_order = $id;
            }
            $printFile->nama_file = $fileName;
            $printFile->save();

            $antrian = DataAntrian::where('ticket_order', $id)->first();
            $antrian->is_revision = 2;
            $antrian->save();

            return response()->json(['success' => $fileName, 'message' => 'File berhasil diupload !']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $th->getMessage()]);
        }
    }

    //Untuk submit dengan link file cetak
    public function submitLinkUpload(Request $request)
    {
        if(!$request->input('linkFileUpload')){
            return redirect()->back()->with('error-filecetak', 'Link file cetak belum diisi, silahkan ulangi proses upload file cetak');
        }

        $order = Order::find($request->id);

        if($order->file_cetak != null){
            Storage::disk('public')->delete('file-cetak/' . $order->file_cetak);
        }

        $order->file_cetak = $request->input('linkFileUpload');
        $order->status = 2;
        $order->time_end = now();
        $order->save();

        //Jika $order sudah save, baru kurangi design load
        $employee = Employee::find($order->employee_id);

        if($employee->design_load > 0){
            //designer load -1
            $employee->design_load -= 1;
        }else{
            //designer load 0
            $employee->design_load = 0;
        }
        $employee->save();

        return redirect()->route('design.index')->with('success', 'Link berhasil disimpan !');
    }

    //Untuk submit file cetak bukan link
    public function submitFileCetak($id)
    {
        $order = Order::find($id);

        if(!$order->file_cetak){
            return redirect()->back()->with('error-filecetak', 'File cetak belum diupload, silahkan ulangi proses upload file cetak');
        }

        try{
            $order->status = 2;
            $order->time_end = now();
            $order->save();
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Gagal mengubah status desain');
        }

        try{
            //designer load -1
            $employee = Employee::find($order->employee_id);
            if($employee->design_load > 0){
                $employee->design_load -= 1;
            }else{
                $employee->design_load = 0;
            }
            $employee->save();
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Gagal mengurangi antrian desainer');
        }

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToUsers(
            array("user-". $order->sales->user->id),
            array("web" => array("notification" => array(
              "title" => "Kiw Kiw! Desainmu sudah selesai !",
              "body" => "📣 Cek sekarang, untuk mengantrikan !",
            )),
        ));

        return redirect()->route('design.index')->with('success', 'File berhasil diupload');
    }

    //-------------------------------------------------------------------------------------------------------------
    // Reupload Desain
    //-------------------------------------------------------------------------------------------------------------

    //Dropzone untuk reupload file cetak
    public function reuploadFileDesain(Request $request)
    {
        //Hapus file lama / sebelumnya diupload
        $orderLama = Order::find($request->id);
        $oldFile = $orderLama->file_cetak;
        if($oldFile != ""){
            Storage::disk('public')->delete('file-cetak/' . $oldFile);
        }
        //Menyimpan file cetak dari form dropzone
        $file = $request->file('fileReupload');
        $fileName = time() . '_' . $orderLama->title . $file->getClientOriginalExtension();
        $path = 'file-cetak/' . $fileName;
        Storage::disk('public')->put($path, file_get_contents($file));

        $orderLama->file_cetak = $fileName;
        $orderLama->save();

        return response()->json(['success' => $fileName]);
    }

    //Untuk submit dengan link file cetak
    public function submitLinkReupload(Request $request)
    {
        if(!$request->input('linkReupload')){
            return redirect()->back()->with('error-filecetak', 'Link file cetak belum diisi, silahkan ulangi proses upload file cetak');
        }

            $order = Order::find($request->id);

            if($order->file_cetak != null){
                Storage::disk('public')->delete('file-cetak/' . $order->file_cetak);
            }

            $order->file_cetak = $request->input('linkReupload');
            $order->status = 2;
            $order->time_end = now();
            $order->save();

            //designer load -1
            $employee = Employee::find($order->employee_id);
            if($employee->design_load > 0){
                $employee->design_load -= 1;
            }else{
                $employee->design_load = 0;
            }
            $employee->save();

        return redirect()->route('design.index')->with('success', 'File berhasil diupload !');
    }
    //Untuk submit file cetak bukan link
    public function submitReuploadFile($id)
    {
        $order = Order::find($id);

        if(!$order->file_cetak){
            return redirect()->back()->with('error-filecetak', 'File cetak belum diupload, silahkan ulangi proses upload file cetak');
        }

        $order->status = 2;
        $order->time_end = now();
        $order->save();

        return redirect()->route('design.index')->with('success', 'File Reupload berhasil diunggah !');
    }

    //-------------------------------------------------------------------------------------------------------------
    // Revisi Desain by Sales
    //-------------------------------------------------------------------------------------------------------------

    //Menampilkan halaman revisi desain by sales
    public function revisiDesain($id)
    {
        $order = Order::find($id);
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $job = Job::where('id', $order->job_id)->first();
        $jobs = Job::all();

        return view('page.order.revisi-desain', compact('order', 'sales', 'job', 'jobs'));
    }

    public function listRevisi()
    {
        if(auth()->user()->role_id == 11){
            $listrevisi = DataAntrian::where('is_revision', 1)->where('sales_id', auth()->user()->sales->id)->get();
        }elseif(auth()->user()->role_id == 5 || auth()->user()->role_id == 16 || auth()->user()->role_id == 17){
            $listrevisi = DataAntrian::where('is_revision', 1)->get();
        }

        return Datatables()->of($listrevisi)
        ->addIndexColumn()
        ->addColumn('ticket_order', function($data){
            if($data->is_priority == 1){
                return $data->ticket_order .'<span class="text-warning"><i class="fas fa-star"></i></span>';
            }else{
                return $data->ticket_order;
            }
        })
        ->addColumn('sales', function($data){
            return $data->sales->sales_name;
        })
        ->addColumn('title', function($data){
            return $data->order->title;
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';

            if($data->order->employee_id == auth()->user()->employee->id){
                $button .= '<a href="javascript:void(0)" onclick="uploadRevisi('. $data->ticket_order .')" class="btn btn-sm btn-dark"><i class="fas fa-upload"></i> Unggah File Revisi</a>';
            }else{
                $button .= '<a href="javascript:void(0)" class="btn btn-sm btn-dark disabled"><i class="fas fa-upload"></i> Unggah File Revisi</a>';
            }
            $button .= '</div>';

            return $button;
        })
        ->rawColumns(['acc_desain', 'action', 'ticket_order'])
        ->make(true);
    }

    //Update revisi desain by sales
    public function updateRevisiDesain(Request $request)
    {
        // Validasi form add.blade.php
        $rules = [
            'title' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'description' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke halaman add.blade.php dengan membawa pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //ubah nama file
        if($request->file('accdesain')){
            //Hapus file lama / sebelumnya diupload
            $orderLama = Order::find($id);
            $oldFile = $orderLama->acc_desain;
            if($oldFile != '-'){
                Storage::disk('public')->delete('acc-desain/' . $oldFile);
            }

            $file = $request->file('accdesain');
            $fileName = time() . '_' . $orderLama->title . $file->getClientOriginalExtension();
            $path = 'acc-desain/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }

        // Jika validasi berhasil, simpan data ke database
        $order = Order::find($id);
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
        $order->description = $request->description;
        if($request->file('accdesain')){
            $order->acc_desain = $fileName;
        }
        $order->is_priority = $request->priority ? '1' : '0';
        $order->ada_revisi = '1';
        $order->status = '1';
        $order->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToUsers(
            array("user-". $order->employee->user->id),
            array("web" => array("notification" => array(
              "title" => "Yah ! Ada revisi nih !",
              "body" => "📣 Cek sekarang, untuk mengupload file desainnya !",
            )),
        ));

        return redirect()->route('design.index')->with('success', 'Design berhasil diupdate');
    }

    //-------------------------------------------------------------------------------------------------------------
    // Revisi Desain
    //-------------------------------------------------------------------------------------------------------------

    //Dropzone untuk upload revisi file cetak
    public function uploadRevisi(Request $request)
    {
        //Hapus file lama / sebelumnya diupload
        $orderLama = Order::find($request->id);
        $oldFile = $orderLama->file_cetak;
        if($oldFile != ""){
            Storage::disk('public')->delete('file-cetak/' . $oldFile);
        }
        //Menyimpan file cetak dari form dropzone
        $file = $request->file('fileRevisi');
        $fileName = time() . '.' . $orderLama->title . $file->getClientOriginalExtension();
        $path = 'file-cetak/' . $fileName;
        Storage::disk('public')->put($path, file_get_contents($file));

        $orderLama->file_cetak = $fileName;
        $orderLama->save();

        return response()->json(['success' => $fileName]);
    }

    //Untuk submit file revisi cetak bukan link
    public function submitRevisi($id)
    {
        $order = Order::find($id);

        if(!$order->file_cetak){
            return redirect()->back()->with('error-filecetak', 'File cetak belum diupload, silahkan ulangi proses upload file cetak');
        }

        $order->ada_revisi = 2;
        $order->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToUsers(
            array("user-". $order->sales->user->id),
            array("web" => array("notification" => array(
              "title" => "Yuhuu.. Revisi Desainmu sudah diunggah !",
              "body" => "📣 Lihat updatenya sekarang, pastikan tidak ada revisi lagi ya!",
            )),
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("operator"),
            array("web" => array("notification" => array(
              "title" => "Update Revisi Desain !",
              "body" => "📣 Cek revisi desain dengan nomer tiket ". $order->ticket_order . ", sales ". $order->sales->sales_name,
            )),
        ));

        return redirect()->route('design.index')->with('success', 'File revisi desain berhasil diupload');
    }

    //Untuk submit dengan link file cetak
    public function submitLinkRevisi(Request $request)
    {
        $order = Order::find($request->id);

        if($order->file_cetak != null){
            Storage::disk('public')->delete('file-cetak/' . $order->file_cetak);
        }

        $order->file_cetak = $request->input('linkRevisi');
        $order->ada_revisi = 2;
        $order->save();

        // Menampilkan push notifikasi saat selesai
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $publishResponse = $beamsClient->publishToUsers(
            array("user-". $order->sales->user->id),
            array("web" => array("notification" => array(
              "title" => "Yuhuu.. Revisi Desainmu sudah diunggah !",
              "body" => "📣 Lihat updatenya sekarang, pastikan tidak ada revisi lagi ya!",
            )),
        ));

        $publishResponse = $beamsClient->publishToInterests(
            array("operator"),
            array("web" => array("notification" => array(
              "title" => "Update Revisi Desain !",
              "body" => "📣 Cek revisi desain dengan nomer tiket ". $order->ticket_order . ", sales ". $order->sales->sales_name,
            )),
        ));

        return redirect()->route('design.index')->with('success', 'File revisi desain berhasil diupload');
    }

    //-------------------------------------------------------------------------------------------------------------
    // Input Order ke Workshop by Sales
    //-------------------------------------------------------------------------------------------------------------

    public function toAntrian(string $id){
        $order = Order::find($id);
        $tiket = $order->ticket_order;

        $barang = Barang::where('ticket_order', $tiket)->get();

        $totalBarang = 0;
        foreach($barang as $b){
            $totalBarang += $b->price * $b->qty;
        }

        $ekspedisi = Ekspedisi::all();

        return view ('page.antrian-workshop.create', compact('order', 'totalBarang', 'ekspedisi'));
    }

    //-------------------------------------------------------------------------------------------------------------
    // Tambah Produk by Modal by Sales
    //-------------------------------------------------------------------------------------------------------------

    public function tambahProdukByModal(Request $request){

        $job = new Job;
        $job->job_name = ucwords($request->namaProduk);
        $job->job_type = $request->jenisProduk;
        $job->save();

        return response()->json([
            'status' => 200,
            'message' => 'Produk berhasil ditambahkan'
        ]);
    }

    //-------------------------------------------------------------------------------------------------------------
    // Mendapatkan Produk berdasarkan Kategori by Sales
    //-------------------------------------------------------------------------------------------------------------
    public function getJobsByCategory(Request $request, $kategori){
        $jobs = Job::where('job_name', 'LIKE' , "%". $kategori ."%")->get();
        return response()->json($jobs);
    }

    public function getAllJobs(Request $request){
        $jobs = Job::where('job_name', 'LIKE', "%".request('q')."%")->get();
        return response()->json($jobs);
    }

    public function simpanAcc(Request $request)
    {
        $order = Order::where('ticket_order', $request->ticket_order)->first();

        $file = $request->file('gambarAcc');
        
        try{
            $fileName = time() . '_' . $order->title . $file->getClientOriginalExtension();
            $path = 'acc-desain/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            $design = new GambarAcc;
            $design->ticket_order = $order->ticket_order;
            $design->sales_id = $order->sales_id;
            $design->filename = $fileName;
            $design->filepath = $path;
            $design->filetype = $file->getClientOriginalExtension();
            $design->save();
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Gagal menyimpan file desain');
        }

        response()->json(['success' => $fileName]);
    }

    public function listDesainer()
    {
        $desainer = Employee::with('user')->where('can_design', '1')->get();

        return Datatables()->of($desainer)
        ->addIndexColumn()
        ->addColumn('nama_desainer', function($data){
            return $data->name;
        })
        ->addColumn('jumlah_desain', function($data){
            return $data->design_load;
        })
        ->addColumn('action', function($data){
            $button = '<div class="btn-group">';
            $button .= '<button href="javascript:void(0)" onclick="pilihDesainer('. $data->id .')" class="btn btn-sm btn-dark btnDesainer"><i class="fas fa-user"></i> Pilih</button>';
            $button .= '</div>';
            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function simpanDesainer(Request $request)
    {
        $order = Order::where('ticket_order', $request->input('ticketOrder'))->first();
        $order->employee_id = $request->input('desainerID');
        $order->status = 1;
        $order->save();

        $employee = Employee::find($request->input('desainerID'));
        $employee->design_load += 1;
        $employee->save();

        // return json success for ajax request
        return response()->json([
            'status' => 200,
            'message' => 'Desain berhasil diberikan ke desainer!'
        ]);
    }
}

