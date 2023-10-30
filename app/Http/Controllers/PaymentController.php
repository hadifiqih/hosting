<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Antrian;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();

        return view ('antrian.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $antrian = Antrian::where('ticket_order', $request->order_number)->first();

        $status = $request->payment_amount == $request->omset ? 1 : 0;
        dd($antrian->ticket_order,$request->omset, $status);

        //Membuat $fileName dengan kondisi jika status = 1 maka nama file + ticket_order = fullpayment.jpg Jika tidak maka nama file + ticket_order = downpayment.jpg
        $file = $request->file('payment_proof');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $fileName = $status == 1 ? 'fullpayment'. $fileName : 'downpayment' . $fileName;
        $path = $file->storeAs('public/payment-proof', $fileName);

        $validated = $request->validate([
            'order_number' => 'required',
            'omset' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0|max:' . $request->omset,
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bank' => 'required'
        ]);

        $validated['payment_status'] = $validated['payment_amount'] == $validated['omset'] ? 1 : 0;
        $validated['payment_proof'] = $path; // Menyimpan path ke payment_proof dalam field yang sesuai

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Pembayaran dikonfirmasi !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket)
    {
        //
    }

    public function edit($id)
    {
        $payments = Payment::where('ticket_order', $id)->first();

        return view('antrian.payment.edit', compact('payments'));
    }

    public function updatePelunasan(Request $request)
    {
        if(isset($request->filePelunasan)){
            $file = $request->file('filePelunasan');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = 'bukti-pembayaran/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        }else{
            $fileName = null;
        }

        $metodePembayaran = $request->metodePembayaran;
        $jumlahPembayaran = str_replace(['Rp ', '.'], '', $request->jumlahPembayaran);
        $jumlahPembayaran = (int)$jumlahPembayaran;
        $ticket = $request->ticketAntrian;

        $payment = Payment::where('ticket_order', $ticket)->orderBy('created_at', 'desc')->first();
        $hutang = $payment->remaining_payment;
        $totalOmset = $payment->total_payment;
        $dibayar = $payment->payment_amount + $jumlahPembayaran;
        $ongkir = $payment->shipping_cost;
        $pasang = $payment->installation_cost;

        if($hutang == $jumlahPembayaran){
            $hutang = 0;
            $status = "Lunas";
        }else{
            $hutang = $hutang - $jumlahPembayaran;
            $status = "DP";
        }

        try{
            $payment = new Payment;
            $payment->ticket_order = $ticket;
            $payment->total_payment = $totalOmset;
            $payment->payment_amount = $dibayar;
            $payment->shipping_cost = $ongkir;
            $payment->installation_cost = $pasang;
            $payment->remaining_payment = $hutang;
            $payment->payment_status = $status;
            $payment->payment_method = $metodePembayaran;
            $payment->payment_proof = $fileName;
            $payment->save();
        }catch(Throwable $e){
            return redirect()->back()->with('error', 'Pelunasan gagal diperbarui !');
        }

        return redirect()->route('antrian.index')->with('success', 'Pelunasan berhasil diperbarui !');
    }

    public function destroy($id)
    {
        //
    }
}
