@extends('layouts.app')

@section('title', 'Halaman Produk | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Produk')

@section('header', 'Daftar Produk')

@section('content')
<div class="row row-cols-1 row-cols-md-3 g-4">
    @foreach($products as $product)
    <div class="col">
      <div class="card h-100">
        <img class="card-img-top" src="{{ asset('sneat') }}/assets/img/elements/{{ $product->img_name }}" alt="Card image cap" />
        <div class="card-body">
          <h5 class="card-title">{{ $product->job_name }}</h5>
          <p class="card-text">{{ $product->note }}</p>
        </div>
      </div>
    </div>
    @endforeach 
</div>

@endsection