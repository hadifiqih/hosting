@extends('layouts.app')

@section('title', 'Data Iklan | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Iklan')

@section('breadcrumb', 'Data Iklan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Data Iklan</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm justify-content-end pr-2" style="width: 150px;">
                            <a href="{{ route('iklan.create') }}" class="btn btn-primary btn-sm">Tambah Iklan</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableDataIklan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Marol</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Nama Produk</th>
                                <th>Nama Sales</th>
                                <th>Platform</th>
                                <th>Biaya Iklan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

@section('script')
    <script>
        //datatable iklan
        $(document).ready(function() {
            $('#tableDataIklan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getTableIklan') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'marol', name: 'marol' },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai' },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai' },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'nama_sales', name: 'nama_sales' },
                    { data: 'platform', name: 'platform' },
                    { data: 'biaya_iklan', name: 'biaya_iklan' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection