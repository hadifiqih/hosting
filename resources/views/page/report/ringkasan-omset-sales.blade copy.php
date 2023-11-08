@extends('layouts.app')

@section('title', 'Omset Per Cabang')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Ringkasan Penjualan')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Filter</h3>
            </div>
            <div class="card-body">
                    <form action="{{ route('ringkasan.omsetSalesFilter') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    @if($isFilter == true)
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $date }}" disabled>
                                    @else
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sales">Sales</label>
                                    @if($isFilter == false)
                                    <select name="sales" id="sales" class="form-control" required>
                                        <option value="">-- Pilih Sales --</option>
                                        @foreach ($listSales as $sales)
                                            <option value="{{ $sales->id }}">{{ $sales->sales_name }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" name="sales" id="sales" class="form-control" value="{{ $salesName->sales_name }}" disabled>
                                    @endif
                                </div>
                                @if($isFilter == false)
                                <button type="submit" class="btn btn-primary float-right px-3">Filter</button>
                                @else
                                <a href="{{ route('ringkasan.omsetSales') }}" class="btn btn-danger float-right px-3">Reset</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Ringkasan Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <table id="tableRingkasan" class="table table-borderless table-hover" style="width: 100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tanggal Order</th>
                                    <th>Ticket Order</th>
                                    <th>Sales</th>
                                    <th>Pelanggan</th>
                                    <th>Jenis Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Produk</th>
                                    <th>Deadline</th>
                                    <th>File Desain</th>
                                    <th>Pelunasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($antrians as $antrian)
                                <tr>
                                    <td>{{ $antrian->created_at }}</td>
                                    <td>{{ $antrian->ticket_order }} <button class="btn btn-primary btn-sm" data-target="#modalDetail{{ $antrian->id }}" data-toggle="modal" data-ticket="{{ $antrian->ticket_order }}">Detail</button></td>
                                    <td>{{ $antrian->sales->sales_name }}</td>
                                    <td>{{ $antrian->customer->nama }}</td>
                                    <td>{{ $antrian->job->job_name }}</td>
                                    <td>{{ $antrian->qty }}</td>
                                    <td>Rp{{ number_format($antrian->harga_produk, 0, ',', '.') }}</td>
                                    <td>{{ $antrian->end_job }}</td>
                                    <td>{{ $antrian->order->file_cetak }}</td>
                                    <td>
                                        @php
                                            $pelunasan = \App\Models\Payment::where('ticket_order', $antrian->ticket_order)->latest()->first();
                                        @endphp
                                        @if ($pelunasan->payment_status == 'Lunas')
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalTampilBP{{ $antrian->id }}"><i class="fas fa-check-circle"></i> Lihat</button>
                                        @else
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalTampilBP{{ $antrian->id }}"> Belum Lunas</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @includeIf('page.report.modal-detail-antrian')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let table;

        $(function() {
            $('#tableRingkasan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                ajax: {
                    url: "{{ route('ringkasan.omsetSales') }}",
                },
                columns: [
                    {data : 'created_at', name: 'Tanggal Order'},
                    {data : 'ticket_order', name: 'Ticket Order'},
                    {data : 'sales', name: 'Sales'},
                    {data : 'customer', name: 'Pelanggan'},
                    {data : 'job', name: 'Jenis Produk'},
                    {data : 'qty', name: 'Qty'},
                    {data : 'harga_produk', name: 'Harga Produk'},
                    {data : 'end_job', name: 'Deadline'},
                    {data : 'file_cetak', name: 'File Desain'},
                    {data : 'pelunasan', name: 'Pelunasan'},
                ]
            });
        });

        //Menutup modal saat modal lainnya dibuka
        $('.modal').on('show.bs.modal', function (e) {
                $('.modal').not($(this)).each(function () {
                    $(this).modal('hide');
                });
            });
    </script>
@endsection
