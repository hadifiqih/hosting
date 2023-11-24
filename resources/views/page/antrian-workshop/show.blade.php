@extends('layouts.app')

@section('title', 'Detail Project')

@section('breadcrumb', 'Detail Project')

@section('page', 'Antrian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Status Pesanan</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <p class="text-danger"><i class="fas fa-circle"></i> <span class="ml-2">Sedang Diproses</span></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user mr-2"></i> <strong>Data Pelanggan</strong></h3>
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
            <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i> <strong>Informasi Pembayaran</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="row bg-dark p-3 mb-3 rounded-lg">
                        <div class="col-1">
                            <p class="text-left">No</p>
                        </div>
                        <div class="col-6">
                            <p>Nama Produk</p>
                        </div>
                        <div class="col-2">
                            <p class="text-right">Harga</p>
                        </div>
                        <div class="col-1">
                            <p class="text-right">Qty</p>
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
                            <p class="text-success text-right">---</p>
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
                            <p class="text-success text-right">---</p>
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
                            <p class="text-success text-right">---</p>
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
                            <p class="text-success text-right">---</p>
                        </div>
                        <div class="col-2">
                            <p class="text-success text-right">Rp. 400.000</p>
                        </div>
                    </div>
                    <div class="row pr-2">
                        <div class="col-9">
                            <p class="text-right"><strong> Total Penghasilan </strong></p>
                        </div>
                        <div class="col-1">
                            <p class="text-success text-right">---</p>
                        </div>
                        <div class="col-2">
                            <h4 class="text-success text-right font-weight-bold">Rp. 400.000</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Catatan / Spesifikasi</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <p class="text-dark keterangan">{{ $antrian->note }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Gambar ACC Desain</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">File Desain & Pendukung</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-2 mt-2">
                    <div class="bg-dark text-center rounded-lg py-2 text-sm">PDF</div>
                </div>
                <div class="col-8 my-auto">
                    <a href="" class="font-weight-bold my-0">Nama File</a>
                    <p class="text-muted">Kamis, 12 November 2023</p>
                </div>
            </div>
            <div class="row">
                <div class="col-2 mt-2">
                    <div class="bg-dark text-center rounded-lg py-2 text-sm">PDF</div>
                </div>
                <div class="col-8 my-auto">
                    <a href="#" class="font-weight-bold my-0">Nama File</a>
                    <p class="text-muted">Kamis, 12 November 2023</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.keterangan').each(function() {
                var text = $(this).text();
                $(this).html(text.replace(/\n/g, '<br/>'));
            });
        });
    </script>
@endsection