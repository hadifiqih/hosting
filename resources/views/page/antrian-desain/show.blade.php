@extends('layouts.app')

@section('title', 'Detail Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Detail Desain')

@section('content')
<style>
    .note {
        white-space: pre-line;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Desain #{{ $order->ticket_order }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                                <h6><strong><i class="fas fa-hashtag"></i> Judul Project</strong></h6>
                                <p>{{ $order->title }}</p>
                        </div>
                        <div class="col-md-6">
                                <h6><strong><i class="fas fa-user"></i> Sales</strong></h6>
                                <p>{{ $order->sales->sales_name }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6><strong><i class="fas fa-list pr-1"></i> Daftar Desain</strong></h6>
                        </div>
                    </div>
                    <div class="row mt-2">
                        @foreach($barang as $b)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('storage/' . $b->refdesain->path) }}" alt="Desain" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $b->job->job_name }}</h5>
                                    <p id="note" class="card-text">{{ $b->note }}</p>
                                    <p class="card-text"><small class="text-muted">Qty Produk : {{ $b->qty }}</small></p>
                                    <div class="btn-group">
                                    @if($b->desainer_id != null)
                                        @if($b->desainer_id == Auth::user()->id)
                                            <a href="{{ route('barang.uploadCetak', $b->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Upload File Cetak</a>
                                        @else
                                            <button class="btn btn-sm btn-secondary disabled"><i class="fas fa-upload"></i> Upload File Cetak</button>
                                        @endif

                                        @if(Auth::user()->role_id == 5)
                                            <a href="javascript:void(0)" onclick="sendIdBarangForChange({{ $b->id }})" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Ganti Desainer</a>
                                        @endif
                                    @else
                                        <button onclick="pilihDesainer({{ $b->id }})" class="btn btn-sm btn-warning"><i class="fas fa-pen-nib"></i> Pilih Desainer</button>
                                    @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p><strong>Desainer : </strong><span>{{ !isset($b->desainer->name) ? '-' : $b->desainer->name }}</span></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('page.antrian-desain.modal.modal-bagiDesain')
@include('page.antrian-desain.modal.modal-editDesain')
@endsection

@section('script')
<script>
    function pilihDesainer(id) {
        $('#modalBagiDesain').modal('show');
        $('#idBarang').val(id);
    }

    function sendIdBarangForChange(id) {
        $('#modalEditDesainer').modal('show');
        $('#idBarang').val(id);
    }

    function tugaskanDesainer(id) {
        $.ajax({
            url: "{{ route('barang.tugaskanDesainer') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "barang_id": $('#idBarang').val(),
                "desainer_id": id,
            },
            success: function(response) {
                $('#modalBagiDesain').modal('hide');
                //swal success
                Swal.fire({
                    title: "Berhasil!",
                    text: "Desainer berhasil ditugaskan",
                    icon: "success",
                });

                setInterval(() => {
                    location.reload();
                }, 1500);
            }
        });
    }

    function ubahDesainer(id) {
        $.ajax({
            url: "{{ route('ubahDesainer') }}",
            type: "POST",
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'), // Ambil token CSRF dari meta tag
                "barangId": $('#idBarang').val(),
                "desainer": id,
            },
            success: function(data) {
                $('#modalEditDesainer').modal('hide');
                //swal success
                Swal.fire({
                    title: "Berhasil!",
                    text: "Desainer berhasil diubah",
                    icon: "success",
                });

                setInterval(() => {
                    location.reload();
                }, 1500);

            },
            error: function(xhr, status, error) {
                // Tangani kesalahan di sini, misalnya menampilkan pesan kesalahan kepada pengguna
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function() {
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

        $("#gantiDesainer").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('edit.desainer') }}",
                //data tambahan
                data: {
                    barang: $('#idBarang').val(),
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nama_desainer', name: 'nama_desainer'},
                {data: 'jumlah_desain', name: 'jumlah_desain'},
                {data: 'action', name: 'action'},
            ],
        });
    });
</script>
@endsection
