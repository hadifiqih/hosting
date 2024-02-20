@extends('layouts.app')

@section('title', 'Dokumentasi')

@section('username', Auth::user()->name)

@section('page', 'Dokumentasi')

@section('breadcrumb', 'Upload Dokumentasi')

@section('content')

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
</div>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Dokumentasi #{{ $ticket }}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($barang as $b)
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ $b->job->job_name }}</h4>
                                </div>
                                <div class="card-body">
                                    <img src="{{ asset($b->accdesain) }}" class="img-fluid" alt="Produk" style="width: 100%; height: 100%;">
                                </div>
                                <div class="card-footer">
                                    @if($b->documentation_id == null)
                                    <a href="{{ route('documentation.edit', $b->id) }}" class="btn btn-sm btn-primary float-right">Upload</a>
                                    @else
                                    <a href="#" class="btn btn-sm btn-success float-right"><i class="fas fa-check"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('antrian.markSelesai', $b->ticket_order) }}" class="btn btn-danger float-right">Tandai Selesai</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection