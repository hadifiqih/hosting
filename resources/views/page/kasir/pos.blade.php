<!-- resources/views/cashier/index.blade.php -->

@extends('layouts.app')

@section('title', 'POS Kasir | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Tambah Transaksi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Kasir POS Bahan') }}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="product">{{ __('Nama Produk') }}</label>
                            <input type="text" class="form-control" id="product" placeholder="Masukkan nama produk">
                        </div>
                        <div class="form-group">
                            <label for="price">{{ __('Harga') }}</label>
                            <input type="number" class="form-control" id="price" placeholder="Masukkan harga">
                        </div>
                        <div class="form-group">
                            <label for="quantity">{{ __('Banyak (Qty)') }}</label>
                            <input type="number" class="form-control" id="quantity" placeholder="Masukkan qty">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Tambah Keranjang') }}</button>
                    </form>

                    <hr>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Nama Produk') }}</th>
                                <th>{{ __('Harga') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populate with cart items from the controller -->
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end">
                        <h4>{{ __('Total: Rp ') }}<span id="total">0</span></h4>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success btn-sm">{{ __('Checkout') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection