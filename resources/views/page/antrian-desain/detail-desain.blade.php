@extends('layouts.app')

@section('title', 'Unduh Laporan Workshop')

@section('username', Auth::user()->name)

@section('page', 'Report')

@section('breadcrumb', 'Laporan Workshop')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Detail Desain</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="namaDesain">Nama Project</label>
                        <input type="text" class="form-control" id="namaDesain" name="namaDesain" value="{{ $order->title }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="deskripsiDesain">Deskripsi Project</label>
                        <textarea class="form-control" id="deskripsiDesain" name="deskripsiDesain" rows="3" readonly>{{ $order->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadlineDesain"></label>
                        <input type="text" class="form-control" id="deadlineDesain" name="deadlineDesain" value="{{ $order->deadline }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
