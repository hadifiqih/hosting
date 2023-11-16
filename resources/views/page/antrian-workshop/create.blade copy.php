@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Tambah Antrian')

@section('breadcrumb', 'Tambah Antrian')

@section('content')
<div class="container-fluid">
    <form action="{{ route('antrian.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Data Pelanggan</h2>
                </div>
                <div class="card-body">
                    {{-- Tambah Pelanggan Baru --}}
                    <button type="button" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
                        Tambah Pelanggan Baru
                    </button>

                    <div class="form-group">
                        <label for="nama">Nama Pelanggan <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="nama" name="nama" style="width: 100%">

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
                    <h2 class="card-title">Informasi Produk</h2>
                </div>
                <div class="card-body">
                    <button class="btn btn-sm btn-outline-primary" onclick="modalPilihProuk()">Pilih Produk</button>d
                </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Pembayaran</h2>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="statusPembayaran">Status Pembayaran <span class="text-danger">*</span></label>
                        <select class="custom-select rounded-0" id="statusPembayaran" name="statusPembayaran">
                            <option value="" disabled selected>-- Pilih Status Pembayaran --</option>
                            <option value="DP">DP</option>
                            <option value="Lunas">Lunas</option>
                            <option value="Belum Bayar">Belum Bayar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenisPembayaran">Jenis Pembayaran <span class="text-danger">*</span></label>
                        <select class="custom-select rounded-0" id="jenisPembayaran" name="jenisPembayaran">
                            <option value="" disabled selected>-- Pilih Jenis Pembayaran --</option>
                            <option value="Cash">Tunai</option>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Transfer BRI">Transfer BRI</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Saldo Tokopedia">Marketplace Tokopedia</option>
                            <option value="Saldo Shopee">Marketplace Shopee</option>
                            <option value="Saldo Bukalapak">Marketplace Bukalapak</option>
                            <option value="Bayar Waktu Ambil">Bayar Waktu Ambil</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jumlahPembayaran">Jumlah Pembayaran Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah" id="jumlahPembayaran" placeholder="Rp" name="jumlahPembayaran" required>
                    </div>

                    <div class="form-group">
                        <label for="hargaProduk">Harga Produk (satuan) <p class="text-danger font-italic text-sm mb-0">* (Biaya Ongkir / Pasang tidak termasuk)</p></label>
                        <input type="text" class="form-control rupiah" id="hargaProduk" placeholder="Rp" name="hargaProduk" required>
                    </div>

                    <div class="form-group">
                        {{-- Input Qty Barang / Produk --}}
                        <label for="qty">Qty <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="qty" placeholder="Masukkan jumlah / qty produk" name="qty">
                    </div>

                    <div class="form-group">
                        {{-- Biaya Jasa Pengiriman --}}
                        <label for="biayaPengiriman">Biaya Jasa Pengiriman <span class="text-sm text-muted font-italic">(Opsional)</span></label>
                        <input type="text" class="form-control rupiah" id="biayaPengiriman" placeholder="Rp" name="biayaPengiriman">
                    </div>

                    <div class="form-group">
                        {{-- Biaya Jasa Pemasangan --}}
                        <label for="biayaPemasangan">Biaya Jasa Pemasangan <span class="text-sm text-muted font-italic">(Opsional)</span></label>
                        <input type="text" class="form-control rupiah" id="biayaPemasangan" placeholder="Rp" name="biayaPemasangan">
                    </div>
                    
                    <div class="form-group">
                         {{-- Biaya jasa pengemasan --}}
                        <label for="biayaPengemasan">Biaya Jasa Packing <span class="text-sm text-muted font-italic">(Opsional)</span></label>
                        <input type="text" class="form-control rupiah" id="biayaPengemasan" placeholder="Rp" name="biayaPengemasan">
                    </div>

                    <div class="form-group">
                        {{-- Alamat Pengiriman --}}
                        <label for="alamatPengiriman">Alamat Pengiriman / Pemasangan <span class="text-sm text-muted font-italic">(Opsional)</span></label>
                        <input type="text" class="form-control" id="alamatPengiriman" placeholder="Alamat Pengiriman / Pemasangan" name="alamatPengiriman">
                    </div>

                    <div id="formBuktiBayar" class="form-group">
                        {{-- Bukti Pembayaran / Transfer --}}
                        <label for="buktiPembayaran">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="buktiPembayaran" name="buktiPembayaran">
                                <label class="custom-file-label" for="buktiPembayaran">Pilih File</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{-- Total Omset --}}
                        <label for="totalPembayaran">Total Omset <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rupiah" id="totalPembayaran" placeholder="Rp" name="totalPembayaran" required readonly>
                    </div>

                    {{-- Tampilkan sisa pembayaran jika status pembayaran = DP, tampilkan Lunas jika status pembayaran Lunas --}}
                    <div class="form-group">
                        <label for="sisaPembayaran">Sisa Pembayaran</label>
                        <input type="text" class="form-control rupiah" id="sisaPembayaran" placeholder="Rp" name="sisaPembayaran" readonly>
                    </div>

                </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Desain</h2>
                </div>
                <div class="card-body">
                    <input type="hidden" name="idOrder" value="{{ $order->id }}">
                    <div class="form-group">
                        <label for="namaDesain">Nama Desain <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="namaDesain" placeholder="Nama Desain" name="namaDesain" value="{{ $order->title }}" readonly>
                    </div>
                    <div class="form-group">
                        {{-- Desainer --}}
                        <label for="desainer">Desainer <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="desainer" placeholder="Nama Desainer" name="desainer" value="{{ $order->employee->name }}" readonly>
                    </div>
                    <div class="form-group">
                        {{-- File Desain --}}
                        <h6><strong>File Cetak</strong></h6>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fileDesain" name="fileDesain" value="{{ $order->file_cetak }}" disabled>
                                <label class="custom-file-label" for="fileDesain">{{ $order->file_cetak }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- File Desain --}}
                        <h6><strong>Upload Gambar ACC <span class="text-danger">*</span></strong></h6>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="accDesain" name="accDesain" required>
                                <label class="custom-file-label" for="accDesain">Unggah Gambar</label>
                            </div>
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
                        <input type="hidden" name="sales" value="{{ $order->sales_id }}">
                        {{-- Tombol Submit --}}
                        <div class="d-flex align-items-center">
                            <button id="submitToAntrian" type="submit" class="btn btn-primary">Submit<div id="loader" class="loader" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @includeIf('page.antrian-workshop.modal.modal-tambah-pelanggan')
