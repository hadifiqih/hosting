@extends('layouts.app')

@section('title', 'POS Kasir | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Ubah Produk')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Ubah Produk</h3>
                </div>
                <div class="card-body">
                    <form id="formUbahProduk" action="" enctype="text/plain" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_produk" value="{{ $produk->id }}">
                        <div class="form-group">
                            <label for="kode_produk">Kode Produk</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="{{ $produk->kode_produk }}">
                        </div>
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="{{ $produk->nama_produk }}">
                        </div>
                        <div class="form-group">
                            <label for="harga_kulak">Harga Kulak</label>
                            <input type="text" class="form-control maskMoney" id="harga_kulak" name="harga_kulak" value="{{ isset($harga->harga_kulak) ? $harga->harga_kulak : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="text" class="form-control maskMoney" id="harga_jual" name="harga_jual" value="{{ isset($harga->harga_jual) ? $harga->harga_jual : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="{{ isset($stok->jumlah_stok) ? $stok->jumlah_stok : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="grosir">Grosir</label>
                            <button id="btnTambahGrosir" type="button" class="btn btn-sm btn-outline-primary ml-2"><i class="fas fa-plus"></i> Tambah Harga Grosir</button>
                        </div>
                        <div class="table-responsive">
                            <table id="tabelGrosir" class="table table-bordered" @if(!isset($grosir)) style="display: none;" @endif>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Jumlah Min.</th>
                                        <th>Jumlah Max.</th>
                                        <th>Harga Satuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($grosir != null)
                                        @foreach($grosir as $g)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><input type="number" class="form-control" name="min[]" value="{{ $g->min_qty }}"></td>
                                            <td><input type="number" class="form-control" name="max[]" value="{{ $g->max_qty }}"></td>
                                            <td><input type="text" class="form-control maskMoney" name="harga[]" value="{{ $g->harga_grosir }}"></td>
                                            <td><button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <button id="btnTambahProduk" type="submit" class="btn btn-sm btn-primary float-right">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
<script>
    function removeCurrency(value) {
        return value.replace('Rp ', '').replace(/\./g, '').replace(/,/g, '');
    }

    $(document).ready(function() {
        // Mask Money
        $('.maskMoney').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        // Tambah Grosir
        $('#btnTambahGrosir').click(function() {
            $('#tabelGrosir').show();
            $('#tabelGrosir tbody').append(`
                <tr>
                    <td></td>
                    <td><input type="number" class="form-control" name="min[]" placeholder="Contoh : 5"></td>
                    <td><input type="number" class="form-control" name="max[]" placeholder="Contoh : 10"></td>
                    <td><input type="text" class="form-control maskMoney" name="harga[]" placeholder="Contoh : Rp 10.000"></td>
                    <td><button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                </tr>
            `);
            $('#tabelGrosir tbody tr').each(function(index) {
                $(this).find('td').eq(0).text(index + 1);
            });
            $('.maskMoney').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});
        });

        // Hapus Grosir
        $('#tabelGrosir').on('click', 'tbody tr td button', function() {
            $(this).closest('tr').remove();
            $('#tabelGrosir tbody tr').each(function(index) {
                $(this).find('td').eq(0).text(index + 1);
            });
        });

        // Tambah Produk
        $('#formTambahProduk').submit(function(e) {
            e.preventDefault();
            var token = $('meta[name="csrf-token"]').attr('content');
            var id_produk = $('#id_produk').val();
            var kode_produk = $('#kode_produk').val();
            var nama_produk = $('#nama_produk').val();
            var harga_kulak = removeCurrency($('#harga_kulak').val());
            var harga_jual = removeCurrency($('#harga_jual').val());
            var stok = $('#stok').val();
            var min = [];
            var max = [];
            var harga = [];
            $('#tabelGrosir tbody tr').each(function() {
                min.push($(this).find('td').eq(1).find('input').val());
                max.push($(this).find('td').eq(2).find('input').val());
                harga.push(removeCurrency($(this).find('td').eq(3).find('input').val()));
            });

            $.ajax({
                url: "{{ route('pos.updateProduct') }}",
                type: "POST",
                data: {
                    _token: token,
                    id_produk: id_produk,
                    kode_produk: kode_produk,
                    nama_produk: nama_produk,
                    harga_kulak: harga_kulak,
                    harga_jual: harga_jual,
                    stok: stok,
                    min: min,
                    max: max,
                    harga: harga
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>
@endsection