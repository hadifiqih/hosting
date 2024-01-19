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
    </style>
    <style>
        body {
            font-family: 'Victor Mono', monospace;
            font-size: 10px;
        }
        #printContents {
            max-width: 200px;
            font-size: 14px;
            margin: 0 auto;
            padding: 0 10px;
        }
        #printContents h6 {
            margin: 0;
        }
        /* Set margin y 0 */
        h6, p, td, th, th, table {
            margin: 0;
            font-size: 12px;
            padding: 0;
        }

        /* Set padding y 0 */
        .table-borderless td, .table-borderless th {
            padding: 3px;
        }
        
    </style>

    
</head>
<body>
  <!-- Modal -->
<div class="modal fade" id="modalInvoice" tabindex="-1" aria-labelledby="modalInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Invoice Order</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
              <table class="table-borderless">
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
      
          <div class="row">
              <table class="table table-borderless pe-0">
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
      
                <div class="row text-end me-0 pe-0 ms-2">
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
      
              <div class="d-flex justify-content-center my-3">
                <div class="row">
                  <p class="fw-bold text-center">Terima kasih, selamat berbelanja kembali !</p>
                </div>
              </div>
              <div class="row">{{ $qrCode }}</div>
      </div>
      <div class="modal-footer mt-3">
        <button type="button" onclick="screenshot()" class="btn btn-primary">Download</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      function screenshot() {
        html2canvas(document.querySelector("#printContents")).then(canvas => {
          // document.body.appendChild(canvas)
          var link = document.createElement('a');
          var timestamp = new Date().getTime();
          link.download = timestamp + '_Invoice-Order.jpg';
          link.href = canvas.toDataURL()
          link.click();
        });
        
      }
      //onload
      $(document).ready(function() {
        $('#modalInvoice').modal('show');
      });
    </script>
</body>
  
</html>