@extends('layouts.app')

@section('title', 'Upload File Cetak | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Detail Desain')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Upload File Cetak</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('design.simpanFile', $design->id) }}" method="post" enctype="multipart/form-data" id="simpanFile">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="aktifLink">
                                <label class="custom-control-label" for="aktifLink">File Berukuran Besar?</label>
                            </div>
                        </div>

                        <div class="form-group" style="display: none">
                            <label for="note">Link File</label>
                            <input type="text" class="form-control" id="linkFile" name="linkFile" value="" required>
                        </div>

                        <div id="inputFileForm" class="form-group">
                            <label for="fileCetak">File Cetak</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="fileCetak" class="custom-file-input" id="fileCetak" accept="image/jpg, image/jpeg, image/png, application/pdf, .cdr" required>
                                    <label class="custom-file-label" for="fileCetak">Pilih file</label>
                                </div>
                            </div>
                        </div>
                        <button id="submitUnggahCetak" type="submit" class="btn btn-sm btn-outline-primary">Unggah</button>
                    </form>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6><strong><i class="fas fa-info-circle"></i> Informasi</strong></h6>
                            <ul>
                                <li>File cetak yang diunggah harus berformat .pdf, atau .cdr</li>
                                <li>Untuk file cetak .jpg, .png, minimal resolusi : 300DPI.</li>
                                <li>Minimal resolusi untuk banner : 150DPI.</li>
                                <li>Ukuran file maksimal 50 MB.</li>
                                <li>Jika ukuran file > 50MB, silahkan upload di Google Drive, lalu bagikan, dan tempel link file pada form diatas.</li>
                                <li>Pastikan font sudah di <strong class="text-danger">Convert To Curves</strong> untuk menghindari missing font.</li>
                                <li>Jika ada kesalahan cetak yang berasal dari file cetak, biaya kesalahan cetak akan dibebankan kepada desainer.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        //jika aktif link maka tampilkan form link file dan sembunyikan upload file dan sebaliknya
        $('#aktifLink').on('click', function() {
            if ($('#aktifLink').is(':checked')) {
                $('#linkFile').attr('required', true);
                $('#fileCetak').removeAttr('required');
                $('#fileCetak').parent().parent().hide();
                $('#linkFile').parent().show();
                $('#inputFileForm').hide();
                $('#linkFile').val('');
                $('#fileCetak').val('');
                $('.custom-file-label').text('Pilih file');
            } else {
                $('#linkFile').removeAttr('required');
                $('#fileCetak').attr('required', true);
                $('#fileCetak').parent().parent().show();
                $('#linkFile').parent().hide();
                $('#inputFileForm').show();
                $('#linkFile').val('');
                $('#fileCetak').val('');
                $('.custom-file-label').text('Pilih file');
            }
        });

        //jika file kosong saat menekan button submit maka munculkan alert
        $('#submitUnggahCetak').on('click', function(e) {
            if ($('#fileCetak').val() == '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'File cetak tidak boleh kosong !'
                });
            }
        });

        //sweet alert konfirmasi upload file cetak
        $('#submitUnggahCetak').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "File cetak yang diupload sudah benar ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Unggah !'
            }).then((result) => {
                if (result.isConfirmed) {
                    //proses submit form
                    $('#simpanFile').submit();  
                }
            });
        });
    });
</script>
@endsection