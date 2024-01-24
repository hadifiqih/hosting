<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja</title>
    <style>

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

      .text-end {
        text-align: right;
      }

      .text-center {
        text-align: center;
      }

      .text-start {
        text-align: left;
      }

      p {
        margin: 0;
        text-size : 12px;
      }

      </style>
      </head>
      <body class="background-lunas">
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
</body>
  
</html>