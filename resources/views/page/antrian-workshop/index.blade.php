@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Stempel')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
</div>
@endif

{{-- Alert success-update --}}
@if(session('success-update'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success-update') }}
</div>
@endif

{{-- Alert successToAntrian --}}
@if(session('successToAntrian'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('successToAntrian') }}
</div>
@endif

{{-- Alert success-dokumentasi --}}
@if(session('success-dokumentasi'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success-dokumentasi') }}
</div>
@endif

@if(session('success-progress'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success-progress') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
</div>
@endif

{{-- Alert error --}}

{{-- Content Table --}}
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
            <form id="filterByCategory" action="{{ route('antrian.filterByCategory') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <label for="kategori">Kategori Pekerjaan</label>
                    @if(isset($filtered))
                    <select id="kategori" name="kategori" class="custom-select rounded-1" disabled>
                        <option value="Semua">Semua</option>
                        <option value="1" {{ $filtered == "1" ? "selected" : "" }}>Stempel</option>
                        <option value="3" {{ $filtered == "3" ? "selected" : "" }}>Advertising</option>
                        <option value="2" {{ $filtered == "2" ? "selected" : "" }}>Non Stempel</option>
                        <option value="4" {{ $filtered == "4" ? "selected" : "" }}>Digital Printing</option>
                    </select>
                    @else
                    <select id="kategori" name="kategori" class="custom-select rounded-1">
                        <option value="Semua">Semua</option>
                        <option value="1">Stempel</option>
                        <option value="3">Advertising</option>
                        <option value="2">Non Stempel</option>
                        <option value="4">Digital Printing</option>
                    </select>
                    @endif
            </div>
            <div class="col-md-2 align-self-end">
                @if(isset($filtered))
                <a href="{{ route('antrian.index') }}" class="btn btn-danger mt-1">Reset</a>
                @else
                <button type="submit" class="btn btn-primary mt-1">Filter</button>
                @endif
            </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Dikerjakan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Selesai</a>
                    </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrian" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ticket Order</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">Deadline</th>
                                            <th scope="col">File Desain</th>
                                            <th scope="col">Desainer</th>
                                            <th scope="col">Operator</th>
                                            <th scope="col">Finishing</th>
                                            <th scope="col">QC</th>
                                            <th scope="col">Tempat</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                                <!-- /.card -->
                                @if(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                                    <p class="text-muted font-italic mt-2 text-sm">*Tombol <span class="text-danger">"Upload Progress"</span> akan aktif diatas jam 15.00</p>
                                @endif
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrianSelesai" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal Order</th>
                                            <th scope="col">Ticket Order</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">Keyword Project</th>
                                            <th scope="col">Desain</th>
                                            <th scope="col">Desainer</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                                </table>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
    <script>
        function deleteAntrian(id){
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('antrian.destroy', "+id+") }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "ticket_order": id
                        },
                        success: function(response){
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    ajax.reload();
                                }
                            })
                        },
                        error: function(response){
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            })
                        }
                    });
                }
            })
        }

        $(document).ready(function() {
            var kategori = $('#kategori').val();

            $('.maskRupiah').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});
            
            $("#dataAntrian").DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: kategori == 'Semua' ? "{{ route('antrian.indexData') }}" : "{{ route('antrian.indexData') }}?kategori=" + kategori,
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'ticket_order', name: 'ticket_order'},
                    {data: 'sales', name: 'sales'},
                    {data: 'customer', name: 'customer'},
                    {data: 'endJob', name: 'endJob'},
                    {data: 'fileDesain', name: 'fileDesain'},
                    {data: 'desainer', name: 'desainer'},
                    {data: 'operator', name: 'operator'},
                    {data: 'finishing', name: 'finishing'},
                    {data: 'qc', name: 'qc'},
                    {data: 'tempat', name: 'tempat'},
                    {data: 'action', name: 'action'},
                ],
            });

            $("#dataAntrianSelesai").DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: kategori == 'Semua' ? "{{ route('antrian.indexSelesai') }}" : "{{ route('antrian.indexSelesai') }}?kategori=" + kategori,
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'tanggal_order', name: 'tanggal_order'},
                    {data: 'ticket_order', name: 'ticket_order'},
                    {data: 'sales', name: 'sales'},
                    {data: 'customer', name: 'customer'},
                    {data: 'keyword', name: 'keyword'},
                    {data: 'desain', name: 'desain'},
                    {data: 'desainer', name: 'desainer'},
                    {data: 'action', name: 'action'},
                ],
            });

            //reload ajax datatable setiap 10 menit
            setInterval(function(){
                $('#dataAntrian').DataTable().ajax.reload();
                $('#dataAntrianSelesai').DataTable().ajax.reload();
            }, 600000);

            //Menutup modal saat modal lainnya dibuka
            $('.modal').on('show.bs.modal', function (e) {
                $('.modal').not($(this)).each(function () {
                    $(this).modal('hide');
                });
            });

            $('.metodePembayaran').on('change', function(){
                var id = $(this).attr('id').split('metodePembayaran')[1];
                var metode = $(this).val();
                if(metode == 'Tunai' || metode == 'Cash'){
                    $('.filePelunasan').hide();
                    $('#filePelunasan'+id).removeAttr('required');
                }
                else{
                    $('.filePelunasan').show();
                    $('#filePelunasan'+id).attr('required', true);
                }
            });

            $('.btnModalPelunasan').on('click', function(){
                //ambil id dari tombol submitUnggahBayar
                var id = $(this).attr('id').split('btnModalPelunasan')[1];

                $('#jumlahPembayaran'+id).on('keyup', function(){
                //ambil value dari jumlahPembayaran
                var jumlah = $('#jumlahPembayaran'+id).val().replace(/Rp\s|\.+/g, '');
                //ambil value dari sisaPembayaran
                var sisa = $('#sisaPembayaran'+id).val().replace(/Rp\s|\.+/g, '');
                //inisialisasi variabel keterangan
                var keterangan = $('#keterangan'+id);
                //inisialisasi variabel submit
                var submit = $('#submitUnggahBayar'+id);
                //jika jumlah pembayaran melebihi sisa pembayaran
                if(parseInt(jumlah) > parseInt(sisa)){
                    //tampilkan keterangan
                    keterangan.html('<span class="text-danger">Jumlah pembayaran melebihi sisa pembayaran</span>');
                    //tombol submit disabled
                    submit.attr('disabled', true);
                }
                else{
                    //sembunyikan keterangan
                    keterangan.html('');
                    //tombol submit enabled
                    submit.attr('disabled', false);
                }
                });
            });
        });
    </script>
@endsection
