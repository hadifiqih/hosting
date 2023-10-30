<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Sales;
use App\Models\Antrian;
use App\Models\Customer;
use App\Models\CategoryUsaha;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $antrians = Antrian::with('sales', 'customer', 'job')->where('status', '1')->get();

        return view ('antrian.sales.index', compact('antrians'));
    }

    public function category()
    {
        $category = CategoryUsaha::all();

        return view ('antrian.sales.category', compact('category'));
    }

    public function cekTelepon()
    {
        return view ('antrian.sales.cek-telepon');
    }

    public function checkCustomer(Request $request)
    {
        $customer = Customer::where('customer_phone', $request->input('phone_number'))->first();

        if ($customer) {
            return redirect()->route('sales.create', ['customer' => $customer->customer_phone])->with('success', 'Pelanggan ditemukan !');
        } else {
            return redirect()->route('sales.create')->with('error', 'Pelanggan Baru ! Silahkan isi form dibawah ini.');
        }
    }

    public function create()
    {
        $sales = Sales::all();
        $jobs = Job::all();


        if (request()->get('customer')) {
            $customer = Customer::where('customer_phone', request()->get('customer'))->first();

        } else {
            $customer = null;
        }

        return view ('antrian.sales.create', compact('jobs', 'sales' , 'customer'));
    }

    public function store(Request $request){
        // Jika data customer sudah terdaftar di database, hindari duplikasi data
        $customer = Customer::where('customer_phone', $request->input('noCustomer'))->first();

        // Validasi Data Customer
        $validated = $request->validate([
            'namaCustomer' => 'required',
            'noCustomer' => 'required',
            'alamatCustomer' => 'required',
            'infoCustomer' => 'required',
            'sales' => 'required',
            'job' => 'required',
            'note' => 'required',
            'omset' => 'required',
            'accDesign' => 'required | image | mimes:jpeg,png,jpg,gif,svg | max:2048',

        ]);

        // Simpan Foto Design
        $file = $request->file('accDesign');
        $nama_file = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'storage/design-proof';
        $file->move($tujuan_upload,$nama_file);

        // Simpan Data Customer Baru
        if (!$customer) {
            $customer = Customer::create([
                'customer_name' => $validated['namaCustomer'],
                'customer_phone' => $validated['noCustomer'],
                'customer_address' => $validated['alamatCustomer'],
                'customer_info' => $validated['infoCustomer'],
            ]);
        }

        // Generate ticket_order dari tanggal + id antrian terakhir
        $lastAntrian = Antrian::latest()->first();
        $lastId = $lastAntrian ? $lastAntrian->id : 0;
        $ticket_order = date('Ymd').($lastId + 1);

        //simpan data antrian
        $antrian = Antrian::create([
            'customer_id' => $customer->id,
            'sales_id' => $validated['sales'],
            'job_id' => $validated['job'],
            'acc_design' => $nama_file,
            'note' => $validated['note'],
            'omset' => $validated['omset'],
            'ticket_order' => $ticket_order,
            'status' => '1',
        ]);

        return redirect()->route('sales.index')->with('success', 'Data Berhasil Ditambahkan !');

    }

    public function addCategory()
    {
        return view ('antrian.sales.add-category');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        $category = CategoryUsaha::create([
            'category_name' => $validated['category_name'],
        ]);

        return redirect()->route('category.index')->with('success', 'Data Berhasil Ditambahkan !');
    }









}
