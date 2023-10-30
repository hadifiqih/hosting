@extends('layouts.app')

@section('username', Auth::user()->name)

@section('page', 'Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
      <div class="container-fluid">
        <div class="d-flex flex-column bd-highlight">
        <div class="p-2 bd-highlight">
            <h1 class="ml-4 pt-3">Selamat Datang, <br> <strong>{{ Auth::user()->name }}</strong>&#128075;</h1>
        </div>
        <div class="p-2 bd-highlight">
            <h5 class="ml-4">Berikan yang terbaik untuk setiap tanggung jawab pekerjaan kamu ! &#128170;</h5>
        </div>
        <div class="p-2 bd-highlight">
            <img src="{{ asset('adminlte') }}/dist/img/team-dashboard.png" class="img-fluid mt-3" alt="Team Dashboard">
        </div>
        </div>
      </div>
@endsection

