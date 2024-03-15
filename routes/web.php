<?php

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Events\SendGlobalNotification;
use App\Notifications\AntrianWorkshop;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\OrderController;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\BarangController;

use App\Http\Controllers\DesignController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\EstimatorController;
use App\Http\Controllers\DocumentationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('page.dashboard');
});

Route::get('users/export/', [UserController::class, 'export']);

// Reset Password ------------------------------

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);

})->middleware('guest')->name('password.email');

Route::get('/notification/mark-as-read/{id}', function ($id) {

    $user = auth()->user()->id;
    $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
    $notification->markAsRead();

    if($notification->data['link'] == '/design'){
        return redirect()->route('design.index');
    }else{
        return redirect()->route('antrian.index');
    }
})->middleware('auth')->name('notification.markAsRead');

Route::get('/notification/mark-all-as-read', function () {

    $user = auth()->user()->id;
    $notifications = auth()->user()->unreadNotifications;
    $notifications->markAsRead();

    return redirect()->back();
})->middleware('auth')->name('notification.markAllAsRead');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token, 'email' => request()->query('email')]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('auth.login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// End Reset Password ---------------------

Route::group(['middleware' => 'auth'], function () {
    //Menuju Antrian Controller (Admin)
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/design', [DesignController::class, 'index'])->name('design.index');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
});

Route::group(['middleware' => 'checkrole:admin'], function () {
    //Menuju Design Controller (Admin)
    Route::get('/antrian/{id}/edit', [AntrianController::class, 'edit'])->name('antrian.edit');
    Route::put('/antrian/{id}', [AntrianController::class, 'update'])->name('antrian.update');
    Route::delete('/antrian/{id}', [AntrianController::class, 'destroy'])->name('antrian.destroy');
});

//membuat route group untuk AuthController
Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('auth.index');
    Route::get('/register', 'create')->name('auth.register');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'store')->name('auth.store');
    Route::get('/logout', 'logout')->name('auth.logout');
    Route::get('/beams-generateToken', 'generateToken')->name('beams.auth');
});

Route::controller(EstimatorController::class)->group(function(){
    Route::get('/estimator/laporan-penugasan', 'laporanPenugasan')->middleware(['auth'])->name('estimator.laporanPenugasan');
    Route::get('/estimator/laporan-penugasan-json', 'laporanPenugasanJson')->middleware(['auth'])->name('estimator.laporanPenugasanJson');
    Route::get('/estimator/laporan-workshop-excel', 'laporanWorkshopExcel')->middleware(['auth'])->name('estimator.laporanWorkshopExcel');
});

Route::controller(IklanController::class)->group(function(){
    Route::get('/iklan/json', 'iklanJson')->name('iklan.indexJson');
    Route::get('/iklan/selesai-json', 'selesaiJson')->name('iklan.selesaiJson');
    Route::get('/iklan', 'index')->name('iklan.index');
    Route::get('/iklan-total-omset', 'totalOmset')->name('iklan.totalOmset');
    Route::get('/iklan/getPeriodeIklan', 'getPeriodeIklan')->name('iklan.getPeriodeIklan');
    Route::get('/iklan/penjualan-iklan', 'penjualanIklan')->name('iklan.penjualanIklan');
    Route::get('/iklan/penjualan-iklan-json', 'penjualanJson')->name('iklan.penjualanJson');
    Route::get('/iklan/create', 'create')->name('iklan.create');
    Route::get('/iklan/{id}', 'show')->name('iklan.show');
    Route::post('/iklan', 'store')->name('iklan.store');
    Route::get('/iklan/{id}/edit', 'edit')->name('iklan.edit');
    Route::put('/iklan/{id}', 'update')->name('iklan.update');
    Route::delete('/iklan/{id}', 'destroy')->name('iklan.destroy');
    
});

