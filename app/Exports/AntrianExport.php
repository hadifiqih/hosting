<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AntrianExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        $awal = date('Y-m-01');
        $akhir = date('Y-m-d');
        $stempels = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })
        ->where('kategori_id', 1)
        ->get();

        $nonStempels = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })
        ->where('kategori_id', 2)
        ->get();

        $advertisings = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })
        ->where('kategori_id', 3)
        ->get();

        $digitals = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })
        ->where('kategori_id', 4)
        ->get();

        return view('page.report.laporan-workshop-excel', [
            'stempels' => $stempels,
            'nonStempels' => $nonStempels,
            'advertisings' => $advertisings,
            'digitals' => $digitals
        ]);
    }
}
