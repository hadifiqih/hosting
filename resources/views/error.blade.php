
@extends('layouts.app')

@section('title', 'Error | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Error')

@section('breadcrumb', 'Error')

@section('content')
{{-- Menampilkan confirm dengan error message lalu kembali ke halaman sebelumnya--}}
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
@endif

@endsection