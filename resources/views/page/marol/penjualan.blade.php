@extends('layouts.app')

@section('title', 'Laporan Penjualan dari Iklan | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Laporan Iklan')

@section('breadcrumb', 'Laporan Penjualan dari Iklan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-sm-6">
                <div class="info-box shadow">
                <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                     <div class="info-box-content">
                    <span class="info-box-text">Total Omset</span>
                    <span class="info-box-number"><h3 class="text-success"><strong id="omset">Rp {{ number_format($omset, 0, ',', '.') }}</strong></h3></span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="info-box shadow">
                <span class="info-box-icon bg-danger"><i class="fas fa-money-bill-wave"></i></span>
                     <div class="info-box-content">
                    <span class="info-box-text">Total Spending Budget</span>
                    <span class="info-box-number"><h3 class="text-danger"><strong id="spendingIklan">Rp {{ number_format($spendingIklan, 0, ',', '.') }}</strong></h3></span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
        </div>
    </div>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Laporan Penjualan dari Iklan</h3>
                </div>
                <div class="card-body table-responsive">
                    <table id="tablePenjualan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode Iklan</th>
                                <th>Sales</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
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
                                $('#tablePenjualan').DataTable().ajax.reload();
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
            $('#tablePenjualan').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('iklan.penjualanJson') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'periode_iklan', name: 'periode_iklan'},
                    { data: 'sales', name: 'sales' },
                    { data: 'kategori', name: 'kategori' },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'omset', name: 'omset' },
                ]
            });

            $('#btnFilter').on('click', function() {
                $('#tablePenjualan').DataTable().ajax.url("{{ route('iklan.penjualanJson') }}?tahun=" + $('#tahun').val() + "&bulan=" + $('#bulan').val()).load();
                $.ajax({
                    url: "{{ route('iklan.totalOmset') }}",
                    type: "GET",
                    data: {
                        "tahun": $('#tahun').val(),
                        "bulan": $('#bulan').val()
                    },
                    success: function(data) {
                        $('#omset').text('Rp ' + data.omset);
                        $('#spendingIklan').text('Rp ' + data.spendingIklan);
                    }
                });
            });
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
    </script>
@endsection