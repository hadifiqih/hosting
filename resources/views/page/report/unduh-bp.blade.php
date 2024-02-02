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

    .button-container {
        text-align: center;
        margin-top: 20px;
    }

    .button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
    }

    .button:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>

<div class="container">
    <h3 class="text-center">Laporan Biaya Produksi Cetak</h3>
    <p class="text-center">Nomor Tiket : {{ $antrian->ticket_order }}</p>
    <table>
        <tr>
            <td>Sales</td>
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
    </table>

    <h3 class="text-center m-2">Penugasan Pengerjaan</h3>
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

    <h3 class="text-center m-2">Biaya Produksi</h3>
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
        <tr>
            <td class="text-center">Biaya Sales (3%)</td>
            <td> {{ $biaya->biaya_sales }}</td>
            <td class="text-center">BPJS (2.5%)</td>
            <td> {{ $antrian->biaya_desain }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Desain</td>
            <td> {{ $biaya->biaya_desain }}</td>
            <td class="text-center">Biaya Transportasi</td>
            <td> {{ $biaya->biaya_transportasi }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Penanggung Jawab</td>
            <td> {{ $biaya->biaya_transportasi }}</td>
            <td class="text-center">Biaya Overhead/Lain-lain</td>
            <td> {{ $biaya->biaya_lain }}</td>
        </tr>
        <tr>
            <td class="text-center">Biaya Pekerja</td>
            <td> {{ $biaya->biaya_pekerja }}</td>
            <td class="text-center">Biaya Alat dan Listrik</td>
            <td> Rp{{ $biaya->biaya_alat_listrik}}</td>
        </tr>

    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
