@extends('layouts.app')

@section('title', 'Daftar Produk | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Daftar Produk')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
    {{ session('error') }}
</div>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Produk</h3>
                    <a href="{{ route('pos.createProduct') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Produk</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableProduct" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Kulak</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
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
@includeIf('page.kasir.modal.ubah-produk')
@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
<script>
    function modalTambahProduk() {
        $('#tambahProduk').modal('show');
    }

    function hapusProduk(id) {
        // Sweet Alert 2
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data Produk yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Terhapus!',
                    'Data Produk berhasil dihapus.',
                    'success'
                )

                // Ajax Delete
                $.ajax({
                    url: "/pos/manage-product/delete/" + id,
                    type: "GET",
                    success: function(response) {
                        $('#tableProduct').DataTable().ajax.reload();
                    }
                });

            }
        });
    }

    $(document).ready(function() {
        $('.maskMoney').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        $('#tableProduct').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pos.manageProductJson') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'kode_produk', name: 'kode_produk'},
                {data: 'nama_produk', name: 'nama_produk'},
                {data: 'harga_kulak', name: 'harga_kulak'},
                {data: 'harga_jual', name: 'harga_jual'},
                {data: 'stok_bahan', name: 'stok_bahan'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        $('#formUbahProduk').on('submit', function(e) {
            e.preventDefault();
            var token = $("#formUbahProduk input[name='_token']").val();
            var id = $('#formUbahProduk #id').val();
            var kode_produk = $('#formUbahProduk #kode_produk').val();
            var nama_produk = $('#formUbahProduk #nama_produk').val();
            var harga_kulak = $('#formUbahProduk #harga_kulak').val();
            var harga_jual = $('#formUbahProduk #harga_jual').val();

            $.ajax({
                url: "/pos/manage-product/update/" + id,
                type: "PUT",
                data: {
                    _token: token,
                    _method: 'PUT',
                    kode_produk: kode_produk,
                    nama_produk: nama_produk,
                    harga_kulak: harga_kulak,
                    harga_jual: harga_jual,
                },
                success: function(response) {
                    $('#ubahProduk').modal('hide');
                    $('#formUbahProduk').trigger('reset');
                    $('#tableProduct').DataTable().ajax.reload();
                }
            });
        });
    });
</script>
@endsection