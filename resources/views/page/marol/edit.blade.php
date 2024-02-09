@extends('layouts.app')

@section('title', 'Data Iklan | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Iklan')

@section('breadcrumb', 'Data Iklan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><strong>Edit Iklan</strong></h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('iklan.update', $iklan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="marol">Marol</label>
                            <input type="text" class="form-control" id="marol" name="marol" value="{{ $iklan->marol }}">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="datetime" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $iklan->tanggal_mulai }}">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="datetime" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $iklan->tanggal_selesai }}">
                        </div>

                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <select class="form-control" id="nama_produk" name="nama_produk">
                                @foreach ($produk as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $iklan->job_id ? 'selected' : '' }}>{{ $item->job_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nama_sales">Nama Sales</label>
                            <select class="form-control" id="nama_sales" name="nama_sales">
                                @foreach ($sales as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $iklan->sales_id ? 'selected' : '' }}>{{ $item->sales_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="platform">Platform</label>
                            <select class="form-control" id="platform" name="platform">
                                @foreach ($platform as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $iklan->platform ? 'selected' : '' }}>{{ $item->nama_sumber }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="biaya_iklan">Biaya Iklan</label>
                            <input type="number" class="form-control" id="biaya_iklan" name="biaya_iklan" value="{{ $iklan->biaya_iklan }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
@endsection