<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Struk Invoice Percetakan</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 14px;
    }

    .container {
      width: 900px;
      margin: 0 auto;
    }

    .spesifikasi {
      white-space: pre-line;
    }

    header {
      background-color: #f0f0f0;
      padding: 10px;
    }

    main {
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }

    th {
      text-align: left;
    }

    p {
      text-align: center;
    }

    h1, h2, h3, h4 {
      margin: 10px 0;
    }

    .text-red {
      color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Invoice Order</h1>
      <h2>{{ $order->sales->sales_name }}</h2>
      <h3>{{ $order->sales->address }}</h3>
      <h4>Telp / WA : {{ $order->sales->sales_phone }}</h4>
    </header>
    <main>
      <table>
        <tr>
          <th>No. Invoice</th>
          <td>{{ $order->ticket_order }}</td>
        </tr>
        <tr>
          <th>Tanggal</th>
          <td>{{ $order->created_at }}</td>
        </tr>
        <tr>
          <th>Pelanggan</th>
          <td>{{ $order->customer->nama }}</td>
        </tr>
        <tr>
          <th>Alamat</th>
          <td>{{ $order->customer->alamat != null ? $order->customer->alamat : '-'}}</td>
        </tr>
        <tr>
          <th>Telepon</th>
          <td>{{ $order->customer->telepon }}</td>
        </tr>
      </table>
      <h3>Informasi Pembelian</h3>
      <table style="margin-top: 10px">
        <tr>
          <th>Jenis Cetak</th>
          <th>Jumlah</th>
          <th>Harga</th>
          <th>Total</th>
        </tr>
        @foreach($items as $item)
        <tr>
          <td>{{ $item->job->job_name }}</td>
          <td>{{ $item->qty }}</td>
          <td>Rp. {{ number_format($item->price, 0, ',', '.') }}</td>
          <td>Rp. {{ number_format($item->price*$item->qty, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="3"><strong>Subtotal</strong></td>
          <td class="text-red"><strong>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
          <td colspan="3">Biaya Ongkir</td>
          <td>Rp. {{ number_format($totalOngkir, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3">Biaya Pasang</td>
          <td>Rp. {{ number_format($totalPasang, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3">Biaya Packing</td>
          <td>Rp. {{ number_format($totalPacking, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3">Diskon</td>
          <td>Rp. {{ number_format($infoBayar->diskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3"><strong>Grand Total</strong></td>
          <td class="text-red"><strong>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
          <td colspan="3">Bayar</td>
          <td>Rp. {{ number_format($infoBayar->dibayarkan, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3"><strong>Sisa Tagihan</strong></td>
          <td class="text-red"><strong>Rp. {{ number_format($sisaTagihan, 0, ',', '.') }}</strong></td>
        </tr>
      </table>
      <h3>Spesifikasi :</h3>
      <table>
        @foreach($items as $item => $value)
        <tr>
          <td><strong>Jenis Cetak</strong></td>
          <td><strong>{{ $value->job->job_name }}</strong></td>
        </tr>
        <tr>
          <td colspan="2" class="spesifikasi">{{ $value->note }}</td>
        </tr>
        @endforeach
      </table>
    </main>
    
  </div>
</body>
</html>