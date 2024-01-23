<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Victor+Mono&display=swap');

      table, th, td {
        font-family: 'Roboto Mono', monospace;
        border: 2px solid #ffffff;
      }

      .total {
        font-family: 'Roboto Mono', monospace;
      }

      .kop {
        font-family: 'Roboto Mono', monospace;
      }

      .spesifikasi {
            white-space: pre-line;
      }

      .background-lunas {
        background-image: url('{{ asset("storage/img/lunas.png") }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }

      .background-belum-lunas {
        background-image: url('{{ asset("storage/img/dp.png") }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }

      .page-break {
        page-break-after: always;
      }
      </style>
      </head>
      <body>
        <!-- Modal -->
      <div class="container page-break">
        <div id="printContents" class="text-sm">
          <div class="row">
            <div class="col-12">
              <p class="text-center kop">Bahan Stempel Malang</p>
              <p class="text-center kop">Jl. Candi Jago No. 1, Blimbing, Kota Malang, Jawa Timur, 65125</p>
              <p class="text-center kop">Telp/WA: 0813-3467-3331</p>
              <p class="text-center kop">--------------------</p>
            </div>
          </div>

          <div class="row">
            <table class="">
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
            </table>
            <p class="text-center">--------------------</p>
          </div>

          <div class="row">
            <table class="table">
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

          <div class="row text-end me-0 pe-0 ms-2 total">
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
          </div>

          <div class="row kop">Spesifikasi</div>
          <div class="row kop">--------------------</div>
          <div class="row">
            <table class="table">
              <thead>
                <tr>
                  <th>Nama Barang</th>              
                  <th>Spesifikasi</th>            
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $item)
                <tr>
                  <td>{{ $item->job->job_name }}</td>
                  <td class="spesifikasi">{{ $item->note }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
  
</html>