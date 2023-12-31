<?php

namespace App\Http\Controllers;

use PDF;
use Dompdf\Dompdf;

use App\Models\Order;
use App\Models\Sales;
use App\Models\Antrian;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportResource;
use Yajra\DataTables\Facades\DataTables;



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

    public function showJsonByTicket($id)
    {
        $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing', 'machine')
                    ->where('ticket_order', $id)
                    ->first();

        return response()->json($antrians);
    }

    public function showOrderByTicket($id)
    {
        $orders = Order::with('antrian', 'sales', 'job', 'employee')
                    ->where('ticket_order', $id)
                    ->first();

        return response()->json($orders);
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
        $antrianStempel = DataAntrian::with('customer', 'sales', 'payment', 'operator', 'finishing', 'job', 'order')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where(function ($query) use ($tempat) {
                $query->whereHas('sales', function ($subquery) use ($tempat) {
                    $subquery->where('sales_name', 'like', '%' . $tempat . '%');
                })
                ->whereHas('job', function ($subquery) {
                    $subquery->where('kategori_id', '1');
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

    // public function ringkasanOmsetSales()
    // {
    //     $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
    //         ->where('created_at', 'like', '%2023-10-21%')
    //         ->get();

    //     $listSales = Sales::all();

    //     $isFilter = false;

    //     return view('page.report.ringkasan-omset-sales', compact('antrians', 'listSales', 'isFilter'));
    // }

    public function ringkasanSalesIndex()
    {
        $listSales = Sales::all();

        $isFilter = false;

        return view('page.report.ringkasan-omset-sales', compact('listSales', 'isFilter'));
    }

    public function ringkasanOmsetSales()
    {
        if(request()->has('tanggal')) {
            $date = request('tanggal');
        } else {
            $date = date('2023-10-21');
        }

        if(request()->has('sales')) {
            $sales = request('sales');

            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->where('created_at', 'like', '%' . $date . '%')
            ->where('sales_id', $sales)
            ->get();

        } else {
            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->where('created_at', 'like', '%' . $date . '%')
            ->get();
        }

        return Datatables::of($antrians)
        ->addColumn('created_at', function ($antrians) {
            return $antrians->created_at->format('d-m-Y');
        })
        ->addColumn('ticket_order', function ($antrians) {
            return $antrians->ticket_order;
        })
        ->addColumn('sales', function ($antrians) {
            return $antrians->sales_id == 0 ? '-' : $antrians->sales->sales_name;
        })
        ->addColumn('customer', function ($antrians) {
            return $antrians->customer->nama;
        })
        ->addColumn('job', function ($antrians) {
            return $antrians->job->job_name;
        })
        ->addColumn('qty', function ($antrians) {
            return $antrians->qty;
        })
        ->addColumn('harga_produk', function ($antrians) {
            return 'Rp'. number_format($antrians->harga_produk, 0, ',', '.');
        })
        ->addColumn('end_job', function ($antrians) {
            return $antrians->end_job;
        })
        ->addColumn('file_cetak', function ($antrians) {
            return $antrians->order->file_cetak;
        })
        ->addColumn('action', function ($antrians) {
            return '
            <div class="btn-group">
                <button class="btn btn-sm btn-warning" type="button" onclick="lihatPelunasan(`'. $antrians->ticket_order .'`)"><i class="fas fa-eye"></i> Pelunasan</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="lihatAntrian(`'. $antrians->ticket_order .'`)"><i class="fas fa-list"></i> Detail</button>
            </div>
            ';
        })
        ->make(true);
    }

    public function ringkasanFilter(Request $request)
    {
        if(request()->has('tanggal')) {
            $date = request('tanggal');
        } else {
            $date = date('Y-m-d');
        }

        if(request()->has('sales')) {
            $sales = request('sales');
        } else {
            $sales = null;
        }

        $salesName = Sales::where('id', $sales)->first();

        $isFilter = true;

        return view('page.report.ringkasan-filter', compact('date', 'salesName' , 'isFilter', 'sales'));
    }

    public function dataFilter($sales, $date)
    {
            $antrians = Antrian::with('payment', 'order', 'sales', 'customer', 'job', 'design', 'operator', 'finishing')
            ->where('created_at', 'like', '%' . $date . '%')
            ->where('sales_id', $sales)
            ->get();

        return Datatables::of($antrians)
        ->addColumn('created_at', function ($antrians) {
            return $antrians->created_at->format('d-m-Y');
        })
        ->addColumn('ticket_order', function ($antrians) {
            return $antrians->ticket_order;
        })
        ->addColumn('sales', function ($antrians) {
            return $antrians->sales_id == 0 ? '-' : $antrians->sales->sales_name;
        })
        ->addColumn('customer', function ($antrians) {
            return $antrians->customer->nama;
        })
        ->addColumn('job', function ($antrians) {
            return $antrians->job->job_name;
        })
        ->addColumn('qty', function ($antrians) {
            return $antrians->qty;
        })
        ->addColumn('harga_produk', function ($antrians) {
            return 'Rp'. number_format($antrians->harga_produk, 0, ',', '.');
        })
        ->addColumn('end_job', function ($antrians) {
            return $antrians->end_job;
        })
        ->addColumn('file_cetak', function ($antrians) {
            return $antrians->order->file_cetak;
        })
        ->addColumn('action', function ($antrians) {
            return '
            <div class="btn-group">
                <button class="btn btn-sm btn-warning" type="button" onclick="lihatPelunasan(`'. $antrians->ticket_order .'`)"><i class="fas fa-eye"></i> Pelunasan</button>
                <button class="btn btn-sm btn-primary" type="button" onclick="lihatAntrian(`'. $antrians->ticket_order .'`)"><i class="fas fa-list"></i> Detail</button>
            </div>
            ';
        })
        ->make(true);
    }

    public function mesin()
    {
        $machine = Machine::all();

        return response()->json($machine);
    }
}
