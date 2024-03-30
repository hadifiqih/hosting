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
                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modalTambahPelanggan">
                        <i class="fas fa-user"></i> Tambah Pelanggan
                    </button>
                </div>
                <div class="card-body">
                    {{-- Tambah Pelanggan Baru --}}
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

    {{-- Button Tambah Produk --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Informasi Order</h2>
                    <button type="button" class="btn btn-sm btn-primary float-right" onclick="tambahProduk()"><i class="fas fa-plus"></i> Tambah Produk</button>
                </div>
                <div class="card-body">
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

    // Tambah Produk
    function tambahProduk() {
        if($('#customer_id').val() == null || $('#customer_id').val() == ""){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Pilih pelanggan terlebih dahulu!',
            });
        }else{
            $('#modalPilihProduk').modal('show');
        }
    }

    function updateTotalBarang(){
        $.ajax({
            url: "/barang/getTotalBarang/" + $('#customer_id').val(),
            method: "GET",
            success: function(data){
                $('#subtotal').html(data.totalBarang);
                $('#totalAll').html(data.totalBarang);
                $('#totalAllInput').val(data.totalBarang);
                $('#sisaPembayaran').html(data.totalBarang);
                $('#sisaPembayaranInput').val(data.totalBarang);
            },
            error: function(xhr, status, error){
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }
        });
    }

    function deleteBarang(id){
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Produk akan dihapus dari antrian ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/barang/" + id,
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE",
                        id: id
                    },
                    success: function(data){
                        $('#tableProduk').DataTable().ajax.reload();

                        // function updateTotalBarang
                        updateTotalBarang();

                        //tampilkan toast sweet alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Produk berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr, status, error){
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        // Masking Rupiah
        $('.maskRupiah').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        // function updateTotalBarang
        updateTotalBarang();

        //fungsi untuk menyembunyikan file acc desain saat kosoongAcc dicentang
        $('#kosongAcc').on('change', function(){
            if($(this).is(':checked')){
                //remove required
                $('#fileAccDesain').removeAttr('required');
                $('#fileAccDesain').attr('disabled', true);
            }else{
                //add required
                $('#fileAccDesain').attr('required', true);
                $('#fileAccDesain').attr('disabled', false);
            }
        });

        // Select2 Pelanggan
        $('#customer_id').select2({
            placeholder: 'Pilih Pelanggan',
            ajax: {
                url: '{{ route('pelanggan.search') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        //nama produk select2
        $('#modalPilihProduk #kategoriProduk').on('change', function(){

        $('#modalPilihProduk #namaProduk').val(null).trigger('change');
        $('#modalPilihProduk #namaProduk').empty();
        $('#modalPilihProduk #namaProduk').append(`<option value="" selected disabled>Pilih Produk</option>`);

        $('#modalPilihProduk #namaProduk').select2({
            placeholder: 'Pilih Produk',
            ajax: {
                url: "{{ route('job.searchByCategory') }}",
                data: function (params) {
                    return {
                        kategoriProduk: $('#kategoriProduk').val(),
                        q: params.term // tambahkan jika ingin mencari berdasarkan keyword
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.job_name
                            };
                        })
                    };
                },
                cache: true
            }
        });
        });

        $('#not_iklan').on('change', function(){
            if($(this).is(':checked')){
                $('#namaProdukIklan').val(null).trigger('change');
                $('#tahunIklan').val('');
                $('#bulanIklan').val('');
                $('#tahunIklan').prop('disabled', true);
                $('#bulanIklan').prop('disabled', true);
                $('#namaProdukIklan').prop('disabled', true);
                $('#bulanIklan').hide();
                $('.divNamaProduk').hide();
            }else{
                $('#namaProdukIklan').val(null).trigger('change');
                $('#tahunIklan').val('');
                $('#bulanIklan').val('');
                $('#tahunIklan').prop('disabled', false);
                $('#bulanIklan').prop('disabled', false);
                $('#namaProdukIklan').prop('disabled', false);
                $('#bulanIklan').show();
                $('.divNamaProduk').show();
            }
        });

        $('#not_iklanEdit').on('change', function(){
            if($(this).is(':checked')){
                $('#namaProdukIklanEdit').val(null).trigger('change');
                $('#tahunIklanEdit').val('');
                $('#bulanIklanEdit').val('');
                $('#tahunIklanEdit').prop('disabled', true);
                $('#bulanIklanEdit').prop('disabled', true);
                $('#namaProdukIklanEdit').prop('disabled', true);
                $('#bulanIklanEdit').hide();
                $('.divNamaProdukEdit').hide();
            }else{
                $('#namaProdukIklanEdit').val(null).trigger('change');
                $('#tahunIklanEdit').val('');
                $('#bulanIklanEdit').val('');
                $('#tahunIklanEdit').prop('disabled', false);
                $('#bulanIklanEdit').prop('disabled', false);
                $('#namaProdukIklanEdit').prop('disabled', false);
                $('#bulanIklanEdit').show();
                $('.divNamaProdukEdit').show();
            }
        });

        $('#not_iklan').on('change', function(){
            if($(this).is(':checked')){
                $('#periode_iklan').val(null).trigger('change');

                $('#periode_iklan').prop('disabled', true);
            }else{
                $('#periode_iklan').prop('disabled', false);
            }
        });

        $('#tahunIklanEdit').on('change', function(){
            $('#bulanIklanEdit').show();
        });

        $('#tahunIklan').on('change', function(){
            $('#bulanIklan').show();
        });

        $('#bulanIklan').on('change', function(){
            $('#modalPilihProduk .divNamaProduk').show();
        });

        $('#bulanIklanEdit').on('change', function(){
            $('#modalEditProduk .divNamaProdukEdit').show();
        });

        $('#namaProdukIklanEdit').select2({
            placeholder: 'Pilih Produk',
            allowClear: true,
            ajax: {
                url: "{{ route('getAllJobs') }}",
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.job_name
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#namaProdukIklan').select2({
            placeholder: 'Pilih Produk',
            allowClear: true,
            ajax: {
                url: "{{ route('getAllJobs') }}",
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.job_name
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#formTambahProduk').on('submit', function(e){
            e.preventDefault();

            var kosongAcc = ($('#kosongAcc').is(':checked')) ? 1 : 0;

            // Inisialisasi File acc_desain
            var acc_desain = ($('#fileAccDesain')[0].files.length > 0) ? $('#fileAccDesain')[0].files[0] : "";

            var dataInput = new FormData();
            dataInput.append('acc_desain', acc_desain);
            dataInput.append('_token', "{{ csrf_token() }}");
            dataInput.append('customer_id', $('#customer_id').val());
            dataInput.append('ticket_order', $('#ticket_order').val());
            dataInput.append('namaProduk', $('#namaProduk').val());
            dataInput.append('kategoriProduk', $('#kategoriProduk').val());
            dataInput.append('qty', $('#qty').val());
            dataInput.append('harga', $('#harga').val());
            dataInput.append('keterangan', $('#keterangan').val());
            dataInput.append('tahunIklan', $('#tahunIklan').val());
            dataInput.append('bulanIklan', $('#bulanIklan').val());
            dataInput.append('namaProdukIklan', $('#namaProdukIklan').val());

            $.ajax({
                url: "{{ route('barang.store') }}",
                method: "POST",
                //data is form dataInput, acc_desain, _token
                data: dataInput,
                contentType: false,
                processData: false,
                cache: false,
                success: function(data){
                    $('#modalPilihProduk').modal('hide');
                    $('#tableProduk').DataTable().ajax.reload();
                    $('#formTambahProduk')[0].reset();

                    // function updateTotalBarang
                    updateTotalBarang();

                    //tampilkan toast sweet alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Produk berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(xhr, status, error){
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            });
        });

        //Tabel Produk
        //DataTables Produk
        $('#tableProduk').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            paging: false,
            searching: false,
            info: false,
            ajax: {
                url: "/barang/show/" + $('#customer_id').val(),
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'kategori', name: 'kategori'},
                {data: 'namaProduk', name: 'namaProduk'},
                {data: 'qty', name: 'qty'},
                {data: 'harga', name: 'harga'},
                {data: 'hargaTotal', name: 'hargaTotal'},
                {data: 'keterangan', name: 'keterangan'},
                {data: 'accdesain', name: 'accdesain'},
                {data: 'action', name: 'action'},
            ],
        });
});
</script>
@endsection
