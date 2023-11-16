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
                                    <th colspan="3" id="totalHarga">Rp 90.000</th>
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

        $("#tableProduk").DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [[ 0, "desc" ]],
        });
    });

    function tambahProduk(){
        $('#modalPilihProduk').modal('show');
    }

    $(document).ready(function(){
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

        $('#namaProduk').select2({
            placeholder: 'Pilih Produk',
            ajax: {
                url: "{{ route('getAllJobs') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                            text: item.job_name,
                            id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('.btnTambah').on('submit', function(e){
            e.preventDefault();

            var namaProduk = $('#namaProduk').val();
            var qty = $('#qty').val();
            var harga = $('#harga').val();
            var diskon = $('#diskon').val();
            var keterangan = $('#keterangan').val();

            var table = document.getElementById("tableProduk");
            var row = table.insertRow(1);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = namaProduk;
            var cell2 = row.insertCell(1);
            cell2.innerHTML = qty;
            var cell3 = row.insertCell(2);
            cell3.innerHTML = harga;
            var cell4 = row.insertCell(3);
            cell4.innerHTML = diskon;
            var cell5 = row.insertCell(4);
            cell5.innerHTML = keterangan;
            var cell6 = row.insertCell(5);
            cell6.innerHTML = '<button type="button" class="btn btn-sm btn-danger" onclick="hapusProduk(this)">Hapus</button>';

        });
    });
</script>
@endsection
