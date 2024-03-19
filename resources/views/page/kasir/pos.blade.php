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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white">
                    {{ __('Kasir POS Bahan') }}
                </div>
            <div class="card-body">
            <form>
                <div class="form-group mb-3">
                    <label for="product" class="form-label">{{ __('Nama Pelanggan') }}</label>
                    <input type="text" class="form-control" id="nama_pelanggan" placeholder="Contoh : Bambang Sumanto">
                </div>
                <div class="form-group mb-3">
                    <label for="product" class="form-label">{{ __('Nama Produk') }}</label>
                    <select class="form-control select2" id="produk">
                        <option value="">Pilih Produk</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Tambah Keranjang') }}</button>
            </form>

            <div class="table-responsive">
            <table class="table table-striped mt-3">
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
                    {{-- Give Example Content --}}
                    <tr>
                        <td>Produk 1</td>
                        <td>Rp 10.000</td>
                        <td><input class="form-control" type="text" value="5000" style="width: 75px"/></td>
                        <td><input class="form-control" type="text" value="Rp1.000.000" style="width: 120px"/></td>
                        <td>Rp 20.000</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Produk 2</td>
                        <td>Rp 20.000</td>
                        <td><input class="form-control" type="text" value="2" style="width: 75px"/></td>
                        <td><input class="form-control" type="text" value="Rp10.000.000" style="width: 120px"/></td>
                        <td>Rp 40.000</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
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
                            <option value="tunai">{{ __('Tunai') }}</option>
                            <option value="debit">{{ __('Debit') }}</option>
                            <option value="kredit">{{ __('Kredit') }}</option>
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

@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('#produk').select2({
            placeholder: "Pilih Produk",
            allowClear: true,
            ajax : {
                url: "{{ route('pos.getProductName') }}",
                type: "GET",
                dataType: "json",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama_produk,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>

@endsection
