<?php

namespace App\Http\Controllers;

use App\Models\Barang;

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
        $awal = date('Y-m-01');
        $akhir = date('Y-m-d');
        // return Excel::download(new AntrianExport, 'laporan-workshop.xls');
        $barangs = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })->get();
        return view('page.report.laporan-workshop-excel', compact('barangs'));
    }

    public function laporanPenugasanJson()
    {
        $antrians = DataAntrian::all();

        return Datatables::of($antrians)
            ->addIndexColumn()
            ->addColumn('ticket_order', function ($antrian) {
                return $antrian->ticket_order;
            })
            ->addColumn('nama_produk', function ($antrian) {
                $barang = Barang::where('ticket_order', $antrian->ticket_order)->get();
                $nama_produk = '';
                foreach ($barang as $b) {
                    $nama_produk .= $b->job->job_name . ', ';
                }
                return $nama_produk;
            })
            ->addColumn('jumlah_spk', function ($antrian) {
                $spk = DataKerja::where('ticket_order', $antrian->ticket_order)->first();
                //lakukan explode untuk memisahkan data, kemudian hitung jumlah array
                $operator = count(explode(',', $spk->operator_id));
                $finishing = count(explode(',', $spk->finishing_id));
                $jumlah = $operator + $finishing;
                return $jumlah;
            })
            ->addColumn('qty', function ($antrian) {
                return '-';
            })
            ->addColumn('nama_pekerja', function ($antrian) {
                return '-';
            })
            ->addColumn('harga', function ($antrian) {
                return '-';
            })

            ->rawColumns(['nama_pekerja'])
            ->make(true);
    }
}
