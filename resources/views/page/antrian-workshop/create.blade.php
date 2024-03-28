@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Tambah Antrian')

@section('breadcrumb', 'Tambah Antrian')

@section('content')
<div class="container-fluid">
    <form action="{{ route('antrian.simpanAntrian') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Pelanggan</h2>
                </div>
                <div class="card-body">
                    {{-- Tambah Pelanggan Baru --}}
                    <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#modalTambahPelanggan">
                        <i class="fas fa-user"></i> Tambah Pelanggan
                    </button>

                    <div class="form-group">
                        <label for="nama">Nama Pelanggan <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="customer_id" name="customer_id" style="width: 100%">
                            <option value="" selected>Pilih Pelanggan</option>
                        </select>
                    </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Informasi Order</h2>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="tambahProduk()"><i class="fas fa-plus"></i> Tambah Produk</button>
                        <table id="tableProduk" class="table table-responsive table-bordered" style="width: 100%">
                            <thead>
                                <th>#</th>
                                <th>Kategori Produk</th>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga (satuan)</th>
                                <th>Harga Total</th>
                                <th>Keterangan Spesifikasi</th>
                                <th>Acc Desain</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-center">Total</th>
                                    <th colspan="4" class="text-danger maskRupiah"><span id="subtotal"></span></th>
                                </tr>
                            </tfoot>
                        </table>

                            <div class="form-group mt-3">
                                <label for="packing">Biaya Packing</label>
                                <input type="text" class="form-control maskRupiah" id="packing" placeholder="Contoh : Rp 100.000" name="biayaPacking" value="{{ old('packing') }}">
                            </div>

                            <div class="form-group">
                                <label for="pasang">Biaya Pasang</label>
                                <input type="text" class="form-control maskRupiah" id="pasang" placeholder="Contoh : Rp 100.000" name="biayaPasang" value="{{ old('pasang') }}">
                            </div>

                            <div class="form-group">
                                <label for="diskon">Diskon / Potongan Harga</label>
                                <input type="text" class="form-control maskRupiah" id="diskon" placeholder="Contoh : Rp 100.000" name="diskon" value="{{ old('diskon') }}">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox" id="isOngkir" name="isOngkir" value="{{ old('isOngkir') }}">
                                    <label for="isOngkir" class="custom-control-label">Menggunakan Pengiriman ?</label>
                                </div>
                            </div>

                            <div class="form-group divOngkir">
                                <label for="ongkir">Biaya Ongkir</label>
                                <input type="text" class="form-control maskRupiah" id="ongkir" placeholder="Contoh : Rp 100.000" name="ongkir" value="{{ old('ongkir') }}">
                            </div>

                            <div class="form-group divAlamatKirim">
                                <label for="ongkir">Alamat Pengiriman</label>
                                <input type="text" class="form-control" id="alamatKirim" placeholder="Jl. Alamat Lengkap" name="alamatKirim" value="{{ old('alamatKirim') }}">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox" id="alamatSama" name="alamatSama" value="{{ old('alamatSama') }}">
                                    <label for="alamatSama" class="custom-control-label">Alamat seperti pada Data Pelanggan</label>
                                </div>
                                <p class="text-muted font-italic text-sm mb-0 mt-1">*Harap isi dengan alamat lengkap, agar tidak terjadi kesalahan pengiriman.</p>
                                <p class="text-muted font-italic text-sm mt-0">*Contoh alamat lengkap: Jalan Mangga Kecil No.13, RT 09 RW 03, Kelurahan Besi Tua, Kecamatan Sukaraja, Kab. Binjai, Sumatera Utara, 53421.</p>
                            </div>

                            <div class="form-group divEkspedisi">
                                <label for="ekspedisi">Ekspedisi</label>
                                <select name="ekspedisi" id="ekspedisi" class="form-control">
                                    <option value="" selected disabled>Pilih Ekspedisi</option>
                                    @foreach($ekspedisi as $eks)
                                    <option value="{{ $eks->kode_ekspedisi }}">{{ $eks->nama_ekspedisi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group divEksLain">
                                <label for="keterangan">Nama Ekspedisi</label>
                                <input type="text" class="form-control" id="namaEkspedisi" placeholder="Nama Ekspedisi" name="namaEkspedisi" value="{{ old('namaEkspedisi') }}">
                            </div>

                            <div class="form-group divResi">
                                <label for="">No. Resi</label>
                                <input type="text" class="form-control" id="noResi" placeholder="No. Resi" name="noResi" value="{{ old('noResi') }}">
                                <p class="text-muted font-italic text-sm mb-0 mt-1">*Opsional, khusus order dari Marketplace. Hiraukan selain dari marketplace.</p>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <span>Total : </span><h4 class="font-weight-bold text-danger" id="totalAll"></h4>
                                </div>
                                {{-- Hidden input untuk mengambil nilai dari id totalAll --}}
                                <input type="hidden" name="totalAllInput" id="totalAllInput">
                            </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Informasi Pembayaran</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="metodePembayaran">Metode Pembayaran</label>
                        <select name="metodePembayaran" id="metodePembayaran" class="form-control" required>
                            <option value="" selected disabled>Pilih Metode Pembayaran</option>
                            <option value="Cash">Cash</option>
                            <option value="BCA">Transfer BCA</option>
                            <option value="BNI">Transfer BNI</option>
                            <option value="BRI">Transfer BRI</option>
                            <option value="Mandiri">Transfer Mandiri</option>
                            <option value="Shopee">Saldo Shopee</option>
                            <option value="Tokopedia">Saldo Tokopedia</option>
                            <option value="Bukalapak">Saldo Bukalapak</option>
                            <option value="Bayar Waktu Ambil">Bayar Waktu Ambil</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="statusPembayaran">Status Pembayaran</label>
                        <select name="statusPembayaran" id="statusPembayaran" class="form-control" required>
                            <option value="" selected disabled>Pilih Status Pembayaran</option>
                            <option value="2">Lunas</option>
                            <option value="1">DP(Down Payment)</option>
                        </select>
                        <p class="text-muted font-italic text-sm mb-0 mt-1">*Jika order dari marketplace, bisa ditandai sebagai lunas.</p>
                    </div>

                    <div class="form-group">
                        <label for="jumlahPembayaran">Jumlah Pembayaran</label>
                        <input type="text" class="form-control maskRupiah" id="jumlahPembayaran" placeholder="Contoh : Rp 100.000" name="jumlahPembayaran" required>
                        <p class="text-muted font-italic text-sm mb-0 mt-1">*Untuk marketplace, total jumlah pembayaran adalah total keseluruhan penjualan (termasuk biaya admin)</p>
                    </div>

                    {{-- Upload bukti transfer using dropzone --}}
                    <div class="form-group">
                        <label for="paymentImage">Bukti Pembayaran</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="paymentImage" name="paymentImage">
                                <label class="custom-file-label" for="paymentImage">Pilih Gambar</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <span>Sisa Pembayaran : </span><h4 class="font-weight-bold text-danger" id="sisaPembayaran"></h4>
                            <input type="text" name="sisaPembayaranInput" id="sisaPembayaranInput" hidden>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Tombol Submit Antrikan --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-right">
                        {{-- Tombol Submit --}}
                        <div class="d-flex align-items-center">
                            <button id="submitToAntrian" type="submit" class="btn btn-primary">Submit<div id="loader" class="loader" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @includeIf('page.antrian-workshop.modal.modal-tambah-pelanggan')
    @includeIf('page.antrian-workshop.modal.modal-pilih-produk')
    @includeIf('page.antrian-workshop.modal.modal-edit-produk')
</div>
@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
    Dropzone.options.myDropzone = {
        url: "{{ route('simpanAcc') }}",
        paramName: "gambarAcc",
        maxFilesize: 20,
        autoProcessQueue: false,
    };

    $(function () {
        bsCustomFileInput.init();
    });

    
</script>
@endsection
