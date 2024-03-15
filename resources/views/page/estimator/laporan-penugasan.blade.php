@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Laporan Penugasan')

@section('content')
<link href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.css" rel="stylesheet">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Laporan SPK Bulan {{ date('F - Y') }}</h3>
                    <a class="btn btn-sm btn-success float-right" href="{{ route('estimator.laporanWorkshopExcel') }}"><i class="fas fa-file-excel"></i> Unduh Excel</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
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
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.dataTables.js"></script>
<script>
    //DATA TABLE
    $(document).ready(function() {
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('estimator.laporanPenugasanJson') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_order', name: 'ticket_order'},
                {data: 'sales', name: 'sales'},
                {data: 'nama_produk', name: 'nama_produk'},
                {data: 'qty', name: 'qty'},
                {data: 'tgl_mulai', name: 'tgl_mulai'},
                {data: 'tgl_selesai', name: 'tgl_selesai'},
                {data: 'desainer', name: 'desainer'},
                {data: 'operator', name: 'operator'},
                {data: 'finishing', name: 'finishing'},
                {data: 'qc', name: 'qc'},
                {data: 'omset', name: 'omset'},
            ],
        });
    });
</script>
@endsection