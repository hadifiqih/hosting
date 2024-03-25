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
                @if(Auth::user()->role_id == 11 || Auth::user()->role_id == 5 || Auth::user()->role_id == 17 || Auth::user()->role_id == 16)
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Menunggu Desain</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai Desain</a>
                </li>
                @if(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising' || auth()->user()->role == 'supervisor')
                <li class="nav-item">
                    <a class="nav-link" id="revisi-desain-tab" data-toggle="pill" href="#revisi-desain" role="tab" aria-controls="revisi-desain" aria-selected="false">Revisi Desain</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
            @if(Auth::user()->role_id == 11 || Auth::user()->role_id == 5 || Auth::user()->role_id == 17 || Auth::user()->role_id == 16)
            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Antrian Desain</h2>
                  {{-- Tombol tambah order --}}
                    @if(Auth::user()->role == 'sales')
                        <a href="{{ route('design.tambahDesain') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                    @endif
                </div>
                <div class="card-body">
                {{-- Menampilkan Antrian Desain --}}
                <table id="tableAntrianDesain" class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket Order</th>
                            <th>Judul Project</th>
                            <th>Sales</th>
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
                                <th>Judul Project</th>
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
            <div class="tab-pane fade" id="revisi-desain" role="tabpanel" aria-labelledby="revisi-desain-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Revisi Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableRevisiDesain" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket Order</th>
                                <th>Sales</th>
                                <th>Judul Desain</th>
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
    @includeIf('page.antrian-desain.modal.modal-detail-desain')
    @includeIf('page.antrian-desain.modal.modal-upload')
    @includeIf('page.antrian-desain.modal.modal-upload-revisi')
</div>
@endsection


@section('script')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        function showDetailDesain(id){
            $('#detailWorking').modal('show');

            $.ajax({
                url: "/order/"+ id +"/show",
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    //membersihkan isi dari seluruh element modal
                    $('#ticketDetail').empty();
                    $('#createdAtWorking').empty();
                    $('#lastUpdateWorking').empty();
                    $('#namaSalesWorking').empty();
                    $('#judulDesainWorking').empty();
                    $('#jenisProdukWorking').empty();
                    $('#keteranganWorking').empty();
                    $('#refgambar').empty();

                    var dibuat = new Date(response.data.created_at);
                    var tanggalDibuat = dibuat.getFullYear() + '-' + (dibuat.getMonth() + 1) + '-' + dibuat.getDate();
                    var waktuDibuat = dibuat.getHours() + ":" + dibuat.getMinutes() + ":" + dibuat.getSeconds();

                    var perbarui = new Date(response.data.updated_at);
                    var tanggalUpdate = perbarui.getFullYear() + '-' + (perbarui.getMonth() + 1) + '-' + perbarui.getDate();
                    var waktuUpdate = perbarui.getHours() + ":" + perbarui.getMinutes() + ":" + perbarui.getSeconds();

                    //format tanggal dan jam
                    $("#createdAtWorking").val(tanggalDibuat + " " + waktuDibuat);
                    $("#lastUpdateWorking").val(tanggalUpdate + " " + waktuUpdate);

                    $('#ticketDetail').text(response.data.ticket_order);
                    $('#namaSalesWorking').val(response.data.sales.sales_name);
                    $('#judulDesainWorking').val(response.data.title);
                    $('#jenisProdukWorking').val(response.jenisProduk);
                    $('#keteranganWorking').val(response.data.description);
                    $('#refgambar').attr('src', "{{ asset('storage/ref-desain') }}/" + response.data.desain);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi Kesalahan!'
                    });
                }
            });
        }

        function showDesainer(id) {
            $('#modalBagiDesain').modal('show');
            $('#ticket_order').val(id);
        }

        function showUploadCetak(id) {
            $('#modalUpload').modal('show');
            $('#modalUpload #idOrder').val(id);
        }

        function deleteOrder(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/order/hapus/"+ id,
                        type: "DELETE",
                        dataType: "JSON",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            $('#tableAntrianDesain').DataTable().ajax.reload();
                            $('#tableAntrianDikerjakan').DataTable().ajax.reload();
                            $('#tableAntrianSelesai').DataTable().ajax.reload();
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.success
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi Kesalahan!'
                            });
                        }
                    });
                }
            });
        }

        function pilihDesainer(id) {
            //disable button btnDesainer
            $('.btnDesainer').prop('disabled', true);

            var tiket = $('#ticket_order').val();

            //ajax untuk mengirim data ke controller
            $.ajax({
                url: "{{ route('simpanDesainer') }}",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: "{{ csrf_token() }}",
                    desainerID: id,
                    ticketOrder: tiket
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.success
                    });

                    $('#modalBagiDesain').modal('hide');
                    $('#tableAntrianDesain').DataTable().ajax.reload();
                    $('#tableAntrianDikerjakan').DataTable().ajax.reload();
                    $('#tableAntrianSelesai').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi Kesalahan!'
                    });

                    //enable button btnDesainer
                    $('.btnDesainer').prop('disabled', false);
                }
            });
        }

        function uploadRevisi(id) {
            $('#modalUploadRevisi').modal('show');
            $('#ticketModalRevisi').val(id);
            $('.idOrder').val(id);
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
                {data: 'sales', name: 'sales'},
                {data: 'title', name: 'title'},
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
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'},
            ],
        });

        $("#tableRevisiDesain").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.revisi') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'ticket_order', name: 'ticket_order'},
                {data: 'sales', name: 'sales'},
                {data: 'title', name: 'title'},
                {data: 'action', name: 'action'},
            ],
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
                autoProcessQueue: false,
                init:function() {
                    var myDropzone = this;
                    //saat submit di klik, maka jalankan fungsi ini
                    $('#submitCetak').click(function(){
                        myDropzone.processQueue();
                    });

                    this.on('complete', function(){
                        if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                            var _this = this;
                            _this.removeAllFiles();
                        }
                        $('#modalUpload').modal('hide');
                        $('#tableAntrianDikerjakan').DataTable().ajax.reload();
                        $('#tableAntrianSelesai').DataTable().ajax.reload();
                    });
                }
        };

        Dropzone.options.myDropzoneRevisi = { // camelized version of the `id`
            paramName: "fileRevisi", // The name that will be used to transfer the file
            clickable: true,
            acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
            dictInvalidFileType: "Type file ini tidak dizinkan",
            addRemoveLinks: true,
            dictRemoveFile: "Hapus file",
            maxFileSize: 50,
            autoProcessQueue: false,
            init:function() {
                var myDropzone = this;
                //saat submitRevisi di klik, maka jalankan fungsi ini
                $('#submitRevisi').click(function(){
                    myDropzone.processQueue();
                });

                this.on('complete', function(){
                    if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        _this.removeAllFiles();
                    }
                    $('#modalUploadRevisi').modal('hide');
                    location.reload();
                });
            }
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

        // saat form upload revisi di submit, maka tampilkan loader dan disable button submitnya
        $('#submitRevisiDesain').click(function() {
            $(this).addClass('disabled');
            $('#loader').show();
        });

        $('#linkFileUpload').on('keyup', function() {
            if($(this).val() != '') {
                $('.submitLink').removeClass('disabled');
            } else {
                $('.submitLink').addClass('disabled');
            }
        });

        $('#linkFileRevisi').on('keyup', function() {
            if($(this).val() != '') {
                $('.submitLink').removeClass('disabled');
            } else {
                $('.submitLink').addClass('disabled');
            }
        });
    });
</script>

@if(session('success'))
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
                title: '{{ session('success') }}'
                });
        });
    </script>
@endif

@if(session('error'))
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
