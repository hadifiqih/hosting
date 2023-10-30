@extends('layouts.app')

@section('title', 'Antrian Workshop')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Progress Produksi')

@section('content')
    {{-- Form Upload Progress Produksi (Foto atau Video) dengan keterangan --}}

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Progress Produksi #{{ $antrian->ticket_order }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('store.progressProduksi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="idAntrian" id="antrian_id" value="{{ $antrian->id }}">
                {{-- Upload Gambar Proses --}}
                <div class="form-group">
                    <label for="">Upload Gambar Proses</label>
                    <input class="form-control" type="file" name="fileGambar" id="file" class="form-control" accept="image/*">
                </div>
                {{-- Upload Video Proses --}}
                <div class="form-group">
                    <label for="">Upload Video Proses</label>
                    <input class="form-control" type="file" name="fileVideo" id="file" class="form-control" accept="video/*">
                </div>
                <div class="form-group">
                    <label for="">Keterangan Proses <span class="text-danger">*</span></label>
                    <input type="text" name="note" id="keterangan" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

@endsection