Route::controller(ReportController::class)->group(function(){
    Route::get('/report-workshop', 'pilihTanggal')->name('laporan.workshop');
    Route::post('/report-workshop-pdf', 'exportLaporanWorkshopPDF')->name('laporan-workshop-pdf');
    Route::get('/cetak-espk/{id}', 'cetakEspk')->name('cetak-espk');
    Route::get('/sales/report', 'reportSales')->name('report.sales');
    Route::post('/sales/report', 'reportSalesByDate')->name('report.salesByDate');
    Route::get('/report-form-order/{id}', 'reportFormOrder')->name('report.formOrder');
    //Admin Keuangan
    Route::get('/antrian/omset-global-sales', 'omsetGlobalSales')->name('omset.globalSales');
    Route::get('/antrian/omset-percabang', 'omsetPerCabang')->name('omset.perCabang');
    Route::get('/antrian/omset-perproduk', 'omsetPerProduk')->name('omset.perProduk');
    Route::get('/antrian/ringkasan-omset-sales', 'ringkasanSalesIndex')->name('ringkasan.salesIndex');
    Route::get('/antrian/{id}/show', 'showJsonByTicket')->name('antrian.indexJson');
    Route::get('/antrian/{id}/order', 'showOrderByTicket')->name('antrian.OrderJson');
    Route::get('/mesin', 'mesin')->name('mesin.index');
    Route::get('/antrian/ringkasan-omset-sales/data', 'ringkasanOmsetSales')->name('ringkasan.omsetSales');
    Route::post('/antrian/ringkasan-omset-sales/filter', 'ringkasanFilter')->name('ringkasan.omsetSalesFilter');
    Route::get('/antrian/filter/{sales}/{date}', 'dataFilter')->name('ringkasan.dataFilter');
    // Route Nota Order
    //--------------------------------------------
    Route::get('/order/{id}/nota-order', 'notaOrder')->name('order.notaOrder');
    Route::get('/order/{id}/nota-order-pdf', 'notaOrderPDF')->name('order.notaOrderPDF');
    Route::get('/order/{id}/nota-order-view', 'notaOrderView')->name('order.notaOrderView');
    Route::get('/cetak-form-espk/{id}', 'cetakFormEspk')->name('cetak-form-espk');
    Route::get('/report/tampilBP/{id}', 'tampilBP')->name('report.tampilBP');
    // Route Laporan Iklan
});

Route::controller(DesignController::class)->group(function(){
    Route::post('/design/simpan-file-produksi', 'simpanFileProduksi')->name('simpanFileProduksi');
    Route::get('/design/download-file-produksi/{id}', 'downloadFileProduksi')->name('downloadFileProduksi');
});

Route::controller(EmployeeController::class)->group(function(){
    Route::get('/employee', 'index')->middleware('auth')->name('employee.index');
    Route::get('/profile/{id}', 'show')->middleware('auth')->name('employee.show');
    Route::put('/profile/{id}', 'update')->middleware(['auth'])->name('employee.update');
    Route::post('/profile/upload-foto', 'uploadFoto')->middleware(['auth'])->name('employee.uploadFoto');
});

Route::controller(OrderController::class)->group(function(){
    Route::get('/list-revisi', 'listRevisi')->name('list.revisi');
    Route::get('/list-desain/menunggu', 'listMenunggu')->name('list.menunggu');
    Route::get('/list-desain/dalam-proses', 'listDalamProses')->name('list.dalamProses');
    Route::get('/list-desain/selesai', 'listSelesai')->name('list.selesai');
    Route::get('/list-desain/desainer', 'listDesainer')->name('list.desainer');
    Route::post('/revisi-desain', 'revisiUpload')->name('revisi.upload');

    Route::get('/order/create', 'create')->name('order.create');
    Route::post('/order', 'store')->name('order.store');
    Route::get('/order/{id}/edit', 'edit')->name('order.edit');
    Route::delete('/order/hapus/{id}', 'hapus')->name('order.delete');
    Route::put('/order/{id}', 'update')->name('order.update');
    Route::delete('/order/{id}', 'destroy')->name('order.destroy');
    Route::get('/design', 'antrianDesain')->name('design.index');
    Route::get('/order/{id}/show', 'show')->name('order.show');

    Route::get('/order/{id}/toAntrian', 'toAntrian')->middleware(['auth', 'checkrole:sales'])->name('order.toAntrian');
    Route::post('/order/tambahProdukByModal', 'tambahProdukByModal')->name('tambahProdukByModal');
    Route::get('/get-jobs-by-category', 'getJobsByCategory')->name('getJobsByCategory');
    Route::get('/get-all-jobs', 'getAllJobs')->name('getAllJobs');
    Route::post('/order/simpan-desainer', 'simpanDesainer')->name('simpanDesainer');
    //--------------------------------------------
    // Route File Desain FIX
    //--------------------------------------------
    Route::post('/order/upload-print-file', 'uploadPrintFile')->name('design.upload');
    Route::get('/design/submit-file-cetak/{id}', 'submitFileCetak')->name('submit.file-cetak');
    Route::post('/submit-link', 'submitLinkUpload')->name('submitLinkUpload');
    Route::post('/order/unggah-gambar-acc', 'simpanAcc')->name('simpanAcc');
    Route::get('/order/get-acc/{id}', 'getAccDesain')->name('getAccDesain');
    Route::delete('/acc-desain/hapus/{id}', 'hapusAcc')->name('hapusAcc');
    //--------------------------------------------
    // Route Revisi Desain
    //--------------------------------------------
    Route::get('/order/{id}/revisi-desain', 'revisiDesain')->name('order.revisiDesain');
    Route::put('/order/{id}/revisi-desain', 'updateRevisiDesain')->name('order.updateRevisiDesain');
    Route::post('/order/upload-revisi-desain', 'uploadRevisi')->name('uploadRevisi');
    Route::get('/order/{id}/submit-revisi-desain', 'submitRevisi')->name('submitRevisi');
    Route::post('/order/submit-revisi', 'submitLinkRevisi')->middleware('auth')->name('submitLinkRevisi');
    //--------------------------------------------
    // Route Reupload File
    //--------------------------------------------
    Route::post('/design/reupload-file', 'reuploadFileDesain')->name('design.reuploadFile');
    Route::get('/design/submit-reupload-file/{id}', 'submitReuploadFile')->name('submit.reupload');
    Route::post('/design/submit-reupload-link', 'submitLinkReupload')->name('submitLinkReupload');
    //--------------------------------------------
});

Route::controller(AntrianController::class)->group(function(){
    Route::get('/antrian/indexAntrian', 'indexData')->middleware('auth')->name('antrian.indexData');
    Route::get('/antrian/selesai', 'indexSelesai')->middleware('auth')->name('antrian.indexSelesai');
    Route::post('/antrian/simpan-antrian', 'simpanAntrian')->middleware('auth')->name('antrian.simpanAntrian');

    Route::post('/antrian/storeToAntrian', 'store')->middleware('auth')->name('antrian.store');
    Route::get('/antrian/show/{id}', 'show')->middleware('auth')->name('antrian.show');
    Route::post('/antrian/updateDeadline', 'updateDeadline')->middleware('auth')->name('antrian.updateDeadline');
    Route::get('/antrian/dokumentasi/{id}', 'showDokumentasi')->middleware('auth')->name('antrian.showDokumentasi');
    Route::post('/antrian/storeDokumentasi', 'storeDokumentasi')->middleware('auth')->name('antrian.storeDokumentasi');
    Route::get('/design/download/{id}', 'downloadPrintFile')->name('design.download');
    Route::get('/design/download-file-pendukung/{id}', 'downloadFilePendukung')->name('design.downloadFilePendukung');
    Route::get('/design/download-create/{id}', 'downloadPrintFileCreate')->name('design.download.create');
    Route::get('/list-machines', 'getMachine')->name('mesin.search');

    Route::get('/estimator/index', 'estimatorIndex')->middleware('auth')->name('estimator.index');

    Route::get('/antrian/showProgress/{id}', 'showProgress')->middleware('auth')->name('antrian.showProgress');
    Route::post('/antrian/storeProgress', 'storeProgressProduksi')->middleware('auth')->name('store.progressProduksi');
    Route::get('/antrian/mark-aman/{id}', 'markAman')->middleware('auth')->name('antrian.markAman');
    Route::get('/antrian/download-produksi-file/{id}', 'downloadProduksiFile')->middleware('auth')->name('antrian.downloadProduksi');
    Route::get('/antrian/reminder', 'reminderProgress')->middleware('auth')->name('antrian.reminder');
    Route::get('/antrian/tandai-selesai/{id}', 'markSelesai')->middleware('auth')->name('antrian.markSelesai');
    Route::post('/antrian/filterByCategory', 'filterProcess')->middleware('auth')->name('antrian.filterByCategory');

    Route::put('/biaya-produksi/selesai/{id}', 'biayaProduksiSelesai')->middleware('auth')->name('biaya.produksi.update');
    Route::get('/antrian/e-spk/{id}', 'printeSpk')->middleware('auth')->name('antrian.form-espk');
});

Route::controller(PaymentController::class)->group(function(){
    Route::get('/payment/{id}', 'show')->name('payment.show');
    Route::post('/payment/pelunasan', 'updatePelunasan')->name('updatePelunasan');
    Route::put('/payment/unggah-pelunasan', 'unggahPelunasan')->name('unggahPelunasan');
});

Route::resource('product', ProductController::class);

Route::controller(CustomerController::class)->group(function(){
    Route::get('/customer', 'index')->name('customer.index');
    Route::get('/customer/json', 'indexJson')->name('customer.indexJson');
    Route::get('/customer/create', 'create')->name('customer.create');
    Route::get('/customer/{id}', 'edit')->name('customer.edit');
    Route::post('/customer', 'store')->name('customer.store');
    Route::put('/customer/{id}', 'update')->name('customer.update');
    Route::delete('/customer/{id}', 'destroy')->name('customer.destroy');
    Route::get('/pelanggan/search', 'cariPelanggan')->name('pelanggan.search');
    Route::get('/pelanggan/{id}', 'pelangganById')->name('pelanggan.show');
    Route::get('/customer/searchByNama', 'searchById')->name('pelanggan.searchById');
    Route::get('/pelanggan-all/{id}', 'getAllCustomers')->name('getAllCustomers');
    Route::post('/customer/store', 'store')->name('pelanggan.store');
    Route::get('/pelanggan/status/{id}', 'statusPelanggan')->name('pelanggan.status');
    Route::get('/get-info-pelanggan', 'getInfoPelanggan')->name('getInfoPelanggan');
});

