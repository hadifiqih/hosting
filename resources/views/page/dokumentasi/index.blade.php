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
        <div class="row">
            <di class="col-12">
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
                                <h3 class="card-title">Antrian Dokumentasi</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrian" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ticket Order</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Desain</th>
                                            <th scope="col">Nama Produk</th>
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
        $(document).ready(function() {
            $('.maskRupiah').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});
            
            $("#dataAntrian").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('documentation.indexJson') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'ticket_order', name: 'ticket_order'},
                    {data: 'sales', name: 'sales'},
                    {data: 'accdesain', name: 'accdesain', orderable: false, searchable: false},
                    {data: 'nama_produk', name: 'nama_produk'},
                    {data: 'action', name: 'action'}
                ]
            });

            //reload ajax datatable setiap 10 menit
            setInterval(function(){
                $('#dataAntrian').DataTable().ajax.reload();
            }, 600000);
        });
    </script>
@endsection
