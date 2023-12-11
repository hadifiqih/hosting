<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Barang;

use App\Models\Design;
use App\Models\Customer;
use App\Models\Employee;
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
        $listDesainer = Employee::where('can_design', 1)->get();

        if(auth()->user()->role == 'sales'){
            $sales = Sales::where('user_id', auth()->user()->id)->first();
            $salesId = $sales->id;
            $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->where('sales_id', $salesId)->get();
            $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->where('sales_id', $salesId)->get();
            $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->where('sales_id', $salesId)->get();
            $listRevisi = [];

        }elseif(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel'){
            $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->get();
            $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->where('employee_id', Auth::user()->employee->id)->get();
            $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->where('employee_id', Auth::user()->employee->id)->get();
            $listRevisi = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->where('ada_revisi', 1)->where('employee_id', Auth::user()->employee->id)->get();
        }else{
            $listDesain = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 0)->get();
            $listDikerjakan = Order::with('employee', 'sales', 'job', 'user')->orderByDesc('is_priority')->where('status', 1)->get();
            $listSelesai = Order::with('employee', 'sales', 'job', 'user')->where('status', 2)->get();
            $listRevisi = [];
        }

        return view('page.antrian-desain.index', compact('listDesain', 'listDikerjakan', 'listSelesai', 'listDesainer', 'listRevisi'));
    }

    public function bagiDesain(Request $request){

        $order = Order::find($request->order_id);
        $order->status = 1;
        $order->employee_id = $request->desainer_id;
        $order->time_taken = now();
        $order->save();

        $employee = Employee::find($request->desainer_id);
        $employee->design_load += 1;
        $employee->save();

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
              "title" => "Kiw Kiw! Ada desain baru menunggu !",
              "body" => "📣 Cek brief dulu, pastikan ga ada revisi !✨",
            )),
        ));

        return redirect()->route('design.index')->with('success', 'Berhasil memilih desainer');
    }

    public function create()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();

        $jobs = Job::all();

        return view('page.order.add', compact('sales', 'jobs'));
    }

    public function edit($id)
    {
        $order = Order::find($id);
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $job = Job::where('id', $order->job_id)->first();
        $jobs = Job::all();

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

        // Jika validasi berhasil, simpan data ke database
        $order = Order::find($id);
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
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
        // Validasi form add.blade.php
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
            $fileName = time() . '_' . $request->title . $file->getClientOriginalExtension();
            $path = 'ref-desain/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }else{
            $fileName = '-';
        }

        // Jika validasi berhasil, simpan data ke database
        $order = new Order;
        $order->ticket_order = $ticketOrder;
        $order->title = $request->title;
        $order->sales_id = $request->sales;
        $order->job_id = $request->job;
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
        $orderLama = Order::find($request->id);
        if($orderLama->file_cetak != null){
            Storage::disk('public')->delete('file-cetak/' . $orderLama->file_cetak);
        }

        try{
            //Menyimpan file cetak dari form dropzone
            $file = $request->file('fileCetak');
            $fileName = time() . '_' . $orderLama->title . $file->getClientOriginalExtension();
            $path = 'file-cetak/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));

            //Menyimpan nama file cetak ke database
            $order = Order::where('id', $request->id)->first();
            $order->file_cetak = $fileName;
            $order->save();

            return response()->json(['success' => $fileName]);
        }catch(\Exception $e){
            return redirect()->back()->with('error-filecetak', 'File cetak belum diupload, silahkan ulangi proses upload file cetak');
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

        return view ('page.antrian-workshop.create', compact('order', 'totalBarang'));
    }

    //-------------------------------------------------------------------------------------------------------------
    // Tambah Produk by Modal by Sales
    //-------------------------------------------------------------------------------------------------------------

    public function makeAntrian(){

        return view ('page.antrian-workshop.create');
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

    public function getJobsByCategory(Request $request){
        $category = $request->kategoriProduk;
        $jobs = Job::where('job_type', $category)->get();

        return response()->json($jobs);
    }

    public function getAllJobs(Request $request){
        $jobs = Job::where('job_name', 'LIKE', "%".request('q')."%")->get();

        return response()->json($jobs);
    }

}

