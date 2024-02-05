<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Biaya Produksi Cetak</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        margin: 0px auto;
        padding: 0px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

</style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Laporan Biaya Produksi</h2>
    <p class="text-center">Nomor Tiket : {{ $antrian->ticket_order }}</p>
    <table>
        <tr>
            <td>Sales</td>
            <td>{{ $antrian->sales->sales_name }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>{{ $antrian->customer->nama }}</td>
        </tr>
        <tr>
            <td>Omset</td>
            <td>Rp{{ number_format($omset,0,',','.') }}</td>
        </tr>
        <tr>
            <td>Jam / Tanggal Mulai</td>
            <td>{{ $antrian->dataKerja->tgl_mulai }}</td>
        </tr>
        <tr>
            <td>Jam / Tanggal Selesai</td>
            <td>{{ $antrian->dataKerja->tgl_selesai }}</td>
        </tr>
        <tr>
            <td>Total Biaya Produksi</td>
            <td style="color: darkred">Rp{{ number_format($totalProduksi,0,',','.') }} ({{ $persenBiayaProduksi }}%)</td>
        </tr>
        <tr>
            <td>Laba / Keuntungan</td>
            <td style="color: darkgreen"><strong>Rp{{ number_format($profit,0,',','.') }} ({{ $persenProfit }}%)</strong></td>
        </tr>
        
    </table>

    <h3 class="text-center mt-3 text-primary">Keterangan Orderan</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Jenis Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $barang->job->job_name }}</td>
                <td>{{ $barang->qty }}</td>
                <td>Rp{{ number_format($barang->price,0,',','.') }}</td>
                <td>Rp{{ number_format($barang->price * $barang->qty,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-center">Total</th>
                <th>Rp{{ number_format($omset,0,',','.') }}</th>
            </tr>
        </tfoot>
    </table>

    <h3 class="text-center mt-3 text-primary">Penugasan Pengerjaan</h3>
    <table>
        <tr>
            <td>Operator</td>
            <td>Finishing</td>
            <td>Quality Control</td>
        </tr>
          <tr>
            <td>
              @php
                    $operator = $dataKerja->operator_id;
                    //explode string
                    $explode = explode(',', $operator);
                    //for each
                    foreach ($explode as $value) {
                      $employee = App\Models\Employee::where('id', $value)->first();
                      $operator = "";
                      if($value == 'r'){
                        if(end($explode) == $value){
                          echo $operator .= '- Rekanan';
                        }else{
                          echo $operator .= '- Rekanan, <br>';
                        }
                      }else{
                        if(end($explode) == $value){
                            echo $operator .= '- ' . $employee->name;
                        }else{
                            echo $operator .= '- ' . $employee->name.', <br>';
                        }
                      }
                    }
                @endphp
            </td>
            <td>
              @php
                    $finishing = $dataKerja->finishing_id;
                    //explode string
                    $explode = explode(',', $finishing);
                    //for each
                    foreach ($explode as $value) {
                      $employee = App\Models\Employee::where('id', $value)->first();
                      $finishing = "";
                      if($value == 'r'){
                        if(end($explode) == $value){
                          echo $finishing .= '- Rekanan';
                        }else{
                          echo $finishing .= '- Rekanan, <br>';
                        }
                      }else{
                        if(end($explode) == $value){
                            echo $finishing .= '- ' . $employee->name;
                        }else{
                            echo $finishing .= '- ' . $employee->name.', <br>';
                        }
                      }
                    }
                @endphp
            </td>
            <td>
              @php
                  $qc = $dataKerja->qc_id;
                  //explode string
                  $explode = explode(',', $qc);
                  //for each
                  foreach ($explode as $value) {
                      $employee = App\Models\Employee::where('id', $value)->first();
                      $qc = "";
                      if(end($explode) == $value){
                          echo $qc .= '- ' . $employee->name;
                      }else{
                          echo $qc .= '- ' . $employee->name.', <br>';
                      }
                  }
              @endphp
            </td>
          </tr>
    </table>

    <h3 class="text-center mt-3 text-primary">Biaya Produksi</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Item</th>
                <th>Harga</th>
                <th>Note</th>
                <th>Orderan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Isi tabel laporan biaya produksi cetak -->
            @foreach($bahans as $bahan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bahan->nama_bahan }}</td>
                <td>Rp{{ number_format($bahan->harga,0,',','.') }}</td>
                <td>{{ $bahan->note }}</td>
                <td>{{ $bahan->barang->job->job_name }}</td>
            </tr>
            @endforeach
            <!-- Tambahkan baris tambahan sesuai kebutuhan -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">Total</th>
                <th colspan="3" class="text-center">Rp{{ number_format($total,0,',','.') }}</th>
            </tr>
        </tfoot>
    </table>

    <table>
        <tr><td colspan="4" class="text-center"><strong>Biaya Lain - Lain</strong> </td></tr>
        <tr>
            <td class="text-center">Biaya Sales (3%)</td>
            <td> Rp{{ $totalBiayaSales }}</td>
            <td class="text-center">BPJS (2.5%)</td>
            <td> Rp{{ $totalBiayaBPJS }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Desain (2%)</td>
            <td> Rp{{ $totalBiayaDesain }}</td>
            <td class="text-center">Biaya Transportasi (1%)</td>
            <td> Rp{{ $totalBiayaTransport }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Penanggung Jawab (3%)</td>
            <td> Rp{{ $totalBiayaPenanggungJawab }}</td>
            <td class="text-center">Biaya Overhead/Lain-lain (2.5%)</td>
            <td> Rp{{ $totalBiayaOverhead }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Pekerja (5%)</td>
            <td> Rp{{ $totalBiayaPekerja }}</td>
            <td class="text-center">Biaya Alat dan Listrik (2%)</td>
            <td> Rp{{ $totalBiayaListrik }}</td>
        </tr>
        <tr>
            <td class="text-center" colspan="2"><strong>Total</strong></td>
            <td class="text-center" colspan="2"><strong>Rp {{ $totalBiayaAllFormatted }}</strong></td>
        </tr>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
