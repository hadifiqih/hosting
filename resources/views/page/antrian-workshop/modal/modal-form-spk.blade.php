<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form e-SPK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto Mono', monospace;
            font-size: 14px;
        }

        .spesifikasi {
          white-space: pre-line;
        }
    </style>

</head>
<body>
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-4"></div>
        <div class="col-4 text-center mt-5">
            <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalspk">
            Buka Form e-SPK
          </button>
        </div>
      <div class="col-4"></div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="modalspk" tabindex="-1" aria-labelledby="modalspkLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalspkLabel">Form e-SPK</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="printedSPK" class="container">
          <h4 class="text-center mt-3"><strong>Surat Perintah Kerja (e-SPK)</strong></h4>

          <div class="row table-responsive">
            <table class="table table-bordered table-striped mt-3">
              <tr class="bg-dark">
                <td class="text-center text-white" colspan="4">No. SPK : SPK-{{ $antrian->ticket_order }}</td>
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

              <tr class="bg-dark">
                <td class="text-center text-white" colspan="4">Gambar ACC Desain</td>
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
                <td class="text-center">Keterangan</td>
                <td class="text-center">Desainer</td>
              </tr>

              @foreach ($barang as $item)
              <tr>
                <td>{{ $item->job->job_name }}</td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="spesifikasi">{{ $item->note }}</td>
                <td>{{ isset($item->designQueue->designer->name) ? $item->designQueue->designer->name : '-' }}</td>
              </tr>
              @endforeach

              <tr class="bg-dark text-white">
                <td colspan="4" class="text-center text-white">Penugasan</td>
              </tr>

              <tr>
                <td>Operator</td>
                <td colspan="2">Finishing</td>
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
                <td colspan="2">
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
        <button id="downloadButton" type="button" class="btn btn-primary">Unduh</button>
      </div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function(){
      $('#modalspk').modal('show');

      $('#downloadButton').click(function() {
          // convert printedSPK to image with html2canvas high resolution
          html2canvas(document.getElementById('printedSPK'), { scale: 2 })
            .then(function(canvas) {
              var link = document.createElement('a');
              link.href = canvas.toDataURL('image/png');
              link.download = 'printedSPK.png';
              link.click();
            });
        });
      });
  </script>
</body>
</html>
