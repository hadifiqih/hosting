<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Employee;
use App\Models\DesignQueue;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexDesain()
    {
        $designs = DesignQueue::all();
        return view('page.antrian-desain.daftar-antrian', compact('designs'));
    }

    public function indexDatatables()
    {
        $designs = DesignQueue::all();
        
        return Datatables::of($designs)
            ->addIndexColumn()
            ->addColumn('sales', function($design){
                return $design->sales->sales_name;
            })
            ->addColumn('job', function($design){
                return $design->job->job_name;
            })
            ->addColumn('ref_desain', function($design){
                return '<a class="btn btn-sm btn-primary" href="'.asset('storage/ref-desain/'.$design->ref_desain).'" target="_blank">Lihat</a>';
            })
            ->addColumn('prioritas', function($design){
                return $design->prioritas == 1 ? 'Prioritas' : 'Biasa';
            })
            ->addColumn('action', function($design){
                return '<a href="'.route('design.editDesain', $design->id).'" class="btn btn-sm btn-warning">Edit</a>';
            })
            ->rawColumns(['action', 'ref_desain'])
            ->make(true);
    }

    public function editDesain($id)
    {
        $design = DesignQueue::find($id);
        return view('page.antrian-desain.edit-desain', compact('design'));
    }

    public function tambahDesain()
    {
        return view('page.antrian-desain.tambah-desain');
    }

    public function storeAddDesain(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'sales_id' => 'required',
            'job_id' => 'required',
            'note' => 'required',
            'prioritas' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $design = new DesignQueue;
        $design->simpanTambahDesain($request);

        return redirect()->route('design.indexDesain')->with('success', 'Desain berhasil ditambahkan');
    }

    public function index(){
        $antrians = Antrian::where('status', '1')->with('design')->get();
        $designs = Design::all();
        return view('antrian.design.index', compact('antrians', 'designs'));
    }

    public function edit($id){
        $employees = Employee::where('can_design', '1')->get();
        $antrian = Antrian::find($id);
        $designs = Design::with('employee')->get();

        return view('antrian.design.create', compact('employees', 'antrian', 'designs'));
    }

    public function update(Request $request, $id){
        // File Upload
        $file = $request->file('designFile');
        $nama_file = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'storage/print-file';
        $file->move($tujuan_upload,$nama_file);

        if($file->move($tujuan_upload,$nama_file)){
            "File berhasil diupload";
        }

        // Create Design
        $design = new Design;
        $design->title = $request->title;
        $design->description = $request->description;
        $design->file_name = $nama_file;
        $design->antrian_id = $id;
        $design->employee_id = $request->designer;
        $design->save();

        // Update Antrian
        $antrian = Antrian::find($id);
        $antrian->design_id = $design->id;
        $antrian->save();

        return redirect('/design')->with('success', 'Design berhasil diupload');
    }

    public function simpanFileProduksi(Request $request)
    {
        $rules = [
            'fileCetak' => 'required|max:204800',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $file = $request->file('fileCetak');
        $nama_file = time()."_".$file->getClientOriginalName();
        $path = 'file-jadi/' . $nama_file;
        Storage::disk('public')->put($path, $file->get());

        $design = new Design;
        $design->ticket_order = $request->ticketOrder;
        $design->title = $request->judulFile;
        $design->filename = $nama_file;
        $design->employee_id = $request->desainer;
        $design->save();

        $antrian = Antrian::where('ticket_order', $request->ticketOrder)->first();
        $antrian->design_id = $design->id;
        $antrian->is_aman = 1;
        $antrian->save();

        return redirect()->route('estimator.index')->with('success', 'File berhasil diupload');

    }

    public function downloadFileProduksi($id)
    {
        $design = Design::find($id);
        $path = 'file-jadi/' . $design->filename;
        return Storage::disk('public')->download($path);
    }

    //
}