Route::controller(JobController::class)->group(function(){
    Route::get('/job/search', 'search')->name('job.search');
    Route::get('/job/searchByNama', 'searchByNama')->name('job.searchByNama');
    Route::get('/job/searchByCategory', 'searchByCategory')->name('job.searchByCategory');
});

Route::controller(DocumentationController::class)->group(function(){
    //documentation index
    Route::get('/documentation', 'index')->name('documentation.index');
    Route::get('/documentation/{id}/edit', 'edit')->name('documentation.edit');
    Route::get('/documentation/show/{id}', 'show')->name('documentation.show');
    Route::get('/documentation/json', 'indexJson')->name('documentation.indexJson');
    Route::get('/documentation/selesai-json', 'selesaiJson')->name('documentation.selesaiJson');
    Route::get('/documentation/download/{id}', 'downloadGambar')->name('documentation.download');
    Route::get('/documentation/gallery', 'galleryDokumentasi')->name('documentation.gallery');
    Route::post('/documentation/upload-gambar', 'uploadGambar')->name('documentation.upload');
    Route::get('/documentation/upload-gambar-produksi/{id}', 'uploadGambarProduksi')->name('documentation.uploadProduksi');
});

Route::controller(UserController::class)->group(function(){
    Route::get('/user/superadmin', 'index')->middleware(['auth', 'checkrole:superadmin'])->name('user.index');
    Route::get('/user/create', 'create')->middleware(['auth', 'checkrole:superadmin'])->name('user.create');
    Route::get('/user/{id}/edit', 'edit')->middleware(['auth', 'checkrole:superadmin'])->name('user.edit');
    Route::put('/user/update/{id}', 'update')->middleware(['auth', 'checkrole:superadmin'])->name('user.update');
    Route::delete('/user/{id}', 'destroy')->middleware(['auth', 'checkrole:superadmin'])->name('user.destroy');
    Route::get('/user/edit-desainer', 'editDesainer')->name('edit.desainer');//untuk menampilkan table daftar desainer
});

//Route Resource BarangController
Route::resource('barang', BarangController::class);

//Route Resource BahanController
Route::resource('bahan', BahanController::class);

Route::controller(BahanController::class)->group(function(){
    Route::get('/bahan/total/{id}', 'totalBahan')->name('bahan.total');
});

Route::controller(GeneralController::class)->group(function(){
    Route::get('/getProvinsi', 'getProvinsi')->name('getProvinsi');
    Route::get('/getKota', 'getKota')->name('getKota');
});

Route::controller(BarangController::class)->group(function(){
    Route::get('/barang/getTotalHarga/{id}', 'getTotalHarga')->name('getTotalHarga');
    Route::get('/barang/show/{id}', 'showCreate')->name('barang.showCreate');
    Route::put('/barang/update/{id}', 'updateCreate')->name('barang.updateCreate');
    //edit barang
    Route::get('/barang/edit/{id}', 'editCreate')->name('barang.editCreate');
    
    Route::get('/barang/getTotalBarang/{id}', 'getTotalBarang')->name('getTotalBarang');
    Route::get('/barang/getBarangById/{id}', 'getBarangById')->name('getBarangById');
    Route::post('/barang/simpan-barang-dari-desain', 'simpanBarangDariDesain')->name('simpanBarangDariDesain');
    Route::get('/barang/get-barang-by-ticket/{id}', 'getBarangByTicket')->name('getBarangByTicket');
    Route::get('/barang/upload-cetak/{id}', 'uploadCetak')->name('barang.uploadCetak');
    Route::post('/barang/tugaskan-desainer', 'tugaskanDesainer')->name('barang.tugaskanDesainer');
    Route::put('barang/unggah-cetak/{id}', 'unggahCetak')->name('unggahCetak');
    //ganti desainer
    Route::post('/barang/ubah-desainer', 'ubahDesainer')->name('ubahDesainer');//untuk mengubah desainer
});

Route::get('/error', function () {
    //menampilkan halaman error dan error message
    if (session('error')) {
        $error = session('error');
        return view('error', compact('error'));
    }
})->name('error.page');
