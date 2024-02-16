@extends('layouts.app')

@section('title', 'Dokumentasi Upload | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Dokumentasi')

@section('breadcrumb', 'Upload Dokumentasi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Dokumentasi</h3>
                </div>
                <div class="card-body">
                        <!-- Formulir Dropzone untuk unggah file -->
                        <form action="{{ route('documentation.upload') }}" class="dropzone" id="my-dropzone">
                            <div class="dz-message" data-dz-message><span>Letakkan file di sini atau klik untuk memilih file</span></div>
                            <input type="hidden" name="job_id" value="{{ $barang->job_id }}">

                        </form>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
  // Konfigurasi Dropzone
  Dropzone.options.myDropzone = {
    paramName: "file", // Nama yang digunakan untuk mentransfer file
    maxFilesize: 50, // Ukuran maksimum file dalam MB
    dictDefaultMessage: "Letakkan file di sini atau klik untuk memilih file",
    acceptedFiles: 'image/*', // Batasi hanya file gambar yang dapat diunggah
    addRemoveLinks: true, // Tampilkan tautan Hapus pada setiap file yang diunggah
    autoUpload: false, // Setel false agar file tidak diunggah secara otomatis
  };
</script>

@endsection