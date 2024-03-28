@extends('layouts.app')

@section('title', 'Antrian Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('content')
<style>
    .badge {
        font-size: 14px;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ session('error') }}
    </div>
@endif

<div class="container">
    <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="custom-content-below-proses-tab" data-toggle="pill" href="#custom-content-below-proses" role="tab" aria-controls="custom-content-below-proses" aria-selected="true">Proses Desain</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-selesai-tab" data-toggle="pill" href="#custom-content-below-selesai" role="tab" aria-controls="custom-content-below-selesai" aria-selected="false">Selesai Desain</a>
        </li>
    </ul>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Antrian Penugasan Desain</h5>
        </div>
        <div class="card-body">
            <div class="tab-content table-responsive" id="custom-content-below-tabContent">
                <div class="tab-pane fade show active" id="custom-content-below-proses" role="tabpanel" aria-labelledby="custom-content-below-proses-tab">
                        <table class="table table-bordered table-hover" id="tableAntrianDesain">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sales</th>
                                    <th>Judul Desain</th>
                                    <th>Jenis Produk</th>
                                    <th>Keterangan</th>
                                    <th>Prioritas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                </div>
                <div class="tab-pane fade" id="custom-content-below-selesai" role="tabpanel" aria-labelledby="custom-content-below-selesai-tab">
                    <table class="table table-bordered table-hover" id="tableSelesaiDesain">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sales</th>
                                <th>Judul Desain</th>
                                <th>Jenis Produk</th>
                                <th>File</th>
                                <th>Keterangan</th>
                                <th>Prioritas</th>
                                <th>Desainer</th>
                                <th>Mulai Penugasan</th>
                                <th>Diselesaikan</th>
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
</div>
@endsection

@section('script')
<script>
    function deleteData(id) {
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
        
        //Swal.fire confirm delete
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/design/hapus-desain') }}" + '/' + id,
                    type: "POST",
                    data: {
                        '_method': 'DELETE',
                        '_token': csrf_token
                    },
                    success: function(data) {
                        $('#tableAntrianDesain').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                    },
                    error: function() {
                        Swal.fire(
                            'Gagal!',
                            'Data gagal dihapus.',
                            'error'
                        )
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $('#tableAntrianDesain').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('design.indexPenugasanDatatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'sales', name: 'sales'},
                { data: 'judul', name: 'judul' },
                { data: 'job', name: 'job' },
                { data: 'note', name: 'note' },
                { data: 'prioritas', name: 'prioritas' },
                { data: 'action', name: 'action' }
            ]
        });

        $('#tableSelesaiDesain').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('design.indexPenugasanSelesaiDatatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'sales', name: 'sales'},
                { data: 'judul', name: 'judul' },
                { data: 'job', name: 'job' },
                { data: 'ref_desain', name: 'ref_desain'},
                { data: 'note', name: 'note' },
                { data: 'prioritas', name: 'prioritas' },
                { data: 'desainer', name: 'desainer' },
                { data: 'mulai_penugasan', name: 'mulai_penugasan' },
                { data: 'diselesaikan', name: 'diselesaikan' },
                { data: 'action', name: 'action' }
            ]
        });
    });
</script>
@endsection