@extends('layouts.app')

@section('title', 'Detail Project')

@section('breadcrumb', 'Detail Project')

@section('page', 'Antrian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Status Pesanan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <p class="text-danger"><i class="fas fa-clipboard"></i> <span class="ml-2">Sedang Diproses</span></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Data Pelanggan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="nama">Nama Pelanggan </label>
                        <p>{{ $antrian->customer->nama }} <span class="badge bg-danger">Repeat Order</span></p>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="nama">Telepon</label>
                        <p>{{ $antrian->customer->telepon }}</p>
                    </div>
                </div>
                <div class="col-3">
                    <label for="alamat">Sumber Pelanggan</label>
                    <p>{{ $antrian->customer->infoPelanggan }}</p>
                </div>
                <div class="col-3">
                    <label for="alamat">Instansi </label>
                    <p>{{ $antrian->customer->instansi }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="alamat">Alamat</label>
                    <p>{{ $antrian->customer->alamat }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Informasi Pembayaran</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                        <div class="row p-1 text-sm" style="background-color: lightgray;">
                            <div class="col-1">
                                <p>No</p>
                            </div>
                            <div class="col-6">
                                <p>Nama Produk</p>
                            </div>
                            <div class="col-2">
                                <p class="text-right">Harga</p>
                            </div>
                            <div class="col-1">
                                <p class="text-right">Jumlah</p>
                            </div>
                            <div class="col-2">
                                <p class="text-right">Total</p>
                            </div>
                        </div>
                        <div class="row m-1 mt-1">
                            <div class="col-1">
                                1
                            </div>
                            <div class="col-6">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            </div>
                            <div class="col-2">
                                <p class="text-danger text-right">Rp. 100.000</p>
                            </div>
                            <div class="col-1">
                                <p class="text-success text-right">1</p>
                            </div>
                            <div class="col-2">
                                <p class="text-success text-right">Rp. 100.000</p>
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-1">
                                2
                            </div>
                            <div class="col-6">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            </div>
                            <div class="col-2">
                                <p class="text-danger text-right">Rp. 100.000</p>
                            </div>
                            <div class="col-1">
                                <p class="text-success text-right">1</p>
                            </div>
                            <div class="col-2">
                                <p class="text-success text-right">Rp. 100.000</p>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right">Total Pesanan</p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">|</p>
                        </div>
                        <div class="col-2">
                            <p class="text-success text-right">Rp. 100.000</p>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right">Ongkos Kirim</p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">|</p>
                        </div>
                        <div class="col-2">
                            <p class="text-success text-right">Rp. 10.000</p>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right">Biaya Pasang</p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">|</p>
                        </div>
                        <div class="col-2">
                            <p class="text-success text-right">Rp. 400.000</p>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right">Biaya Packing</p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">|</p>
                        </div>
                        <div class="col-2">
                            <p class="text-success text-right">Rp. 400.000</p>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right">Total Penghasilan</p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">|</p>
                        </div>
                        <div class="col-2">
                            <h3 class="text-success text-right font-weight-bold">Rp. 400.000</h3>
                        </div>
                    </div>
                </div>
            <div class="row p-2">
                <div class="card p-2">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="nama">Catatan / Spesifikasi</label>
                            <p>{{ $antrian->note }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection