<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AntrianExport implements FromView
{

    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $awal = date('Y-m-01');
        $akhir = date('Y-m-d');
        $barangs = Barang::whereHas('antrian', function($query) use ($awal, $akhir){
            $query->whereBetween('created_at', [$awal, $akhir]);
        })
        ->get();

        return view('page.report.laporan-workshop-excel', [
            'barangs' => $barangs
        ]);
    }
}
