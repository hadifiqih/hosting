<?php

namespace App\Http\Controllers;

use App\Models\Barang;

use App\Models\Employee;

use App\Models\DataKerja;
use App\Models\DataAntrian;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Exports\AntrianExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class EstimatorController extends Controller
{
    public function laporanPenugasan()
    {
        return view('page.estimator.laporan-penugasan');
    }

    public function laporanWorkshopExcel()
    {
        return Excel::download(new AntrianExport, 'laporan-workshop.xlsx');
    }

    public function laporanPenugasanJson(Request $request)
    {
        $periode = date('Y') . '-' . $request->get('periode');
        if($request->get('periode')){
            $awal = date('Y-m-01', strtotime($periode));
            $akhir = date('Y-m-t', strtotime($periode));
        }else{
            $awal = date('Y-m-01');
            $akhir = date('Y-m-d');
        }

        $antrians = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })->get();

        return Datatables::of($antrians)
            ->addIndexColumn()
            ->addColumn('ticket_order', function ($antrian) {
                return $antrian->ticket_order;
            })
            ->addColumn('sales', function ($antrian) {
                return $antrian->user->sales->sales_name;
            })
            ->addColumn('kategori', function ($antrian) {
                return $antrian->kategori->nama_kategori;
            })
            ->addColumn('nama_produk', function ($antrian) {
                return $antrian->job->job_name;
            })
            ->addColumn('qty', function ($antrian) {
                return $antrian->qty;
            })
            ->addColumn('tgl_mulai', function ($antrian) {
                return $antrian->dataKerja->tgl_mulai;
            })
            ->addColumn('tgl_selesai', function ($antrian) {
                return $antrian->dataKerja->tgl_selesai;
            })
            ->addColumn('desainer', function ($antrian) {
                if($antrian->design_queue_id == null){
                    return '<span class="text-danger">DESAINER KOSONG</span>';
                }else{
                    return $antrian->designQueue->designer->name;
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
            ->addColumn('omset', function ($antrian) {
                return 'Rp'. number_format($antrian->qty * $antrian->price,0,',','.');
            })
            ->addColumn('status', function ($antrian) {
                if($antrian->status == 1){
                    return '<span class="font-weight-bold text-warning">DIPROSES</span>';
                }else{
                    return '<span class="font-weight-bold text-success">SELESAI</span>';
                }
            })
            ->rawColumns(['ticket_order','operator', 'finishing', 'qc', 'desainer', 'status'])
            ->make();
    }
}