</div>

@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>

   <script>
       $(document).ready(function(){
        $('#hargaProduk, #biayaPengiriman, #biayaPemasangan, #jumlahPembayaran, #totalPembayaran, #sisaPembayaran').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        $('#statusPembayaran').change(function(){
        var status = $(this).val();
            if(status == 'Belum Bayar'){
                $('#jumlahPembayaran').val('0');
                $('#jumlahPembayaran').attr('readonly', true);
                $('#jenisPembayaran').val('Bayar Waktu Ambil');
                $('#jenisPembayaran').attr('readonly', true);
                $('#formBuktiBayar').hide();
            }else{
                $('#jumlahPembayaran').val('');
                $('#jumlahPembayaran').attr('readonly', false);
                $('#jenisPembayaran').val('');
                $('#jenisPembayaran').attr('readonly', false);
            }
        });

        $('#jenisPembayaran').change(function(){
            var jenis = $(this).val();
            if(jenis == 'Cash' || jenis == 'Bayar Waktu Ambil'){
                $('#formBuktiBayar').hide();
            }else{
                $('#formBuktiBayar').show();
            }
        });

        $('#hargaProduk, #qty, #biayaPengiriman, #biayaPemasangan').change(function(){
            var jumlahPembayaran = parseInt($('#jumlahPembayaran').val().replace('Rp ', '').replace(/\./g, ''));
            var qty = parseInt($('#qty').val());
            var hargaProduk = parseInt($('#hargaProduk').val().replace('Rp ', '').replace(/\./g, ''));
            var biayaPengiriman = parseInt($('#biayaPengiriman').val().replace('Rp ', '').replace(/\./g, ''));
            var biayaPemasangan = parseInt($('#biayaPemasangan').val().replace('Rp ', '').replace(/\./g, ''));

            if(isNaN(qty)){
                qty = 0;
            }
            if(isNaN(hargaProduk)){
                hargaProduk = 0;
            }
            if(isNaN(biayaPengiriman)){
                biayaPengiriman = 0;
            }
            if(isNaN(biayaPemasangan)){
                biayaPemasangan = 0;
            }

            var total = (hargaProduk * qty) + biayaPengiriman + biayaPemasangan;
            totalView = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            var sisa = total - jumlahPembayaran;
            sisaView = sisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $('#totalPembayaran').val('Rp ' + totalView);
            $('#sisaPembayaran').val('Rp ' + sisaView);
        });

        $('#nama').select2({
            placeholder: 'Pilih Pelanggan',
            ajax:{
                url:"{{ route('pelanggan.search') }}",
                processResults: function(data){
                    $('#alamat').val('');
                    $('#noHp').val('');
                    return{
                        results: $.map(data, function(item){
                            var status = $('#statusPelanggan').val(item.frekuensi_order);
                            if(status >= '0'){
                                $('#statusPelanggan').val('Pelanggan Baru');
                            }else if(status >= '1'){
                                $('#statusPelanggan').val('Pernah Order');
                            }else if(status >= '2'){
                                $('#statusPelanggan').val('Repeat Order');
                            }
                            return{
                                id: item.id,
                                text: item.nama+ ' ' + '-' + ' ' +item.telepon,
                            }
                        })
                    }
                },
                cache: true
            }
        });

   $('#pelanggan-form').on('submit', function(e){
       e.preventDefault();

       $(this).find('#submitPelanggan').prop('disabled', true);
       $(this).find('#loader').show();

       const formData = $(this).serialize();
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       $.ajax({
           url:"{{ route('pelanggan.store') }}",
           type:"POST",
           data: formData,
           success:function(response){
               if(response.success){
                   $('#exampleModal').modal('hide');
                   //Mengosongkan Form pada Modal
                   $('#modalTelepon').val('');
                   $('#modalNama').val('');
                   $('#modalAlamat').val('');
                   $('#modalInstansi').val('');
                   $('#infoPelanggan').val('default');
                   Swal.fire({
                       icon: 'success',
                       title: 'Berhasil',
                       text: 'Data Pelanggan Berhasil Ditambahkan',
                       timer: 2500
                   });
                   setInterval(() => {
                       location.reload();
                   }, 2500);
               }
            }
        });
    });

    $('#submitToAntrian').on('submit', function(e){
        e.preventDefault();
        $(this).find('#submitToAntrian').prop('disabled', true);
        $('#loader').show();
        this.submit();
    });

});
</script>
<script>
    $(function () {
      bsCustomFileInput.init();
    });
</script>
@endsection
