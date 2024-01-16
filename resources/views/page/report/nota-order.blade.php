<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto Mono', monospace;
        }
        #printContents {
            max-width: 284px;
            font-size: 14px;
            margin: 0 auto;
            padding: 0 10px;
        }
        #printContents h6 {
            margin: 0;
        }
    </style>

    
</head>
<body>
  <div class="container">
  <div id="printContents" class="text-sm">
    <div class="row">
        <div class="col-12">
            <h6 class="text-center">Bahan Stempel Malang</h6>
            <h6 class="text-center">Jl. Candi Jago No. 1, Blimbing, Kota Malang, Jawa Timur, 65125</h6>
            <h6 class="text-center">Telp/WA: 0813-3467-3331</h6>
            <h6 class="text-center">--------------------</h6>
        </div>
    </div>

    <div class="row">
        <table class="table table-borderless">
          <tr>
            <td>No</td>
            <td>: {{ $order->ticket_order }}</td>
          </tr>
          <tr>
            <td>Kasir</td>
            <td>: {{ $order->sales->sales_name }}</td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>: {{ $order->created_at }}</td>
          </tr>
          <tr>
            <td>Pelanggan</td>
            <td>: {{ $order->customer->nama }}</td>
          </tr>
        </div>
      </table>
    </div>

    <div class="row">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th>Nama Barang</th>              
              <th>Harga</th>            
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($items as $item)
            <tr>
              <td>{{ $item->job->job_name }}</td>
              <td>{{ number_format($item->price, 0, ',', '.') }} X {{ $item->qty }}</td>         
              <td class="text-end"><strong>{{ number_format($item->price * $item->qty, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach          
          </tbody>
        </table>
      </div>

        <div class="row d-flex justify-content-end">
          <div class="text-end">
            <h6 class="text-end">--------------------</h6>
            <p>Subtotal: <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></p>
            <p>Diskon: <strong>Rp {{ number_format($diskon, 0, ',', '.') }}</strong></p>
            <p>Biaya Pasang: <strong>Rp {{ number_format($totalPasang, 0, ',', '.') }}</strong></p>
            <p>Biaya Pengiriman: <strong>Rp {{ number_format($totalOngkir, 0, ',', '.') }}</strong></p>
            <p>Biaya Packing: <strong>Rp {{ number_format($totalPacking, 0, ',', '.') }}</strong></p>
            <h6 class="text-end">--------------------</h6>
            <p>Grand Total: <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></p>
            <p>Tunai/Transfer: <strong>Rp {{ number_format($infoBayar->dibayarkan, 0, ',', '.') }}</strong></p>
            <p>Sisa Tagihan: <strong>Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></p>
            <p>Metode: <strong>{{ $infoBayar->metode_pembayaran }}</strong></p>
          </div>
        </div>

        <div class="d-flex justify-content-center">
          <div class="row">
            <p class="fw-bold text-center">Terima kasih, selamat berbelanja kembali !</p>
          </div>
        </div>
          <div class="row">{{ $qrCode }}</div>
        </div>
      </div>

      <div class="row">
        {{-- Tombol Cetak --}}
        <div class="col-6 text-center">
          <button id="btnCetak" class="btn btn-primary" onclick="printDiv('printContents')">Cetak</button>
        </div>

        <div class="col-6 text-center">
          <button id="btnJpg" class="btn btn-primary" onclick="screenshot()">Cetak JPG</button>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      function screenshot() {
        html2canvas(document.querySelector("#printContents")).then(canvas => {
          // document.body.appendChild(canvas)
          var link = document.createElement('a');
          link.download = 'Struk Belanja.jpg';
          link.href = canvas.toDataURL()
          link.click();
        });
        
      }
    </script>
</body>
  
</html>