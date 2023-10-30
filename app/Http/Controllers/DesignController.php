<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Design;
use App\Models\Antrian;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
