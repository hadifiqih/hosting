<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-SPK (Surat Perintah Kerja)</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            background-color: #343a40;
            color: #fff;
            padding: 10px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            margin: 0;
        }

        .info {
            margin-top: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .spesifikasi {
            white-space: pre-line;
        }

        .job-details {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .job-details h2 {
            font-size: 18px;
            margin-bottom: 10px;
            margin-top: 3px;
        }

        .job-details p {
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: grey;
            font-style: italic;
        }

        .serial-number {
            margin-top: 6px;
            font-size: 18spx;
            font-weight: bold;
            color: #ffffff;
        }

        .text-danger {
            color: #dc3545;
        }

        /* Align text left <tr></tr> */
        .text-left {
            text-align: left;
        }

        table, th, td {
            border: 1px solid rgb(216, 216, 216);
            border-collapse: collapse;
        }

        .table {
            margin-top: 10px;
        }

        .table td {
            padding: 5px 10px;
        }

        .table th {
            padding: 10px;
        }

        .table-warning {
            background-color: #fff3cd;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        .text-success {
            color: #28a745;
        }

        .text-center {
            text-align: center;
        }

        .table-responsive {
            width: 100%;
        }

        .ml-2 {
            margin-left: 20px;
        }

        .bg-success {
            background-color: #28a745;
            color: #fff;
        }

        .bg-danger {
            background-color: #dc3545;
            color: #fff;
        }

        .bg-outline-success {
            background-color: #cefad0;
            color: #343a40;
        }

        .bg-outline-danger {
            background-color: #f8d7da;
            color: #343a40;
        }

        .table-header {
            width: 15%;
            text-align: left;
        }

        .px-3 {
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .signature {
            text-align: right;
            margin-top: 50px;
            margin-right: 50px;
        }

        .signature p {
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Surat Perintah Kerja (e-SPK)</div>
        <div class="serial-number">Nomor Tiket: {{ $antrian->ticket_order }}</div>
    </div>

    <table class="table table-responsive text-center">
        <tr>
            <td width="50%"><strong class="text-success" style="font-size: 30px;">Mulai</strong></td>
            <td width="50%"><strong class="text-danger" style="font-size: 30px">Deadline</strong></td>
        </tr>
        <tr>
            <td class="bg-outline-success px-3" style="font-size: 20px; font-weight:bold;">{{ $antrian->start_job }}</td>
            <td class="bg-outline-danger px-3" style="font-size: 20px; font-weight:bold;">{{ $antrian->end_job }}</td>
        </tr>
    </table>

    <div class="job-details">
        <h2>Spesifikasi Pekerjaan</h2>
        <hr>
        <table class="table table-responsive text-left">
            <tbody>
                <tr>
                    <th class="table-header">Jenis Pekerjaan</th>
                    <td>: {{ $antrian->job->job_name }}</td>
                    <td rowspan="9" class="text-center">
                        <img src="{{ asset('storage/acc-desain/' . $antrian->order->acc_desain) }}" alt="Gambar Pekerjaan" width="70%">
                    </td>
                </tr>
                <tr>
                    <th class="table-header">Sales</th>
                    <td>: {{ $antrian->sales->sales_name }}</td>
                </tr>
                <tr>
                    <th class="table-header">Jumlah</th>
                    <td>: {{ $antrian->qty }}</td>
                </tr>
                <tr>
                    <th class="table-header">Deskripsi</th>
                    <td class="spesifikasi">: {{ $antrian->note }}</td>
                </tr>
                <tr>
                    <th class="table-header">Lokasi Workshop</th>
                    <td>: {{ $antrian->working_at }}</td>
                </tr>
                <tr>
                    <th class="table-header">Omset</th>
                    <td>: Rp{{ number_format($antrian->omset,0,',','.') }}</td>
                </tr>
                <tr>
                    <th class="table-header">Desainer</th>
                    <td>: {{ $antrian->order->employee->name }}</td>
                </tr>
                <tr>
                    <th class="table-header">Operator</th>
                    <td>:
                    @if($antrian->operator_id)
                        @php
                            $operatorId = explode(',', $antrian->operator_id);
                            foreach ($operatorId as $item) {
                                if($item == 'rekanan'){
                                    echo '- Rekanan';
                                }
                                else{
                                    $antriann = App\Models\Employee::find($item);
                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                    if($antriann->id == end($operatorId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        @endphp
                        @else
                        -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="table-header">Finishing</th>
                    <td>:
                        @if($antrian->finisher_id)
                            @php
                                $finisherId = explode(',', $antrian->finisher_id);
                                foreach ($finisherId as $item) {
                                    if($item == 'rekanan'){
                                        echo '- Rekanan';
                                    }
                                    else{
                                        $antriann = App\Models\Employee::find($item);
                                        //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                        if($antriann->id == end($finisherId)){
                                            echo '- ' . $antriann->name;
                                        }
                                        else{
                                            echo '- ' . $antriann->name . "<br>";
                                        }
                                    }
                                }
                            @endphp
                            @else
                            -
                            @endif
                    </td>
                </tr>
                <tr>
                    <th class="table-header">Pengawas / QC</th>
                    <td>:
                        @if($antrian->qc_id)
                            @php
                                $qcId = explode(',', $antrian->qc_id);
                                foreach ($qcId as $item) {
                                        $antriann = App\Models\Employee::find($item);
                                        //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                        if($antriann->id == end($qcId)){
                                            echo '- ' . $antriann->name;
                                        }
                                        else{
                                            echo '- ' . $antriann->name . "<br>";
                                        }
                                    }
                            @endphp
                            @else
                                -
                            @endif
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

    <div class="footer">
        *Surat Perintah Kerja ini dibuat secara otomatis oleh sistem e-SPK, jika ada kesalahan / pertanyaan silahkan hubungi Admin Workshop / Sales.
    </div>
</body>
</html>
