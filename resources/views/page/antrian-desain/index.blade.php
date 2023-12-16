@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('style')
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/dropzone/min/dropzone.min.css">
@endsection

@section('content')

{{-- Jika ada sesi success --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
@endif

{{-- Jika ada sesi error --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                @if(Auth::user()->role == 'sales' || Auth::user()->role == 'supervisor')
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Menunggu Desain</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Progress Desain</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai Desain</a>
                </li>
                @if(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                <li class="nav-item">
                    <a class="nav-link" id="revisi-desain-tab" data-toggle="pill" href="#revisi-desain" role="tab" aria-controls="revisi-desain" aria-selected="false">Revisi Desain</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
            @if(Auth::user()->role == 'sales' || Auth::user()->role == 'supervisor')
            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Antrian Desain</h2>
                  {{-- Tombol tambah order --}}
                    @if(Auth::user()->role == 'sales')
                        <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                    @endif
                </div>
                <div class="card-body">
                {{-- Menampilkan Antrian Desain --}}
                <table id="tableAntrianDesain" class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket Order</th>
                            <th>Judul Desain</th>
                            <th>Sales</th>
                            <th>Ref. Desain</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                {{-- End Menampilkan Antrian Desain --}}
                    </div>
                </div>
            </div>
            @endif

            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianDikerjakan" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket Order</th>
                                <th>Judul Desain</th>
                                <th>Sales</th>
                                <th>Ref. Desain</th>
                                <th>Jenis Produk</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                    {{-- Modal Upload --}}
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianSelesai" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket Order</th>
                                <th>Sales</th>
                                <th>Judul Desain</th>
                                <th>Waktu Selesai</th>
                                <th>Periode</th>
                                <th>Produk</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                <th>File Cetak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                  </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    @includeIf('page.antrian-desain.modal.modal-bagiDesain')
</div>
@endsection


@section('script')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        function pilihDesainer(id) {
            $('#modalBagiDesain').modal('show');
        }

        $("#tableAntrianDesain").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.menunggu') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_order', name: 'ticket_order'},
                {data: 'title', name: 'title'},
                {data: 'sales', name: 'sales'},
                {data: 'ref_desain', name: 'ref_desain'},
                {data: 'job_name', name: 'job_name'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
        });

        $("#tableAntrianDikerjakan").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.dalamProses') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_order', name: 'ticket_order'},
                {data: 'title', name: 'title'},
                {data: 'sales', name: 'sales'},
                {data: 'ref_desain', name: 'ref_desain'},
                {data: 'job_name', name: 'job_name'},
                {data: 'desainer', name: 'desainer'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
        });

        $("#tableAntrianSelesai").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.selesai') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_order', name: 'ticket_order'},
                {data: 'sales', name: 'sales'},
                {data: 'title', name: 'title'},
                {data: 'selesai', name: 'selesai'},
                {data: 'periode', name: 'periode'},
                {data: 'produk', name: 'produk'},
                {data: 'desainer', name: 'desainer'},
                {data: 'status', name: 'status'},
                {data: 'file_cetak', name: 'file_cetak'},
                {data: 'action', name: 'action'},
            ],
        });

        $("#tableRevisiDesain").DataTable({
            responsive: true,
            autoWidth: false,
        });

        $("#tableDesainer").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.desainer') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nama_desainer', name: 'nama_desainer'},
                {data: 'jumlah_desain', name: 'jumlah_desain'},
                {data: 'action', name: 'action'},
            ],
        });

        Dropzone.options.myDropzone = { // camelized version of the `id`
                paramName: "fileCetak", // The name that will be used to transfer the file
                clickable: true,
                acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd, .blend, .skp",
                dictInvalidFileType: "Type file ini tidak dizinkan",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                maxFileSize: 50,
        };

        Dropzone.options.myDropzoneRevisi = { // camelized version of the `id`
                paramName: "fileRevisi", // The name that will be used to transfer the file
                clickable: true,
                acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
                dictInvalidFileType: "Type file ini tidak dizinkan",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                maxFileSize: 50,
        };

        Dropzone.options.myDropzoneReupload = { // camelized version of the `id`
                paramName: "fileReupload", // The name that will be used to transfer the file
                clickable: true,
                acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
                dictInvalidFileType: "Type file ini tidak dizinkan",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                maxFileSize: 50,
        };

    $(document).ready(function() {
        // saat form bagiDesain di submit, maka tampilkan loader dan disable button submitnya
        $('.bagiDesain').submit(function() {
            $('.submitButton').prop('disabled', true);
        });

        // saat form upload di submit, maka tampilkan loader dan disable button submitnya
        $('#submitUploadDesain').click(function() {
            $(this).addClass('disabled');
            $('#loader').show();
        });

        $('#submitRevisiDesain').click(function() {
            $(this).addClass('disabled');
            $('#loader').show();
        });
    });
</script>

@if(session('success-submit'))
<script>
$(function() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 5000
    });
        Toast.fire({
        icon: 'success',
        title: '{{ session('success-submit') }}'
        });
});
</script>
@endif

@if(session('error-filecetak'))
    <script>
    $(function() {
        var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
        });
        Toast.fire({
            icon: 'error',
            title: '{{ session('error-filecetak') }}'
        });
    });
    </script>
@endif
@endsection
