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
                        <select class="form-control select2" id="namaPelanggan" name="namaPelanggan" style="width: 100%">
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
                    <h2 class="card-title">Informasi Produk</h2>
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
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-center">Total</th>
                                    <th id="subtotal" colspan="3" id="totalHarga" class="text-danger">Rp {{ number_format($totalBarang, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                
                            <div class="form-group mt-3">
                                <label for="packing">Biaya Packing</label>
                                <input type="text" class="form-control" id="packing" placeholder="Contoh : Rp 100.000" name="packing">
                            </div>

                            <div class="form-group">
                                <label for="ongkir">Biaya Ongkir</label>
                                <input type="text" class="form-control" id="ongkir" placeholder="Contoh : Rp 100.000" name="ongkir">
                            </div>

                            <div class="form-group">
                                <label for="pasang">Biaya Pasang</label>
                                <input type="text" class="form-control" id="pasang" placeholder="Contoh : Rp 100.000" name="pasang">
                            </div>

                            <div class="form-group">
                                <label for="diskon">Diskon / Potongan Harga</label>
                                <input type="text" class="form-control" id="diskon" placeholder="Contoh : Rp 100.000" name="diskon">
                            </div>

                            <div class="row">
                                <div class="col">
                                    <span>Total : </span><h4 class="font-weight-bold text-danger">Rp 1.840.000</h4>
                                </div>
                                <div class="col">
                                    <span>Sisa Pembayaran : </span><h4 class="font-weight-bold text-danger">Rp 0</h4>
                                </div>
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
                    <h2 class="card-title">Data Desain</h2>
                </div>
                <div class="card-body">
                    <button class="btn-sm btn-primary">Upload ACC Desain <i class="fas fa-plus "></i></button>

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
                            <button id="submitToAntrian" type="submit" class="btn btn-primary">Submit<div id="loader" class="loader" style="display: none;"></div>
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

<script>
    $(function () {
        bsCustomFileInput.init();
    });

    function tambahProduk(){
        $('#modalPilihProduk').modal('show');
    }

    $(document).ready(function(){
        // Mask Money
        $('#harga').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        //nama produk select2
        $('#modalPilihProduk #kategoriProduk').on('change', function(){
            var kategoriProduk = $(this).val();

            $('#modalPilihProduk #namaProduk').val(null).trigger('change');
            $('#modalPilihProduk #namaProduk').empty();
            $('#modalPilihProduk #namaProduk').append(`<option value="" selected disabled>Pilih Produk</option>`);

        $('#modalPilihProduk #namaProduk').select2({
            placeholder: 'Pilih Produk',
            ajax: {
                url: "{{ route('getJobsByCategory') }}",
                dataType: 'json',
                delay: 250,
                data: {
                    kategoriProduk: kategoriProduk
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            if(item.instansi == null){
                                return {
                                    text: item.job_name,
                                    id: item.id,
                                }
                            }else{
                                return {
                                    text: item.job_name + ' - ' + item.instansi,
                                    id: item.id,
                                }
                            }
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
                {data: 'action', name: 'action'},
            ],
        });

        // Nama Pelanggan
        $('#namaPelanggan').select2({
            placeholder: 'Pilih Pelanggan',
            ajax: {
                url: "{{ route('getAllCustomers') }}",
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

        $('#formTambahProduk').on('submit', function(e){
            e.preventDefault();

            // Inisialisasi File acc_desain
            var acc_desain = $('#acc_desain')[0].files[0];
            acc_desain = new FormData(this);
            acc_desain.append('acc_desain', acc_desain);
            acc_desain.append('ticket_order', $('#ticket_order').val());
            acc_desain.append('namaProduk', $('#namaProduk').val());
            acc_desain.append('kategoriProduk', $('#kategoriProduk').val());
            acc_desain.append('qty', $('#qty').val());
            acc_desain.append('harga', $('#harga').val());
            acc_desain.append('keterangan', $('#keterangan').val());

            $.ajax({
                url: "{{ route('barang.store') }}",
                method: "POST",
                data: acc_desain,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#modalPilihProduk').modal('hide');
                    $('#tableProduk').DataTable().ajax.reload();
                    $('#formTambahProduk')[0].reset();

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
    });
</script>
@endsection
