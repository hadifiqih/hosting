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

        $('#formTambahProduk').on('submit', function(e){
            e.preventDefault();

            // Get Value
            var namaProduk = $('#namaProduk').val();
            var kategoriProduk = $('#kategoriProduk').val();
            var qty = $('#qty').val();
            var harga = $('#harga').val();
            var diskon = $('#diskon').val();
            var keterangan = $('#keterangan').val();

            // Menghitung Harga Total
            var hargaTotal = harga * qty;
            var hargaDiskon = hargaTotal * (diskon / 100);
            var hargaTotalDiskon = hargaTotal - hargaDiskon;

            // Menambahkan Data ke Tabel
            $('#tableProduk tbody').append(`
                <tr>
                    <td></td>
                    <td><input type="hidden" name="katergori[]" value="${kategoriProduk}">${kategoriProduk}</td>
                    <td><input type="hidden" name="produk[]" value="${namaProduk}">${namaProduk}</td>
                    <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                    <td><input type="hidden" name="harga[]" value="${harga}">${harga}</td>
                    <td><input type="hidden" name="diskon[]" value="${diskon}">${diskon}</td>
                    <td><input type="hidden" name="hargaTotal[]" value="${hargaTotalDiskon}">${hargaTotalDiskon}</td>
                    <td><input type="hidden" name="keterangan[]" value="${keterangan}">${keterangan}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btnHapusProduk"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);

            // Menambahkan Nomor Urut
            var i = 1;
            $('#tableProduk tbody tr').each(function(){
                $(this).find('td:nth-child(1)').text(i);
                i++;
            });

            // Menambahkan Total Harga
            var totalHarga = 0;
            $('#tableProduk tbody tr').each(function(){
                totalHarga += parseInt($(this).find('td:nth-child(7) input').val());
            });
            $('#totalHarga').text(totalHarga);

            // Menutup Modal
            $('#modalPilihProduk').modal('hide');

            // Mengosongkan Form
            $('#namaProduk').val("");
            $('#qty').val('');
            $('#harga').val('');
            $('#diskon').val('');
            $('#keterangan').val('');
        });
    });
</script>
@endsection
