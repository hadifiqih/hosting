<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Order;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Employee;
use App\Models\DesignQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
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
        $designs = DesignQueue::orderBy('created_at', 'desc')->get();
        
        return Datatables::of($designs)
            ->addIndexColumn()
            ->addColumn('sales', function($design){
                return $design->sales->sales_name;
            })
            ->addColumn('job', function($design){
                return $design->job->job_name;
            })
            ->addColumn('ref_desain', function($design){
                $btn = '<div class="btn-group">';
                if($design->ref_desain == null){
                    $btn .= '<span class="btn btn-sm btn-secondary disabled">Ref. Desain</span>';
                }else{
                    $btn .= '<a class="btn btn-sm btn-primary" href="'.asset('storage/ref-desain/'.$design->ref_desain).'" target="_blank">Ref. Desain</a>';
                }

                if($design->file_cetak == null){
                    $btn .= '<span class="btn btn-sm btn-secondary disabled">File Cetak</span>';
                }else{
                    $btn .= '<a class="btn btn-sm btn-primary p-1" href="'.asset('storage/file-cetak/'.$design->file_cetak).'" target="_blank">File Cetak</a>';
                }

                if($design->acc_desain == null){
                    $btn .= '<span class="btn btn-sm btn-secondary disabled">Acc Desain</span>';
                }else{
                    $btn .= '<a class="btn btn-sm btn-primary p-1" href="'.asset('storage/acc-desain/'.$design->acc_desain).'" target="_blank">Acc Desain</a>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->addColumn('prioritas', function($design){
                return $design->prioritas == 1 ? '<span id="prioritas" class="badge badge-warning">Prioritas</span>' : 'Biasa';
            })
            ->addColumn('status', function($design){
                $status = $design->statusDesain($design->status);
                return $status;
            })
            ->addColumn('action', function($design){
                if($design->status == 0 || $design->status == 1){
                    $btn = '<div class="btn-group">';
                    $btn .= '<a href="'.route('design.editDesain', $design->id).'" class="btn btn-sm btn-warning">Edit</a>';
                    $btn .= '<button onclick="deleteData('. $design->id .')" class="btn btn-sm btn-danger">Hapus</button>';
                    $btn .= '</div>';
                }else if($design->status == 2){
                    $btn = '<a href="'.route('design.editDesain', $design->id).'" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Selesai</a>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'ref_desain', 'status', 'prioritas'])
            ->make(true);
    }

    public function editDesain($id)
    {
        $design = DesignQueue::find($id);
        $jobs = Job::all();
        return view('page.antrian-desain.edit-desain', compact('design', 'jobs'));
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

    public function updateDesain(Request $request, $id)
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

        $design = DesignQueue::find($id);
        $design->hapusRefDesain();
        $design->simpanEditDesain($request);

        return redirect()->route('design.indexDesain')->with('success', 'Desain berhasil diubah');
    }

    public function deleteDesain($id)
    {
        $design = DesignQueue::find($id);
        $design->hapusRefDesain();
        $design->delete();

        return redirect()->route('design.indexDesain')->with('success', 'Desain berhasil dihapus');
    }

    ///---------------------------------------

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
