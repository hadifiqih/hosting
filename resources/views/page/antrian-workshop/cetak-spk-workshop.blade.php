<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export PDF - Laporan Workshop</title>
    <style>
        /* Memberi border pada tabel */
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        /* Memberi padding pada tabel */
        th, td {
            padding: 5px;
        }

        /* Font DejaVuSans */
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url({{ storage_path('fonts/DejaVuSans.ttf') }}) format('truetype');
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom-width: 2px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .bg-danger {
            color: #fff;
            background-color: #dc3545;
        }

        .bg-success {
            color: #fff;
            background-color: #28a745;
        }

        .bg-dark {
            color: #fff;
            background-color: #343a40;
        }

        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
    <div id="printedSPK" class="container">
        <h4 class="text-center mt-3"><strong>Surat Perintah Kerja (e-SPK)</strong></h4>

        <div class="row table-responsive">
          <table class="table table-bordered table-striped mt-3">
            <tr class="bg-dark">
              <td class="text-center text-white" colspan="4">No. SPK : SPK-{{ $antrian->ticket_order }}</td>
            </tr>

            <tr>
              <td class="text-center" colspan="4">{{ $order->title }}</td>
            </tr>

            <tr>
              <td>Tanggal</td>
              <td>: {{ $antrian->created_at }}</td>
              <td>Lokasi Pengerjaan</td>
              <td>: 
                @php
                    $location = $antrian->cabang_id;
                    //explode string
                    $explode = explode(',', $location);
                    //for each
                    foreach ($explode as $key => $value) {
                        $cabang = App\Models\Cabang::where('id', $value)->first();
                        $tempat = "";
                        if(end($explode) == $value){
                            echo $tempat .= $cabang->nama_cabang;
                        }else{
                            echo $tempat .= $cabang->nama_cabang.',';
                        }
                    }
                @endphp
              </td>
            </tr>
            
            <tr>
              <td>Mulai</td>
              <td>: {{ $dataKerja->tgl_mulai }}</td>
              <td>Selesai</td>
              <td>: {{ $dataKerja->tgl_selesai }}</td>
            </tr>

            <tr class="bg-dark">
              <td class="text-center text-white" colspan="4">Pelanggan</td>
            </tr>

            <tr>
              <td>Customer</td>
              <td>: {{ $customer->nama }}</td>
              <td>Alamat</td>
              <td>: {{ $customer->alamat }}</td>
            </tr>

            <tr>
              <td>Instansi</td>
              <td>: {{ $customer->instansi }}</td>
              <td>Telepon</td>
              <td>: {{ $customer->telepon }}</td>
            </tr>

            <tr class="bg-dark text-white">
              <td class="text-center" colspan="4">Gambar ACC Desain</td>
            </tr>

            <tr>
              <td class="text-center" colspan="4">
                @foreach ($barang as $item)
                    <img src="{{ asset($item->accdesain) }}" alt="" width="200px">
                @endforeach
              </td>
            </tr>

            <tr class="bg-dark text-white">
              <td class="text-center" colspan="4">Daftar Pekerjaan</td>
            </tr>

            <tr>
              <td class="text-center">Nama Pekerjaan</td>
              <td class="text-center">Jumlah</td>
              <td colspan="2" class="text-center">Keterangan</td>
            </tr>

            @foreach ($barang as $item)
            <tr>
              <td>{{ $item->job->job_name }}</td>
              <td class="text-center">{{ $item->qty }}</td>
              <td colspan="2">{{ $item->note }}</td>
            </tr>
            @endforeach

            <tr class="bg-dark text-white">
              <td colspan="4" class="text-center">Penugasan</td>
            </tr>

            <tr>
              <td>Desain</td>
              <td>Operator</td>
              <td>Finishing</td>
              <td>Quality Control</td>
              
            </tr>

            <tr>
              <td>{{ $order->employee->name }}</td>
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

            <tr>
              <td class="text-center">Catatan</td>
              <td colspan="3">: {{ $antrian->admin_note }}</td>
            </tr>

          </table>
        </div>
    </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
      <button type="button" class="btn btn-primary">Unduh</button>
    </div>
  </div>
</div>
</div>

</body>
</html>
