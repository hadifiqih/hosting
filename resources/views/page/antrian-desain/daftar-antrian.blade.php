@extends('layouts.app')

@section('title', 'Antrian Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Antrian Desain</h5>
            <button class="btn btn-sm btn-primary float-right">Tambah Desain</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tableAntrianDesain">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sales</th>
                            <th>Judul Desain</th>
                            <th>Jenis Produk</th>
                            <th>Referensi Desain</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#tableAntrianDesain').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('design.indexDatatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'sales', name: 'sales'},
                { data: 'judul', name: 'judul' },
                { data: 'job', name: 'job' },
                { data: 'ref_desain', name: 'ref_desain' },
                { data: 'note', name: 'note' },
                { data: 'status', name: 'status' },
                { data: 'prioritas', name: 'prioritas' },
                { data: 'action', name: 'action' }
            ]
        });
    });
</script>
@endsection