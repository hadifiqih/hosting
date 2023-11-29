@extends('layouts.app')

@section('title', 'Detail Project')

@section('breadcrumb', 'Detail Project')

@section('page', 'Antrian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Status Pesanan </strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                @php
                    $batas = $antrian->end_job;
                    $batas = date_create($batas);
                @endphp
                <p class="text-primary"><i class="fas fa-circle"></i> <span class="ml-2">Sedang Diproses</span></p>
            </div>
            <div class="row ml-1">
                <p class="text-danger"><i class="fas fa-circle"></i> <span class="ml-2">Deadline Pengerjaan : <strong>{{ date_format($batas, 'd F Y - H:i') }}</strong></span></p>
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
                <div class="col-md">
                    <div class="form-group">
                        <label for="nama">Nama Pelanggan </label>
                        <p>{{ $antrian->customer->nama }} <span class="badge bg-danger">Repeat Order</span></p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="nama">Telepon</label>
                        <p>{{ $antrian->customer->telepon }}</p>
                    </div>
                </div>
                <div class="col-md">
                    <label for="alamat">Sumber Pelanggan</label>
                    <p>{{ $antrian->customer->infoPelanggan }}</p>
                </div>
                <div class="col-md">
                    <label for="alamat">Instansi</label>
                    <p>{{ $antrian->customer->instansi }}</p>
                </div>
                <div class="col-md">
                    <label for="iklan">Status Iklan</label>
                    <p>Facebook / -</p>
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
            <h6><strong><i class="fas fa-circle"></i> <span class="ml-2">Ticket Order - </span></strong>{{ $antrian->ticket_order }}</h6>
            <table id="tableItems" class="table table-bordered mt-3">
                <thead class="thead-dark text-light">
                    <tr>
                        <th class="text-left">No</th>
                        <th>Nama Produk</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</strong></td>
                        <td class="text-danger text-right">Rp. 100.000</td>
                        <td class="text-success text-right">1</td>
                        <td class="text-success text-right">Rp. 100.000</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><strong>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</strong></td>
                        <td class="text-danger text-right">Rp. 100.000</td>
                        <td class="text-success text-right">1</td>
                        <td class="text-success text-right">Rp. 100.000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">Ongkos Kirim</td>
                        <td colspan="2" class="text-success text-right">Rp. 10.000</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Biaya Pasang</td>
                        <td colspan="2" class="text-success text-right">Rp. 400.000</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Biaya Packing</td>
                        <td colspan="2" class="text-success text-right">Rp. 400.000</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Penghasilan</strong></td>
                        <td colspan="2" class="text-success text-right font-weight-bold">Rp. 400.000</td>
                    </tr>
                </tfoot>
            </table>            
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
            <h3 class="card-title"><i class="fas fa-folder-open mr-2"></i> <strong>File Cetak & Pendukung</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-2 mt-2">
                            <div class="bg-dark text-center rounded-lg py-2 text-sm">PDF</div>
                        </div>
                        <div class="col-8 my-auto">
                            <a href="" class="font-weight-bold my-0">Nama File</a>
                            <p class="text-muted">Kamis, 12 November 2023</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
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
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Penugasan Pekerjaan</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <div class="col-3">
                    <h5><strong>Operator</strong></h5>
                    <p class="text-dark"><i class="fas fa-circle"></i> Andini Rhaman Naerman</p>
                </div>
                <div class="col-3">
                    <h5><strong>Desain</strong></h5>
                    <p class="text-dark"><i class="fas fa-circle"></i> Andini Rhaman Naerman</p>
                </div>
                <div class="col-3">
                    <h5><strong>Finishing</strong></h5>
                    <p class="text-dark"><i class="fas fa-circle"></i> Andini Rhaman Naerman</p>
                </div>
                <div class="col-3">
                    <h5><strong>Quality Control</strong></h5>
                    <p class="text-dark"><i class="fas fa-circle"></i> Andini Rhaman Naerman</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-money-check mr-2"></i> <strong>Biaya Produksi</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md table-responsive">
                    <h5><strong>Biaya Bahan</strong></h5>
                <button class="btn btn-primary btn-sm m-0"><i class="fas fa-plus-circle"></i> Tambah</button>
                <table class="table table-responsive table-bordered mt-3">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Bahan</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Sticker Ritrama</td>
                            <td>100.000</td>
                            <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Sticker Ritrama</td>
                            <td>100.000</td>
                            <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>

                <h5><strong>Biaya Lainnya</strong></h5>
                <table class="table table-responsive table-bordered table-hover mt-3" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Biaya Sales (3%)</th>
                            <th>Biaya Desain (2%)</th>
                            <th>Biaya Penanggung Jawab (3%)</th>
                            <th>Biaya Pekerjaan (5%)</th>
                            <th>BPJS (2,5%)</th>
                            <th>Biaya Transportasi (1%)</th>
                            <th>Biaya Overhead / Lainnya (2,5%)</th>
                            <th>Biaya Alat & Listrik (3%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>10.000</th>
                            <th>120.000</th>
                            <th>123</th>
                            <th>10.000</th>
                            <th>10.000</th>
                            <th>10.000</th>
                            <th>10.000</th>
                            <th>10.000</th>
                        </tr>
                    </tbody>
                </table>
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

            $('#tableItems').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: kategori == 'Semua' ? "{{ route('antrian.indexSelesai') }}" : "{{ route('antrian.indexSelesai') }}?kategori=" + kategori,
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'nama_produk', name: 'nama_produk'},
                    {data: 'harga', name: 'harga'},
                    {data: 'qty', name: 'qty'},
                    {data: 'total', name: 'total'},
                ],
            });
        });
    </script>
@endsection
