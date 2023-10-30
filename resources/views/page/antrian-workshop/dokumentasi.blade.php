@extends('layouts.app')

@section('title', 'Dokumentasi')

@section('username', Auth::user()->name)

@section('page', 'Dokumentasi')

@section('breadcrumb', 'Upload Dokumentasi')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Dokumentasi #{{ $antrian->ticket_order }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('antrian.storeDokumentasi') }}" class="dropzone" id="my-dropzone" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="jobType" value="{{ $antrian->job_id }}">
                        <input type="hidden" name="idAntrian" value="{{ $antrian->id }}">
                    </form>

                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="{{ route('antrian.index') }}" class="btn btn-secondary">Kembali</a>
                            <a href="{{ route('antrian.markSelesai', $antrian->id) }}" class="btn btn-primary float-right">Upload</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
   //Script Dropzone
   Dropzone.options.myDropzone = {
            paramName: 'files',
            acceptedFiles: 'image/*, video/*',
            maxFilesize: 30,
            uploadMultiple: true,
            //mengirim token ke server
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        };
</script>
@endsection
