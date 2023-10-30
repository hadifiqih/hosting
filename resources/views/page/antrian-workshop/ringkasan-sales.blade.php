@extends('layouts.app')

@section('title', 'Ringkasan Sales | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Ringkasan Sales')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-3">
                <h3 class="card-title text-bold">Ringkasan Sales</h3>
            </div>
            <div class="col-md-9">
                <span class="text-muted d-flex justify-content-end">Tanggal : {{ substr($date,0,10) }}</span>
            </div>
        </div>


    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-warning"><i class="fas fa-money-bill"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Omset</span>
                      <span class="info-box-number">Rp {{ number_format($totalOmset, 0, ',' , '.') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
            </div>
            <div class="col-md-4"></div>
            {{-- Pilih tanggal --}}
            <div class="col-md-5">
                <form action="{{ route('report.salesByDate')}}" method="POST">
                    @csrf
                    <div class="form-group d-flex justify-content-end">
                        <label for="tanggal" class="mr-5">Pilih Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                        {{-- tombol cari inline dengan input tanggal --}}
                        <button type="submit" class="btn btn-sm btn-primary ml-2 mb-3" id="cariTanggal">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <table id="ringkasanSales" class="table table-responsive table-bordered table-hover" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Produk</th>
                    <th>Total Omset</th>
                    <th>Spesifikasi</th>
                    <th>Status Pengerjaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($antrians as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->customer->nama }}</td>
                    <td>{{ $item->job->job_name }}</td>
                    <td>Rp {{ number_format($item->omset, 0, ',' , '.') }}</td>
                    <td>{{ $item->note }}</td>
                    <td>
                        @if($item->status == 1 && $item->end_job == null)
                        <span class="badge badge-primary">Belum Dikerjakan</span>
                        @elseif($item->status == 1 && $item->end_job != null)
                        <span class="badge badge-warning">Sedang Dikerjakan</span>
                        @elseif($item->status == 2)
                        <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#ringkasanSales').DataTable();
    });
</script>
@endsection
