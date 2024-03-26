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
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Antrian Desain</h5>
            <a href="{{ route('design.tambahDesain') }}" class="btn btn-sm btn-primary float-right">Tambah Desain</a>
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
                            <th>File</th>
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