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
                    <a class="btn btn-sm" href="{{ route('pos.addOrder') }}"><i class="fas fa-plus-circle"></i> Tambah Transaksi</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <a href="{{ route('pos.editProduct', $product->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="{{ route('pos.deleteProduct', $product->id) }}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
</div>

@endsection