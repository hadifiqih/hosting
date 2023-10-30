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
                    <h2 class="card-title">Tanggal Laporan</h2>
                </div>
                <div class="card-body">
                    <label>Pilih Tanggal :</label>
                    <form action="{{ route('laporan-desain-pdf') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-9">
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Unduh</button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-2 text-sm text-muted font-italic">*Hanya dapat mengunduh laporan dalam 1 hari</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
