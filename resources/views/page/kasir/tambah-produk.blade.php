@extends('layouts.app')

@section('title', 'POS Kasir | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Tambah Transaksi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Tambah Produk</h3>
                </div>
                <div class="card-body">
                    <form id="formTambahProduk" action="" enctype="text/plain" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kode_produk">Kode Produk</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" placeholder="Contoh : P01214140023">
                        </div>
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Contoh : Gagang Flash 4040">
                        </div>
                        <div class="form-group">
                            <label for="harga_kulak">Harga Kulak</label>
                            <input type="text" class="form-control maskMoney" id="harga_kulak" name="harga_kulak" placeholder="Contoh : Rp 8.000">
                        </div>
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="text" class="form-control maskMoney" id="harga_jual" name="harga_jual" placeholder="Contoh : Rp 12.000">
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" placeholder="Contoh : 8">
                        </div>

                        <div class="form-group">
                            <label for="grosir">Grosir</label>
                            <button id="btnTambahGrosir" type="button" class="btn btn-sm btn-outline-primary ml-2"><i class="fas fa-plus"></i> Tambah Harga Grosir</button>
                        </div>
                        <table id="tabelGrosir" class="table table-bordered" style="display: none;">
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
                                
                            </tbody>
                        </table>
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
            var kode_produk = $('#kode_produk').val();
            var nama_produk = $('#nama_produk').val();
            var harga_kulak = $('#harga_kulak').maskMoney('unmasked')[0];
            var harga_jual = $('#harga_jual').maskMoney('unmasked')[0];
            var stok = $('#stok').val();
            var min = [];
            var max = [];
            var harga = [];
            $('#tabelGrosir tbody tr').each(function() {
                min.push($(this).find('td').eq(1).find('input').val());
                max.push($(this).find('td').eq(2).find('input').val());
                harga.push($(this).find('td').eq(3).find('input').maskMoney('unmasked')[0]);
            });

            $.ajax({
                url: "{{ route('kasir.manageProduct.store') }}",
                type: "POST",
                data: {
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
                    $('#formTambahProduk').trigger('reset');
                    $('#tabelGrosir').hide();
                    $('#tabelGrosir tbody').empty();
                    $('#tableProduct').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data Produk berhasil ditambahkan.'
                    });
                }
            });
        });
    });
</script>
@endsection