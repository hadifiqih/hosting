@extends('layouts.app')

@section('title', 'Antrian Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Detail Brief Desain</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="judul">File Referensi</label><br>
                <img width="300px" height="300px" src="{{ asset('storage/ref-desain/' . $design->ref_desain) }}" class="img-fluid" alt="Referensi Desain">
            </div>
            <div class="form-group">
                <label for="judul">Judul Desain</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ $design->judul }}" readonly>
            </div>
            <div class="form-group">
                <label for="judul">Jenis Produk</label>
                <input type="text" class="form-control" id="job" name="job" value="{{ $design->job->job_name }}" readonly>
            </div>
            <div class="form-group">
                <label for="judul">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" readonly>{{ $design->note }}</textarea>
            </div>
            <div class="form-group">
                <label for="judul">Prioritas</label>
                <input type="text" class="form-control" id="prioritas" name="prioritas" value="{{ $design->prioritas ==  1 ? 'PRIORITAS' : 'TIDAK' }}" readonly>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pilih Desainer</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- table nama desainer --}}
                <table class="table table-bordered table-hover" id="tableNamaDesainer">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Desainer</th>
                            <th>Jumlah Antrian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $designer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $designer->name }}</td>
                            <td>{{ $designer->ambilJumlahAntrian($designer->id) }}</td>
                            <td>
                                <button onclick="pilihDesainer({{ $designer->id }})" class="btn btn-sm btn-primary">Tugaskan</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script>
    function pilihDesainer(userId) {
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
        let queueId = {{ $design->id }};

        //Swal.fire confirm
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Desainer yang dipilih akan menerima antrian desain ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tugaskan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('design.pilihDesainer') }}",
                    type: "POST",
                    data: {
                        _token: csrf_token,
                        user_id: userId,
                        queue_id: queueId
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(() => {
                                window.location.href = "{{ route('design.indexDesain') }}";
                            }, 1500);
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endsection