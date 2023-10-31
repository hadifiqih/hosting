<?php

namespace App\Http\Controllers;

use PDF;
use Dompdf\Dompdf;

use App\Models\Sales;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportResource;



class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $tanggalAwal adalah selalu tanggal 1 dari bulan yang dipilih
        $tanggalAwal = date('Y-m-01 00:00:00');
        // $tanggalAkhir adalah selalu tanggal sekarang dari bulan yang dipilih
        $tanggalAkhir = date('Y-m-d 23:59:59');

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $totalOmset = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return new ReportResource(true, 'Data omset global sales berhasil diambil', $antrians, $totalOmset);
    }

    public function pilihTanggal()
    {
        return view('page.antrian-workshop.pilih-tanggal');
    }

    public function pilihTanggalDesain()
    {
        return view('page.antrian-desain.pilih-tanggal');
    }

    public function exportLaporanDesainPDF(Request $request)
    {

        $tanggal = $request->tanggal;
        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $antrians = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereDate('created_at', $tanggal)
            ->get();

        $totalOmset = 0;
        $totalQty = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
            $totalQty += $antrian->qty_produk;
        }

        $pdf = PDF::loadview('page.antrian-workshop.laporan-desain', compact('antrians', 'totalOmset', 'totalQty', 'tanggal'));
        return $pdf->stream($tanggal . '-laporan-desain.pdf');
        // return $pdf->download($tanggal . '-laporan-workshop.pdf');
    }

    public function exportLaporanWorkshopPDF(Request $request)
    {
        $tempat = $request->tempat_workshop;
        // $tanggalAwal adalah selalu tanggal 1 dari bulan yang dipilih
        $tanggalAwal = date('Y-m-01 00:00:00');
        // $tanggalAkhir adalah selalu tanggal sekarang dari bulan yang dipilih
        $tanggalAkhir = date('Y-m-d 23:59:59');

        //Mengambil data antrian dengan relasi customer, sales, payment, operator, finishing, job, order pada tanggal yang dipilih dan menghitung total omset dan total order
        $antrianStempel = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Stempel');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        $antrianAdvertising = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Advertising');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();


        $antrianNonStempel = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Non Stempel');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        $antrianDigiPrint = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('job_type', 'Digital Printing');
                });
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            })
            ->get();

        //buat beberapa variabel dengan nilai 0 untuk menampung total omset dan total order
        $totalOmsetStempel = 0;
        $totalQtyStempel = 0;

        $totalOmsetAdvertising = 0;
        $totalQtyAdvertising = 0;

        $totalOmsetNonStempel = 0;
        $totalQtyNonStempel = 0;

        $totalOmsetDigiPrint = 0;
        $totalQtyDigiPrint = 0;

        //looping untuk menghitung total omset dan total order
        foreach ($antrianStempel as $antrian) {
            $totalOmsetStempel += $antrian->omset;
            $totalQtyStempel += $antrian->qty;
        }

        foreach ($antrianAdvertising as $antrian) {
            $totalOmsetAdvertising += $antrian->omset;
            $totalQtyAdvertising += $antrian->qty;
        }

        foreach ($antrianNonStempel as $antrian) {
            $totalOmsetNonStempel += $antrian->omset;
            $totalQtyNonStempel += $antrian->qty;
        }

        foreach ($antrianDigiPrint as $antrian) {
            $totalOmsetDigiPrint += $antrian->omset;
            $totalQtyDigiPrint += $antrian->qty;
        }

        $pdf = PDF::loadview('page.antrian-workshop.laporan-workshop', compact('tanggalAwal', 'tanggalAkhir', 'totalOmsetStempel', 'totalQtyStempel', 'totalOmsetAdvertising', 'totalQtyAdvertising', 'totalOmsetNonStempel', 'totalQtyNonStempel', 'totalOmsetDigiPrint', 'totalQtyDigiPrint', 'antrianStempel', 'antrianNonStempel', 'antrianAdvertising', 'antrianDigiPrint', 'tempat'))->setPaper('folio', 'landscape');
        return $pdf->stream($tempat .  '_Laporan_Workshop.pdf');
    }

    public function cetakEspk($id)
    {
        $antrian = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadview('page.antrian-workshop.cetak-spk-workshop', compact('antrian'))->setPaper('folio', 'landscape');
        return $pdf->stream("Adm_" . $antrian->ticket_order . "_" . $antrian->order->title . '_espk.pdf');

        // return view('page.antrian-workshop.cetak-spk-workshop', compact('antrian'));
    }

    public function reportSales()
    {
        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $totalOmset = 0;

        $date = date('Y-m-d');

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereDate('created_at', $date)
            ->where('sales_id', $salesId)
            ->get();

        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

    public function reportSalesByDate()
    {
        if(request()->has('tanggal')) {
            $date = request('tanggal');
        } else {
            $date = date('Y-m-d');
        }

        $sales = Sales::where('user_id', auth()->user()->id)->first();
        $salesId = $sales->id;

        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->whereDate('created_at', $date)
            ->where('sales_id', $salesId)
            ->get();

        $totalOmset = 0;
        foreach ($antrians as $antrian) {
            $totalOmset += $antrian->omset;
        }

        return view('page.antrian-workshop.ringkasan-sales', compact('antrians', 'totalOmset', 'date'));
    }

    public function reportFormOrder($id)
    {
     $antrian = Antrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->where('ticket_order', $id)
            ->first();
     // return view('page.antrian-workshop.form-order', compact('antrian'));
        $pdf = PDF::loadview('page.antrian-workshop.form-order', compact('antrian'))->setPaper('a4', 'portrait');
        return $pdf->stream($antrian->ticket_order . "_" . $antrian->order->title . '_form-order.pdf');
    }

    public function omsetGlobalSales()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-global-sales', compact('dateRange'));
    }

    public function omsetPerCabang()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-per-cabang', compact('dateRange'));
    }

    public function omsetPerProduk()
    {
        //melakukan perulangan tanggal pada bulan ini, menyimpannya dalam array
        $dateRange = [];
        $dateAwal = date('Y-m-01');
        $dateAkhir = date('Y-m-d');
        $date = $dateAwal;

        while (strtotime($date) <= strtotime($dateAkhir)) {
            $dateRange[] = $date;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return view('page.report.omset-per-produk', compact('dateRange'));
    }
}
