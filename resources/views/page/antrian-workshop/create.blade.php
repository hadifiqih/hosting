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
                    <div class="form-group">
                        <label for="sumberPelanggan">Status Pelanggan</label>
                        <input type="text" class="form-control" id="statusPelanggan" placeholder="Status Pelanggan" value="" readonly>
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
                                <input type="text" class="form-control maskRupiah" id="packing" placeholder="Contoh : Rp 100.000" name="packing">
                            </div>

                            <div class="form-group">
                                <label for="pasang">Biaya Pasang</label>
                                <input type="text" class="form-control maskRupiah" id="pasang" placeholder="Contoh : Rp 100.000" name="pasang">
                            </div>

                            <div class="form-group">
                                <label for="diskon">Diskon / Potongan Harga</label>
                                <input type="text" class="form-control maskRupiah" id="diskon" placeholder="Contoh : Rp 100.000" name="diskon">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox" id="isOngkir" name="isOngkir">
                                    <label for="isOngkir" class="custom-control-label">Menggunakan Pengiriman ?</label>
                                </div>
                            </div>

                            <div class="form-group divOngkir">
                                <label for="ongkir">Biaya Ongkir</label>
                                <input type="text" class="form-control maskRupiah" id="ongkir" placeholder="Contoh : Rp 100.000" name="ongkir">
                            </div>

                            <div class="form-group divAlamatKirim">
                                <label for="ongkir">Alamat Pengiriman</label>
                                <input type="text" class="form-control" id="alamatKirim" placeholder="Jl. Alamat Lengkap" name="alamatKirim">
                                <p class="text-muted font-italic text-sm mb-0 mt-1">*Harap isi dengan alamat lengkap, agar tidak terjadi kesalahan pengiriman.</p>
                                <p class="text-muted font-italic text-sm mt-0">*Contoh alamat lengkap: Jalan Mangga Kecil No.13, RT 09 RW 03, Kelurahan Besi Tua, Kecamatan Sukaraja, Kab. Binjai, Sumatera Utara, 53421.</p>
                            </div>

                            <div class="form-group divEkspedisi">
                                <label for="ekspedisi">Ekspedisi</label>
                                <select name="ekspedisi" id="ekspedisi" class="form-control">
                                    <option value="" selected disabled>Pilih Ekspedisi</option>
                                    <option value="GOSEND">GO-SEND (Instant)</option>
                                    <option value="JNE">JNE</option>
                                    <option value="J&T">J&T Express</option>
                                    <option value="POS">POS Indonesia</option>
                                    <option value="TIKI">TIKI</option>
                                    <option value="WAHANA">Wahana</option>
                                    <option value="SICEPAT">SiCepat Express</option>
                                    <option value="NINJA">Ninja Express</option>
                                    <option value="LION">Lion Parcel</option>
                                    <option value="IDE">ID Express</option>
                                    <option value="ANTERAJA">AnterAja</option>
                                    <option value="BALI">Bali Prima Travel</option>
                                    <option value="BUS">Bus Terminal</option>
                                    <option value="KERETA">Kereta Api</option>
                                    <option value="LAIN">Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group divEksLain">
                                <label for="keterangan">Nama Ekspedisi</label>
                                <input type="text" class="form-control" id="namaEkspedisi" placeholder="Nama Ekspedisi" name="namaEkspedisi">
                            </div>

                            <div class="form-group divResi">
                                <label for="">No. Resi</label>
                                <input type="text" class="form-control" id="noResi" placeholder="No. Resi" name="noResi">
                                <p class="text-muted font-italic text-sm mb-0 mt-1">*Opsional, khusus order dari Marketplace. Hiraukan selain dari marketplace.</p>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <span>Total : </span><h4 class="font-weight-bold text-danger" id="totalAll"></h4>
                                </div>
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
                            <option value="Bayar Waktu Ambil">Bayar Waktu Ambil</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="statusPembayaran">Status Pembayaran</label>
                        <select name="statusPembayaran" id="statusPembayaran" class="form-control" required>
                            <option value="" selected disabled>Pilih Status Pembayaran</option>
                            <option value="Lunas">Lunas</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jumlahPembayaran">Jumlah Pembayaran</label>
                        <input type="text" class="form-control maskRupiah" id="jumlahPembayaran" placeholder="Contoh : Rp 100.000" name="jumlahPembayaran" required>
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
                        <input type="hidden" name="ticket_order" id="ticket_order" value="{{ $order->ticket_order }}">
                        <input type="hidden" name="sales_id" id="salesID" value="{{ $order->sales_id }}">

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

    function tambahProduk(){
        $('#modalPilihProduk').modal('show');
    }

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

    function updateTotalBarang(){
        $.ajax({
            url: "{{ route('getTotalBarang', $order->ticket_order) }}",
            method: "GET",
            success: function(data){
                $('#subtotal').html(data.totalBarang);
                $('#totalAll').html(data.totalBarang);
                $('#sisaPembayaran').html(data.totalBarang);
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

    $(document).ready(function(){

        $('.divAlamatKirim').hide();
        $('.divOngkir').hide();
        $('.divEkspedisi').hide();
        $('.divEksLain').hide();
        $('.divResi').hide();

        // function provinsi
        $.ajax({
            url: "{{ route('getProvinsi') }}",
            method: "GET",
            success: function(data){
                //foreach provinsi
                $.each(data, function(key, value){
                    $('#provinsi').append(`
                        <option value="${key}">${value}</option>
                    `);
                });
            }
        });

        $.ajax({
            url: "{{ route('getInfoPelanggan') }}",
            method: "GET",
            success: function(data){
                console.log(data);
                //foreach info pelanggan
                $.each(data, function(key, value){
                    $('#infoPelanggan').append(`
                        <option value="${key}">${value}</option>
                    `);
                });
            }
        });

        // function kota
        $('#provinsi').on('change', function(){
            var provinsi = $(this).val();
            $('#groupKota').show();
            $('#kota').empty();
            $('#kota').append(`<option value="" selected disabled>Pilih Kota</option>`);
            $.ajax({
                url: "{{ route('getKota') }}",
                method: "GET",
                delay: 250,
                data: {
                    provinsi: provinsi
                },
                success: function(data){
                    //foreach kota
                    $.each(data, function(key, value){
                        $('#kota').append(`
                            <option value="${key}">${value}</option>
                        `);
                    });
                }
            });
        });

        // function updateTotalBarang
        updateTotalBarang();

        // Mask Money
        $('#harga').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        // Mask Money .maskRupiah
        $('.maskRupiah').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        // saat ada perubahan pada inputan biaya packing, biaya ongkir, biaya pasang, diskon maka totalAll akan berubah
        $('#packing, #ongkir, #pasang, #diskon').on('keyup', function(){
            var packing = $('#packing').val();
            packing = packing.replace(/[^0-9]/g, '');
            packing = parseInt(packing);

            var ongkir = $('#ongkir').val();
            ongkir = ongkir.replace(/[^0-9]/g, '');
            ongkir = parseInt(ongkir);

            var pasang = $('#pasang').val();
            pasang = pasang.replace(/[^0-9]/g, '');
            pasang = parseInt(pasang);

            var diskon = $('#diskon').val();
            diskon = diskon.replace(/[^0-9]/g, '');
            diskon = parseInt(diskon);

            var totalAll = $('#subtotal').text();
            totalAll = totalAll.replace(/[^0-9]/g, '');
            totalAll = parseInt(totalAll);

            if(isNaN(packing)){
                packing = 0;
            }

            if(isNaN(ongkir)){
                ongkir = 0;
            }

            if(isNaN(pasang)){
                pasang = 0;
            }

            if(isNaN(diskon)){
                diskon = 0;
            }

            if(isNaN(totalAll)){
                totalAll = 0;
            }

            var totalAll = totalAll + packing + ongkir + pasang - diskon;
            totalAll = totalAll.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            $('#totalAll').html('Rp ' + totalAll);
            $('#sisaPembayaran').html('Rp ' + totalAll);
        });

        // sisaPembayaran akan berubah saat ada perubahan pada inputan jumlahPembayaran - totalAll
        $('#jumlahPembayaran').on('keyup', function(){
            var jumlahPembayaran = $('#jumlahPembayaran').val();
            jumlahPembayaran = jumlahPembayaran.replace(/[^0-9]/g, '');
            jumlahPembayaran = parseInt(jumlahPembayaran);

            var totalAll = $('#totalAll').text();
            totalAll = totalAll.replace(/[^0-9]/g, '');
            totalAll = parseInt(totalAll);

            if(isNaN(jumlahPembayaran)){
                jumlahPembayaran = 0;
            }

            if(isNaN(totalAll)){
                totalAll = 0;
            }

            var sisaPembayaran = totalAll - jumlahPembayaran;
            if(sisaPembayaran < 0){
                sisaPembayaran = "Melebihi Total Pembayaran";
            }
            sisaPembayaran = sisaPembayaran.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            $('#sisaPembayaran').html('Rp ' + sisaPembayaran);
        });

        //nama produk select2
        $('#modalPilihProduk #kategoriProduk').on('change', function(){

            $('#modalPilihProduk #namaProduk').val(null).trigger('change');
            $('#modalPilihProduk #namaProduk').empty();
            $('#modalPilihProduk #namaProduk').append(`<option value="" selected disabled>Pilih Produk</option>`);

        $('#modalPilihProduk #namaProduk').select2({
            placeholder: 'Pilih Produk',
            ajax: {
                url: "{{ route('getJobsByCategory') }}",
                data: function (params) {
                    var query = {
                        kategoriProduk: $('#kategoriProduk').val()
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
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
                url: "{{ route('barang.showCreate', $order->ticket_order) }}",
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

        // Nama Pelanggan
        $('#namaPelanggan').select2({
            placeholder: 'Pilih Pelanggan',
            ajax: {
                url: "/pelanggan-all/{{ $order->sales_id }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            if(item.instansi == null){
                                return {
                                    text: item.nama,
                                    id: item.id,
                                }
                            }else{
                                return {
                                    text: item.nama + ' - ' + item.telepon,
                                    id: item.id,
                                }
                            }
                        })
                    };
                },
                cache: true
            }
        });

        //onchange nama pelanggan
        $('#namaPelanggan').on('change', function(){
            var id = $(this).val();
            $.ajax({
                url: "/pelanggan/status/" + id,
                method: "GET",
                success: function(data){
                    $('#statusPelanggan').val(data.status);
                }
            });
        });

        $('#formTambahProduk').on('submit', function(e){
            e.preventDefault();

            var kosongAcc = ($('#kosongAcc').is(':checked')) ? 1 : 0;

            // Inisialisasi File acc_desain
            var acc_desain = ($('#fileAccDesain')[0].files.length > 0) ? $('#fileAccDesain')[0].files[0] : "";

            var dataInput = new FormData();
            dataInput.append('acc_desain', acc_desain);
            dataInput.append('_token', "{{ csrf_token() }}" );
            dataInput.append('ticket_order', $('#ticket_order').val());
            dataInput.append('namaProduk', $('#namaProduk').val());
            dataInput.append('kategoriProduk', $('#kategoriProduk').val());
            dataInput.append('qty', $('#qty').val());
            dataInput.append('harga', $('#harga').val());
            dataInput.append('keterangan', $('#keterangan').val());

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

        // ketika isOngkir dicentang maka divAlamatKirim, divOngkir, divEkspedisi akan muncul
        $('#isOngkir').on('change', function(){
            if($(this).is(':checked')){
                //reset value
                $('#alamatKirim').val('');
                $('#ongkir').val('');
                $('#ekspedisi').val('');
                $('#namaEkspedisi').val('');
                $('#noResi').val('');
                
                $('.divAlamatKirim').show();
                $('.divOngkir').show();
                $('.divEkspedisi').show();
                $('.divResi').show();
                //add required
                $('#alamatKirim').attr('required', true);
                $('#ongkir').attr('required', true);
                $('#ekspedisi').attr('required', true);

            }else{
                $('.divAlamatKirim').hide();
                $('.divOngkir').hide();
                $('.divEkspedisi').hide();
                $('.divEksLain').hide();
                $('.divResi').hide();
                //remove required
                $('#alamatKirim').removeAttr('required');
                $('#ongkir').removeAttr('required');
                $('#ekspedisi').removeAttr('required');
            }
        });

        //jika ekspedisi dipilih selain Lainnya maka divEksLain akan hide
        $('#ekspedisi').on('change', function(){
            if($(this).val() != 'LAIN'){
                $('.divEksLain').hide();
                //remove required
                $('#namaEkspedisi').removeAttr('required');
            }else{
                $('.divEksLain').show();
                //add required
                $('#namaEkspedisi').attr('required', true);
            }
        });

        // function simpanPelanggan
        $('#pelanggan-form').on('submit', function(e){
            e.preventDefault();

            $.ajax({
                url: "{{ route('pelanggan.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    salesID: $('#salesID').val(),
                    nama: $('#modalNama').val(),
                    telepon: $('#modalTelepon').val(),
                    alamat: $('#modalAlamat').val(),
                    instansi: $('#modalInstansi').val(),
                    infoPelanggan: $('#infoPelanggan').val(),
                    provinsi: $('#provinsi').val(),
                    kota: $('#kota').val(),
                },
                success: function(data){
                    $('#modalTambahPelanggan').modal('hide');
                    $('#namaPelanggan').append(`<option value="${data.id}" selected>${data.nama}</option>`);
                    $('#namaPelanggan').val(data.id).trigger('change');
                    $('#namaPelanggan').select2({
                        placeholder: 'Pilih Pelanggan',
                        ajax: {
                            url: "/pelanggan-all/{{ $order->sales_id }}",
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results:  $.map(data, function (item) {
                                        if(item.instansi == null){
                                            return {
                                                text: item.nama + ' - ' + item.telepon,
                                                id: item.id,
                                            }
                                        }else{
                                            return {
                                                text: item.nama + ' - ' + item.telepon, 
                                                id: item.id,
                                            }
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });

                    //tampilkan toast sweet alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pelanggan berhasil ditambahkan',
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
    });
</script>
@endsection
