@extends('layouts.app')

@section('title', 'Iklan Selesai | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Iklan')

@section('breadcrumb', 'Iklan Selesai')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Iklan Selesai</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm justify-content-end pr-2" style="width: 150px;">
                            <a href="{{ route('iklan.create') }}" class="btn btn-primary btn-sm">Tambah Iklan</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableIklanSelesai" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Iklan</th>
                                <th>Marol</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Nama Sales</th>
                                <th>Platform</th>
                                <th>Biaya Iklan</th>
                                <th>Status Iklan</th>
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
        //delete iklan
        function deleteDataIklan(id) {
        @if(Auth::user()->role_id == 20)
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data Iklan ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "iklan/" + id,
                            type: "POST", 
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                $('#tableDataIklan').DataTable().ajax.reload();
                                Swal.fire(
                                    'Terhapus!',
                                    'Data Iklan berhasil dihapus.',
                                    'success'
                                )
                            }
                        });
                    }
                });
            @else
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hanya Supervisor yang dapat menghapus data!',
                });
            @endif
            }

        //datatable iklan
        $(document).ready(function() {
            $('#tableIklanSelesai').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('iklan.selesaiJson') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nomor_iklan', name: 'nomor_iklan'},
                    { data: 'marol', name: 'marol' },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai' },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai' },
                    { data: 'kategori', name: 'kategori'},
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'nama_sales', name: 'nama_sales' },
                    { data: 'platform', name: 'platform' },
                    { data: 'biaya_iklan', name: 'biaya_iklan' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ]
            });

            //jika ada pesan sukses
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}'
                });
            @elseif(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}'
                });
            @endif
        });

    </script>
@endsection