@extends('layouts.app')

@section('title', 'Laporan Penjualan dari Iklan | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Laporan Iklan')

@section('breadcrumb', 'Laporan Penjualan dari Iklan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                  <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">Total Omset</span>
                    <span class="info-box-number"><h3 class="text-success"><strong>Rp {{ number_format($omset, 0, ',', '.') }}</strong></h3></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Laporan Penjualan dari Iklan</h3>
                </div>
                <div class="card-body">
                    <table id="tablePenjualan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Iklan</th>
                                <th>Tanggal Order</th>
                                <th>Marol</th>
                                <th>Sales</th>
                                <th>Nama Produk</th>
                                <th>Sumber Pelanggan</th>
                                <th>Omset</th>
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
                ajax: "{{ route('iklan.penjualanJson') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'nomor_iklan', name: 'nomor_iklan' },
                    { data: 'tanggal_order', name: 'tanggal_order' },
                    { data: 'marol', name: 'marol' },
                    { data: 'sales', name: 'sales' },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'sumber_pelanggan', name: 'sumber_pelanggan' },
                    { data: 'omset', name: 'omset' },
                ]
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