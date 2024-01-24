<?php

namespace App\Http\Controllers;

use PDF;
use QrCode;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDFSnappy;
use ImageSnappy;

use App\Models\Order;
use App\Models\Sales;
use App\Models\Barang;
use App\Models\Antrian;
use App\Models\Machine;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use Mike42\Escpos\Printer;
use App\Models\DataAntrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportResource;

use Yajra\DataTables\Facades\DataTables;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;



class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cetakFormEspk($id)
    {
        
    }

    public function notaOrderPDF($id)
    {
        $order = DataAntrian::where('ticket_order', $id)->first();

        $items = Barang::where('ticket_order', $id)->get();
        //HITUNG TOTAL HARGA
        $totalHarga = 0;
        $totalPacking = 0;
        $totalOngkir = 0;
        $totalPasang = 0;
        $diskon = 0;
        foreach ($items as $item) {
            $totalHarga += $item->price * $item->qty;
        }

        $infoBayar = Pembayaran::where('ticket_order', $id)->first();
        $totalPacking = $infoBayar->biaya_packing;
        $totalPasang = $infoBayar->biaya_pasang;
        $diskon = $infoBayar->diskon;
        
        $infoPengiriman = Pengiriman::where('ticket_order', $id)->first();
        $totalOngkir = $infoPengiriman->ongkir;

        $grandTotal = $totalHarga + $totalPacking + $totalOngkir + $totalPasang - $diskon;
        $sisaTagihan = $grandTotal - $infoBayar->dibayarkan;

        $qrCode = QrCode::size(70)->generate($order->ticket_order);

        $pdf = PDF::loadview('page.report.form-nota-order2', compact('order', 'items', 'totalHarga', 'totalPacking', 'totalOngkir', 'totalPasang', 'diskon', 'grandTotal', 'sisaTagihan', 'infoBayar', 'qrCode'))->setPaper('a4', 'portrait');
        return $pdf->stream($order->ticket_order . "_" . $order->order->title . '_nota-order.pdf');
    }

    public function notaOrderView($id)
    {
        $order = DataAntrian::where('ticket_order', $id)->first();

        $items = Barang::where('ticket_order', $id)->get();
        //HITUNG TOTAL HARGA
        $totalHarga = 0;
        $totalPacking = 0;
        $totalOngkir = 0;
        $totalPasang = 0;
        $diskon = 0;
        foreach ($items as $item) {
            $totalHarga += $item->price * $item->qty;
        }

        $infoBayar = Pembayaran::where('ticket_order', $id)->first();
        $totalPacking = $infoBayar->biaya_packing;
        $totalPasang = $infoBayar->biaya_pasang;
        $diskon = $infoBayar->diskon;
        
        $infoPengiriman = Pengiriman::where('ticket_order', $id)->first();
        $totalOngkir = $infoPengiriman->ongkir;

        $grandTotal = $totalHarga + $totalPacking + $totalOngkir + $totalPasang - $diskon;
        $sisaTagihan = $grandTotal - $infoBayar->dibayarkan;

        $qrCode = QrCode::size(70)->generate($order->ticket_order);

        return view('page.report.view-nota-order', compact('order', 'items', 'totalHarga', 'totalPacking', 'totalOngkir', 'totalPasang', 'diskon', 'grandTotal', 'sisaTagihan', 'infoBayar', 'qrCode'));
    }

    public function notaOrder($id)
    {
        //function pembagian kolom
        function buatBaris1Kolom($kolom1)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 45;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = count($kolom1Array);

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 15;
            $lebar_kolom_2 = 15;
            $lebar_kolom_3 = 15;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        function buatBaris2Kolom($kolom1, $kolom2)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 30;
            $lebar_kolom_2 = 15;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ", STR_PAD_LEFT);
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        $order = DataAntrian::where('ticket_order', $id)->first();
        $items = Barang::where('ticket_order', $id)->get();
        $sales = Sales::where('id', $order->sales_id)->first();

        //HITUNG TOTAL HARGA
        $totalHarga = 0;
        $totalPacking = 0;
        $totalOngkir = 0;
        $totalPasang = 0;
        $diskon = 0;
        foreach ($items as $item) {
            $totalHarga += $item->price * $item->qty;
        }

        $infoBayar = Pembayaran::where('ticket_order', $id)->first();
        $totalPacking = $infoBayar->biaya_packing;
        $totalPasang = $infoBayar->biaya_pasang;
        $diskon = $infoBayar->diskon;

        $infoPengiriman = Pengiriman::where('ticket_order', $id)->first();
        $totalOngkir = $infoPengiriman->ongkir;

        $grandTotal = $totalHarga + $totalPacking + $totalOngkir + $totalPasang - $diskon;
        $sisaTagihan = $grandTotal - $infoBayar->dibayarkan;

        //print nota order dengan menggunakan printer thermal
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("POS-80");
        $printer = new Printer($connector, $profile);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("NOTA ORDER\n");
        $printer->text($sales->sales_name."\n");
        $printer->text($sales->address."\n");
        $printer->text("WA. ".$sales->sales_phone."\n");
        $printer->text("=============================================\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("No. Order : " . $order->ticket_order . "\n");
        $printer->text("Tanggal : " . $order->created_at->format('d-m-Y H:i') . "\n");
        $printer->text("Customer : " . $order->customer->nama . "\n");
        $printer->text("Sales : " . $order->sales->sales_name . "\n");
        $printer->text("=============================================\n");
        foreach ($items as $item) {
            $printer->text(buatBaris1Kolom($item->job->job_name));
            $printer->text(buatBaris3Kolom($item->qty . " PCS", number_format($item->price, 0, ',', '.') ,number_format($item->price * $item->qty, 0, ',', '.')));
        }
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("=============================================\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setPrintWidth(64);
        $printer->setEmphasis(true);
        $printer->text(buatBaris2Kolom("Total Harga : " , number_format($totalHarga, 0, ',', '.') . "\n"));
        $printer->setEmphasis(false);
        $printer->text(buatBaris2Kolom("Biaya Packing : " , number_format($totalPacking, 0, ',', '.') . "\n"));
        $printer->text(buatBaris2Kolom("Biaya Ongkir : " , number_format($totalOngkir, 0, ',', '.') . "\n"));

        if ($totalPasang != 0) {
            $printer->text(buatBaris2Kolom("Biaya Pasang : " , number_format($totalPasang, 0, ',', '.') . "\n"));
        }

        if ($diskon != 0) {
            $printer->text(buatBaris2Kolom("Diskon : " , number_format($diskon, 0, ',', '.') . "\n"));
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("=============================================\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 4);
        $printer->text(buatBaris2Kolom("Grand Total : " , number_format($grandTotal, 0, ',', '.') . "\n"));
        $printer->setTextSize(1, 1);
        $printer->text(buatBaris2Kolom("Dibayarkan : " , number_format($infoBayar->dibayarkan, 0, ',', '.') . "\n"));
        $printer->text(buatBaris2Kolom("Sisa Tagihan : " , number_format($sisaTagihan, 0, ',', '.') . "\n"));
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("=============================================\n");
        $printer->text("-- Terima Kasih --\nSelamat Datang Kembali\n");
        $printer->text("=============================================\n");
        $printer->setBarcodeWidth(8);
        $printer->barcode((string)$order->ticket_order, Printer::BARCODE_CODE39);

        $printer->cut();
        $printer->close();
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
        $tempat = $request->input('tempat_workshop');
        // $tanggalAwal adalah selalu tanggal 1 dari bulan yang dipilih
        $tanggalAwal = date('Y-m-01 00:00:00');
        // $tanggalAkhir adalah selalu tanggal sekarang dari bulan yang dipilih
        $tanggalAkhir = date('Y-m-d 23:59:59');

        $antrianStempel = Barang::where('kategori_id', '1')
        ->whereHas('antrian', function ($query) use ($tempat, $tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('sales', function ($subquery) use ($tempat) {
                $subquery->where('cabang_id', $tempat);
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            });
        })
        ->get();

        $antrianAdvertising = Barang::where('kategori_id', '3')
        ->whereHas('antrian', function ($query) use ($tempat, $tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('sales', function ($subquery) use ($tempat) {
                $subquery->where('cabang_id', $tempat);
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            });
        })
        ->get();

        $antrianNonStempel = Barang::where('kategori_id', '2')
        ->whereHas('antrian', function ($query) use ($tempat, $tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('sales', function ($subquery) use ($tempat) {
                $subquery->where('cabang_id', $tempat);
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            });
        })
        ->get();

        $antrianDigiPrint = Barang::where('kategori_id', '4')
        ->whereHas('antrian', function ($query) use ($tempat, $tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('sales', function ($subquery) use ($tempat) {
                $subquery->where('cabang_id', $tempat);
            })
            ->where(function ($query) {
                $query->where('status', '1')->orWhere('status', '2');
            });
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
            $totalOmsetStempel += $antrian->price * $antrian->qty;
            $totalQtyStempel += $antrian->qty;
        }

        foreach ($antrianAdvertising as $antrian) {
            $totalOmsetAdvertising += $antrian->price * $antrian->qty;
            $totalQtyAdvertising += $antrian->qty;
        }

        foreach ($antrianNonStempel as $antrian) {
            $totalOmsetNonStempel += $antrian->price * $antrian->qty;
            $totalQtyNonStempel += $antrian->qty;
        }

        foreach ($antrianDigiPrint as $antrian) {
            $totalOmsetDigiPrint += $antrian->price * $antrian->qty;
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
