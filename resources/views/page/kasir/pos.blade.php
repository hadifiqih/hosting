<!-- resources/views/cashier/index.blade.php -->

@extends('layouts.app')

@section('title', 'POS Kasir | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Tambah Transaksi')

@section('content')
<style>
    .select2-selection {
        height: 38px !important;
    }
</style>
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white">
                    {{ __('Kasir POS Bahan') }}
                </div>
            <div class="card-body">
            <form id="formKeranjangItem">
                @csrf
                <div class="form-group mb-3">
                    <label for="product" class="form-label">{{ __('Nama Pelanggan') }}</label>
                    <input type="text" class="form-control" id="nama_pelanggan" placeholder="Contoh : Bambang Sumanto">
                </div>
                <input type="hidden" id="user" name="user_id" value="{{ $user }}">
                <input type="hidden" id="cabang" name="cabang_id" value="{{ $cabang }}">
                <input type="hidden" id="sales" name="sales_id" value="{{ $sales }}">
                <input type="hidden" id="id_produk" name="produk_id">
                <input type="hidden" id="kode_produk" name="kode_produk">
                <input type="hidden" id="keranjang_id" name="keranjang_id" value="{{ $keranjang_id }}">
            </form>
                <button id="btnTambahProduk" onclick="modalPilihProduk()" class="btn btn-primary btn-sm">{{ __('Tambah Produk') }}</button>

            <div class="table-responsive">
                <table id="tableItems" class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Nama Produk') }}</th>
                            <th scope="col">{{ __('Harga') }}</th>
                            <th scope="col">{{ __('Qty') }}</th>
                            <th scope="col">{{ __('Diskon') }}</th>
                            <th scope="col">{{ __('Total') }}</th>
                            <th scope="col">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cash" class="form-label">{{ __('Diterima') }}</label>
                        <input type="number" class="form-control" id="diterima" placeholder="Rp">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cash" class="form-label">{{ __('Metode Pembayaran') }}</label>
                        <select class="form-control" id="metode">
                            <option value="CASH">{{ __('Tunai') }}</option>
                            <option value="BCA">{{ __('Transfer BCA') }}</option>
                            <option value="BNI">{{ __('Transfer BNI') }}</option>
                            <option value="BRI">{{ __('Transfer BRI') }}</option>
                            <option value="Mandiri">{{ __('Transfer Mandiri') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-6 col-sm-5">
                    <h4 class="font-weight-bold bg-dark p-2">{{ __('Total: Rp ') }}<span id="total">0</span></h4>
                </div>
                <div class="col-md-6 col-sm-5 py-2">
                    <h6 class="font-weight-bold align-middle">{{ __('Kembali: Rp ') }}<span id="kembali">0</span></h6>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-success btn-sm">{{ __('Checkout') }}</button>
                        <button class="btn btn-danger btn-sm float-right">{{ __('Simpan Draft') }}</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@includeIf('page.kasir.modal.pilih-produk')
@endsection

@section('script')
<script>
    function modalPilihProduk() {
        $('#pilihProduk').modal('show');
    }

    function pilihProduk(id, kode){
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        $('#pilihProduk').modal('hide');
        tambahKeranjang();
    }

    function tambahKeranjang(){
        $.post("{{ route('pos.tambahKeranjang') }}", $('#formKeranjangItem').serialize())
        .done((response) => {
            $('#tableItems').DataTable().ajax.reload();
        })
        .fail(errors => {
            console.log(errors);
            alert('Gagal menambahkan produk ke keranjang!');
        }); 
    }

    $(document).ready(function() {
        $('#tableItems').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            paging: false,
            info: false,
            ajax: "/pos/keranjang-item/" + $('#keranjang_id').val(),
            columns: [
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'harga', name: 'harga' },
                { data: 'qty', name: 'qty' },
                { data: 'diskon', name: 'diskon' },
                { data: 'total', name: 'total' },
                { data: 'action', name: 'action' }
            ]
        });

        $('#tableProduct').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('pos.pilihProduk') }}",
            columns: [
                {data: 'kode_produk', name: 'kode_produk'},
                {data: 'nama_produk', name: 'nama_produk'},
                {data: 'harga_jual', name: 'harga_jual'},
                {data: 'stok', name: 'stok'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });
    });
</script>

@endsection
