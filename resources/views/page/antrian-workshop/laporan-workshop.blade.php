<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export PDF - Laporan Workshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
    <h1 class="text-center">Laporan Produktifitas Workshop {{ ucwords($tempat) }}</h1>
    <h6 class="text-center">Tanggal : {{ date('d-m-Y', strtotime($tanggalAwal)) . " - " . date('d-m-Y', strtotime($tanggalAkhir)) }}</h6>
    <hr>
    <h3>Laporan Stempel</h3>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <th>#</th>
            <th>Ticket Order</th>
            <th>Sales</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Desainer</th>
            <th>Operator</th>
            <th>Finishing</th>
            <th>Pengawas</th>
            <th>Status Deadline</th>
            <th>Omset</th>
        </thead>
        <tbody>
            @foreach($antrianStempel as $antrian)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-center">{{ $antrian->ticket_order }}</td>
                <td>{{ $antrian->sales->sales_name }}</td>
                <td>{{ $antrian->job->job_name }}</td>
                <td class="text-center">{{ $antrian->qty }}</td>
                <td>{{ $antrian->start_job }}</td>
                <td>{{ $antrian->timer_stop ? $antrian->timer_stop : 'Dalam Proses' }}</td>
                <td>{{ $antrian->order->employee->name }}</td>
                <td>
                    @php
                        $operatorId = explode(',', $antrian->operator_id);
                        foreach ($operatorId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($operatorId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $finisherId = explode(',', $antrian->finisher_id);
                        foreach ($finisherId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($finisherId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $qcId = explode(',', $antrian->qc_id);
                        foreach ($qcId as $item) {
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($qcId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                    @endphp
                </td>
                <td class="text-center">
                    @if ($antrian->deadline_status == "1" && $antrian->end_job != null)
                        <span class="badge bg-success">Tepat Waktu</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "2" && $antrian->end_job != null)
                        <span class="badge bg-danger">Terlambat</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job == null)
                        <span class="badge bg-danger">Belum Diantrikan</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job != null)
                        <span class="badge bg-warning">Diproses</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @endif
                </td>
                <td class="text-center"> {{ number_format($antrian->omset, 0, ',', '.'); }} </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">Total Order</td>
                <td class="text-center"> {{ $totalQtyStempel }} </td>
                <td colspan="7" class="text-center">Total Omset</td>
                <td class="text-center"> {{ number_format($totalOmsetStempel, 0, ',', '.'); }} </td>
            </tr>
        </tfoot>
    </table>
    <hr>

    <h3>Laporan Advertising</h3>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <th>#</th>
            <th>Ticket Order</th>
            <th>Sales</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Desainer</th>
            <th>Operator</th>
            <th>Finishing</th>
            <th>Pengawas</th>
            <th>Status Deadline</th>
            <th>Omset</th>
        </thead>
        <tbody>
            @foreach($antrianAdvertising as $antrian)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-center">{{ $antrian->ticket_order }}</td>
                <td>{{ $antrian->sales->sales_name }}</td>
                <td>{{ $antrian->job->job_name }}</td>
                <td class="text-center">{{ $antrian->qty }}</td>
                <td>{{ $antrian->start_job }}</td>
                <td>{{ $antrian->timer_stop ? $antrian->timer_stop : 'Dalam Proses' }}</td>
                <td>{{ $antrian->order->employee->name }}</td>
                <td>
                    @php
                        $operatorId = explode(',', $antrian->operator_id);
                        foreach ($operatorId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($operatorId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $finisherId = explode(',', $antrian->finisher_id);
                        foreach ($finisherId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($finisherId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $qcId = explode(',', $antrian->qc_id);
                        foreach ($qcId as $item) {
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($qcId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                    @endphp
                </td>
                <td class="text-center">
                    @if ($antrian->deadline_status == "1" && $antrian->end_job != null)
                        <span class="badge bg-success">Tepat Waktu</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "2" && $antrian->end_job != null)
                        <span class="badge bg-danger">Terlambat</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job == null)
                        <span class="badge bg-danger">Belum Diantrikan</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job != null)
                        <span class="badge bg-warning">Diproses</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @endif
                </td>
                <td class="text-center"> {{ number_format($antrian->omset, 0, ',', '.'); }} </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">Total Order</td>
                <td class="text-center"> {{ $totalQtyAdvertising }} </td>
                <td colspan="7" class="text-center">Total Omset</td>
                <td class="text-center"> {{ number_format($totalOmsetAdvertising, 0, ',', '.'); }} </td>
            </tr>
        </tfoot>
    </table>
    <hr>

    <h3>Laporan Non Stempel</h3>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <th>#</th>
            <th>Ticket Order</th>
            <th>Sales</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Desainer</th>
            <th>Operator</th>
            <th>Finishing</th>
            <th>Pengawas</th>
            <th>Status Deadline</th>
            <th>Omset</th>
        </thead>
        <tbody>
            @foreach($antrianNonStempel as $antrian)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-center">{{ $antrian->ticket_order }}</td>
                <td>{{ $antrian->sales->sales_name }}</td>
                <td>{{ $antrian->job->job_name }}</td>
                <td class="text-center">{{ $antrian->qty }}</td>
                <td>{{ $antrian->start_job }}</td>
                <td>{{ $antrian->timer_stop ? $antrian->timer_stop : 'Dalam Proses' }}</td>
                <td>{{ $antrian->order->employee->name }}</td>
                <td>
                    @php
                        $operatorId = explode(',', $antrian->operator_id);
                        foreach ($operatorId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($operatorId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $finisherId = explode(',', $antrian->finisher_id);
                        foreach ($finisherId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($finisherId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $qcId = explode(',', $antrian->qc_id);
                        foreach ($qcId as $item) {
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($qcId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                    @endphp
                </td>
                <td class="text-center">
                    @if ($antrian->deadline_status == "1" && $antrian->end_job != null)
                        <span class="badge bg-success">Tepat Waktu</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "2" && $antrian->end_job != null)
                        <span class="badge bg-danger">Terlambat</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job == null)
                        <span class="badge bg-danger">Belum Diantrikan</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job != null)
                        <span class="badge bg-warning">Diproses</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @endif
                </td>
                <td class="text-center"> {{ number_format($antrian->omset, 0, ',', '.'); }} </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">Total Order</td>
                <td class="text-center"> {{ $totalQtyNonStempel }} </td>
                <td colspan="7" class="text-center">Total Omset</td>
                <td class="text-center"> {{ number_format($totalOmsetNonStempel, 0, ',', '.'); }} </td>
            </tr>
        </tfoot>
    </table>
    <hr>
    <h3>Laporan Digital Printing</h3>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <th>#</th>
            <th>Ticket Order</th>
            <th>Sales</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Desainer</th>
            <th>Operator</th>
            <th>Finishing</th>
            <th>Pengawas</th>
            <th>Status Deadline</th>
            <th>Omset</th>
        </thead>
        <tbody>
            @foreach($antrianDigiPrint as $antrian)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-center">{{ $antrian->ticket_order }}</td>
                <td>{{ $antrian->sales->sales_name }}</td>
                <td>{{ $antrian->job->job_name }}</td>
                <td class="text-center">{{ $antrian->qty }}</td>
                <td>{{ $antrian->start_job }}</td>
                <td>{{ $antrian->timer_stop ? $antrian->timer_stop : 'Dalam Proses' }}</td>
                <td>{{ $antrian->order->employee->name }}</td>
                <td>
                    @php
                        $operatorId = explode(',', $antrian->operator_id);
                        foreach ($operatorId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($operatorId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $finisherId = explode(',', $antrian->finisher_id);
                        foreach ($finisherId as $item) {
                            if($item == 'rekanan'){
                                echo '- Rekanan';
                            }
                            else{
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($finisherId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                        }
                    @endphp
                </td>
                <td>
                    @php
                        $qcId = explode(',', $antrian->qc_id);
                        foreach ($qcId as $item) {
                                $antriann = App\Models\Employee::find($item);
                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                if($antriann != null){
                                    if($antriann->id == end($qcId)){
                                        echo '- ' . $antriann->name;
                                    }
                                    else{
                                        echo '- ' . $antriann->name . "<br>";
                                    }
                                }
                            }
                    @endphp
                </td>
                <td class="text-center">
                    @if ($antrian->deadline_status == "1" && $antrian->end_job != null)
                        <span class="badge bg-success">Tepat Waktu</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "2" && $antrian->end_job != null)
                        <span class="badge bg-danger">Terlambat</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job == null)
                        <span class="badge bg-danger">Belum Diantrikan</span>
                    @elseif ($antrian->deadline_status == "0" && $antrian->end_job != null)
                        <span class="badge bg-warning">Diproses</span>
                        <br>
                        <span class="badge bg-dark">{{ $antrian->end_job }}</span>
                    @endif
                </td>
                <td class="text-center"> {{ number_format($antrian->omset, 0, ',', '.'); }} </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">Total Order</td>
                <td class="text-center"> {{ $totalQtyDigiPrint }} </td>
                <td colspan="7" class="text-center">Total Omset</td>
                <td class="text-center"> {{ number_format($totalOmsetDigiPrint, 0, ',', '.'); }} </td>
            </tr>
        </tfoot>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
