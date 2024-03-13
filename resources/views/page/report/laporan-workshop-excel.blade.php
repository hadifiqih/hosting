<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nomer Tiket</th>
            <th>Sales</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Desainer</th>
            <th>Operator</th>
            <th>Finishing</th>
            <th>QC</th>
            <th>Omset</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barangs as $item)
            @php
                $rowspan = 1;
                $currentTicket = $item->ticket_order;
                $countTicket = \App\Models\Barang::where('ticket_order', $item->ticket_order)->count();
            @endphp
            @if ($countTicket > 1 && $currentTicket == $prevTicket)
                <tr>
                    <td rowspan="{{ $countTicket }}">{{ $loop->iteration }}</td>
                    <td rowspan="{{ $countTicket }}">{{ $item->ticket_order }}</td>
                    <td>{{ $item->user->sales->sales_name }}</td>
                    <td>{{ $item->job->job_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->dataKerja->tgl_mulai }}</td>
                    <td>{{ $item->dataKerja->tgl_selesai }}</td>
                    <td>{{ $item->desainer->name }}</td>
                    @php 
                        if($item->dataKerja->operator_id == null){
                            return '<td>OPERATOR KOSONG</td>';
                        }else{
                            //explode string operator
                            $operator = explode(',', $item->dataKerja->operator_id);
                            $namaOperator = [];
                            foreach($operator as $o){
                                if($o == 'r'){
                                    $namaOperator[] = "Rekanan";
                                }else{
                                    $namaOperator[] = \App\Models\Employee::where('id', $o)->first()->name;
                                }
                            }
                            $kumpulanOperator = implode(', ', $namaOperator);
                        }

                        if($item->dataKerja->finishing_id == null){
                            return '<td>FINISHING KOSONG</td>';
                        }else{
                            //explode string operator
                            $finishing = explode(',', $item->dataKerja->finishing_id);
                            $namaFinishing = [];
                            foreach($finishing as $f){
                                if($f == 'r'){
                                    $namaFinishing[] = "Rekanan";
                                }else{
                                    $namaFinishing[] = \App\Models\Employee::where('id', $f)->first()->name;
                                }
                            }
                            $kumpulanFinishing = implode(', ', $namaFinishing);
                        }

                        if($item->dataKerja->qc_id == null){
                            return '<td>QC KOSONG</td>';
                        }else{
                            //explode string operator
                            $qc = explode(',', $item->dataKerja->qc_id);
                            $namaQc = [];
                            foreach($qc as $q){
                                if($q == 'r'){
                                    $namaQc[] = "Rekanan";
                                }else{
                                    $namaQc[] = \App\Models\Employee::where('id', $q)->first()->name;
                                }
                            }
                            $kumpulanQc = implode(', ', $namaQc);
                        }
                    @endphp
                    <td>{{ $kumpulanOperator }}</td>
                    <td>{{ $kumpulanFinishing }}</td>
                    <td>{{ $kumpulanQc }}</td>
                    <td>Rp{{ $item->price * $item->qty }}</td>
                    </tr>
            
                @php $prevTicket = $item->ticket_order; @endphp
            @else
                <tr>
                    <td>{{ $item->iteration }}</td>
                    <td>{{ $item->ticket_order }}</td>
                    <td>{{ $item->user->sales->sales_name }}</td>
                    <td>{{ $item->job->job_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->dataKerja->tgl_mulai }}</td>
                    <td>{{ $item->dataKerja->tgl_selesai }}</td>
                    <td>{{ $item->desainer->name }}</td>
                    @php 
                        if($item->dataKerja->operator_id == null){
                            return '<td">OPERATOR KOSONG</td>';
                        }else{
                            //explode string operator
                            $operator = explode(',', $item->dataKerja->operator_id);
                            $namaOperator = [];
                            foreach($operator as $o){
                                if($o == 'r'){
                                    $namaOperator[] = "Rekanan";
                                }else{
                                    $namaOperator[] = \App\Models\Employee::where('id', $o)->first()->name;
                                }
                            }
                            $kumpulanOperator = implode(', ', $namaOperator);
                        }

                        if($item->dataKerja->finishing_id == null){
                            return '<td">FINISHING KOSONG</td>';
                        }else{
                            //explode string operator
                            $finishing = explode(',', $item->dataKerja->finishing_id);
                            $namaFinishing = [];
                            foreach($finishing as $f){
                                if($f == 'r'){
                                    $namaFinishing[] = "Rekanan";
                                }else{
                                    $namaFinishing[] = \App\Models\Employee::where('id', $f)->first()->name;
                                }
                            }
                            $kumpulanFinishing = implode(', ', $namaFinishing);
                        }

                        if($item->dataKerja->qc_id == null){
                            return '<td">QC KOSONG</td>';
                        }else{
                            //explode string operator
                            $qc = explode(',', $item->dataKerja->qc_id);
                            $namaQc = [];
                            foreach($qc as $q){
                                if($q == 'r'){
                                    $namaQc[] = "Rekanan";
                                }else{
                                    $namaQc[] = \App\Models\Employee::where('id', $q)->first()->name;
                                }
                            }
                            $kumpulanQc = implode(', ', $namaQc);
                        }
                    @endphp
                    <td>{{ $kumpulanOperator }}</td>
                    <td>{{ $kumpulanFinishing }}</td>
                    <td>{{ $kumpulanQc }}</td>
                    <td>Rp{{ $item->price * $item->qty }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>