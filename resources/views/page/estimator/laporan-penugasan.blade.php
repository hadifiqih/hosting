@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Laporan Penugasan')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Laporan Penugasan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableSpk" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Jumlah SPK</th>
                                    <th>Nama Pekerja</th>
                                    <th>Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Unduh</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#tableSpk').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                    url: "{{ route('estimator.laporanPenugasanJson') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'ticket_order', name: 'ticket_order'}
                    {data: 'nama_produk', name: 'nama_produk'},
                    {data: 'qty', name: 'qty'},
                    {data: 'jumlah_spk', name: 'jumlah_spk'},
                    {data: 'nama_pekerja', name: 'nama_pekerja'},
                    {data: 'harga', name: 'harga'},
                ],
        });
    });
</script>
@endsection