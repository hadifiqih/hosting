@extends('layouts.app')

@section('title', 'Daftar Produk | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'POS')

@section('breadcrumb', 'Daftar Produk')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Produk</h3>
                    <button class="btn btn-sm btn-primary float-right" onclick="modalTambahProduk()"><i class="fas fa-plus-circle"></i> Tambah Produk</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Kulak</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->kode_produk }}</td>
                                    <td>{{ $product->nama_produk }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->sell_price }}</td>
                                    @if(auth()->user()->cabang_id == 1)
                                    <td>{{ $product->stok_1 }}</td>
                                    @elseif(auth()->user()->cabang_id == 2)
                                    <td>{{ $product->stok_2 }}</td>
                                    @elseif(auth()->user()->cabang_id == 3)
                                    <td>{{ $product->stok_3 }}</td>
                                    @elseif(auth()->user()->cabang_id == 4)
                                    <td>{{ $product->stok_4 }}</td>
                                    @endif
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('page.kasir.modal.tambah-produk')
@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
<script>
    function modalTambahProduk() {
        $('#tambahProduk').modal('show');
    }

    $(document).ready(function() {
        $('.maskMoney').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

        $('#table').DataTable();

        $('#formTambahProduk').on('submit', function(e) {
            e.preventDefault();
            var token = $("input[name='_token']").val();
            var kode_produk = $('#kode_produk').val();
            var nama_produk = $('#nama_produk').val();
            var harga_kulak = $('#harga_kulak').val();
            var harga_jual = $('#harga_jual').val();
            var stok = $('#stok').val();

            $.ajax({
                url: "{{ route('pos.storeProduct') }}",
                type: "POST",
                data: {
                    _token: token,
                    kode_produk: kode_produk,
                    nama_produk: nama_produk,
                    harga_kulak: harga_kulak,
                    harga_jual: harga_jual,
                    stok: stok
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>
@endsection