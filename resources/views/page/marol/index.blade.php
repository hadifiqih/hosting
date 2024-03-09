@extends('layouts.app')

@section('title', 'Iklan Berjalan | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Iklan')

@section('breadcrumb', 'Iklan Berjalan')

@section('content')
<div class="container">
<div class="row mb-3">
    {{-- Filter Jenis Pekerjaan --}}
    <div class="col-md-4">
        <label for="tahun">Tahun</label>
            <select id="tahun" class="form-control select2" name="tahun" id="tahun">
                <option value="">Semua</option>
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
    </div>
    {{-- Tempat Pengerjaan --}}
    <div class="col-md-4">
        <label for="bulan">Bulan</label>
        <select id="bulan" class="form-control select2" name="bulan" id="bulan">
            <option value="">Semua</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
    </div>
    {{-- Button Filter --}}
    <div class="col-md-4 d-flex align-items-end">
        <button type="button" class="btn btn-primary" id="btnFilter">Filter</button>
    </div>
</div>

<ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="custom-content-below-berjalan-tab" data-toggle="pill" href="#custom-content-below-berjalan" role="tab" aria-controls="custom-content-below-berjalan" aria-selected="true">Berjalan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-content-below-selesai-tab" data-toggle="pill" href="#custom-content-below-selesai" role="tab" aria-controls="custom-content-below-selesai" aria-selected="false">Selesai</a>
    </li>
</ul>

<div class="tab-content" id="custom-content-below-tabContent">
    <div class="tab-pane fade active show" id="custom-content-below-berjalan" role="tabpanel" aria-labelledby="custom-content-below-berjalan-tab">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Iklan Berjalan</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm justify-content-end pr-2" style="width: 150px;">
                                <a href="{{ route('iklan.create') }}" class="btn btn-primary btn-sm">Tambah Iklan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tableDataIklan" class="table table-bordered table-striped">
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
        </div>
    </div>
    <div class="tab-pane fade" id="custom-content-below-selesai" role="tabpanel" aria-labelledby="custom-content-below-selesai-tab">
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
                    <div class="card-body table-responsive">
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
        </div>
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
            $('#tableDataIklan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('iklan.indexJson') }}",
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

            $('#btnFilter').on('click', function() {
                $('#tableDataIklan').DataTable().ajax.url("{{ route('iklan.indexJson') }}?tahun=" + $('#tahun').val() + "&bulan=" + $('#bulan').val()).load();
                $('#tableIklanSelesai').DataTable().ajax.url("{{ route('iklan.selesaiJson') }}?tahun=" + $('#tahun').val() + "&bulan=" + $('#bulan').val()).load();
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