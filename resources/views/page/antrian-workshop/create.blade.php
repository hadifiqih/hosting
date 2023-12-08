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
                                <th>Diskon</th>
                                <th>Harga Total</th> 
                                <th>Keterangan Spesifikasi</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right">Total</th>
                                    <th colspan="3" id="totalHarga">0</th>
                                </tr>
                            </tfoot>
                        </table>
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
                url: "{{ route('barang.show', $order->ticket_order) }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'kategori', name: 'kategori'},
                {data: 'produk', name: 'produk'},
                {data: 'qty', name: 'qty'},
                {data: 'harga', name: 'harga'},
                {data: 'diskon', name: 'diskon'},
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

            // Get Value
            var namaProduk = $('#namaProduk').val();
            var kategoriProduk = $('#kategoriProduk').val();
            var qty = $('#qty').val();
            var harga = $('#harga').val();
            var keterangan = $('#keterangan').val();
            var ticket_order = $('#ticket_order').val();
            var acc_desain = $('#acc_desain').val();

            //ajax request
            $.ajax({
                url: "{{ route('barang.store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    namaProduk: namaProduk,
                    kategoriProduk: kategoriProduk,
                    qty: qty,
                    harga: harga,
                    keterangan: keterangan,
                    ticket_order: ticket_order,
                    acc_desain: acc_desain,
                },

                success: function(data){
                    $('#tableProduk').DataTable().ajax.reload();
                    $('#modalPilihProduk').modal('hide');
                    $('#namaProduk').val('');
                    $('#qty').val('');
                    $('#harga').val('');
                    $('#keterangan').val('');
                    $('#acc_desain').val('');

                    // Total Harga
                    $.ajax({
                        url: "{{ route('getTotalHarga', $order->ticket_order) }}",
                        type: 'GET',
                        success: function(data){
                            $('#totalHarga').html(data.totalHarga);
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
    });
</script>
@endsection
