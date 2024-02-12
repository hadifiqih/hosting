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
                    <form action="{{ route('iklan.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="marol">Marol</label>
                            <select class="form-control" id="marol" name="marol" required>
                                <option value="">-- Pilih Marol --</option>
                                @foreach ($marol as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div>

                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <select class="form-control" id="nama_produk" name="nama_produk" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produk as $item)
                                    <option value="{{ $item->id }}">{{ $item->job_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nama_sales">Nama Sales</label>
                            <select class="form-control" id="nama_sales" name="nama_sales" required>
                                <option value="">-- Pilih Sales --</option>
                                @foreach ($sales as $item)
                                    <option value="{{ $item->id }}">{{ $item->sales_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="platform">Platform</label>
                            <select class="form-control" id="platform" name="platform" required>
                                <option value="">-- Pilih Platform --</option>
                                @foreach ($platform as $item)
                                    <option value="{{ $item->code_sumber }}">{{ $item->nama_sumber }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="biaya_iklan">Biaya Iklan</label>
                            <input type="text" class="form-control" id="biaya_iklan" name="biaya_iklan" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
<script>
    $('#biaya_iklan').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});
</script>
@endsection