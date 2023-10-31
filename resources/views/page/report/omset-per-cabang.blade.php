@extends('layouts.app')

@section('title', 'Omset Per Cabang')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Omset Per Cabang')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Omset Per Cabang</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-borderless table-responsive" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th class="bg-danger">Surabaya</th>
                                <th class="bg-danger">Malang</th>
                                <th class="bg-danger">Kediri</th>
                                <th class="bg-danger">Sidoarjo</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dateRange as $date)
                            <tr>
                                <td>{{ $date }}</td>
                                @php
                                    $omsetSurabaya = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                    $query->where('cabang', 'SBY');
                                                                                    })
                                                                                    ->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetMalang = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                    $query->where('cabang', 'MLG');
                                                                                    })
                                                                                    ->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetKediri = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                    $query->where('cabang', 'KDR');
                                                                                    })
                                                                                    ->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetSidoarjo = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                    $query->where('cabang', 'SDJ');
                                                                                    })
                                                                                    ->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                @endphp
                                <td>Rp{{ number_format($omsetSurabaya, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetMalang, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetKediri, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetSidoarjo, 0, ',', '.') }}</td>
                                @php
                                    $total = $omsetSurabaya + $omsetMalang + $omsetKediri + $omsetSidoarjo;
                                @endphp
                                <td class="bg-warning">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th>Total</th>
                            @php
                                $omsetSurabaya = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                $query->where('cabang', 'SBY');
                                                                                })
                                                                                ->sum('omset');
                                $omsetMalang = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                $query->where('cabang', 'MLG');
                                                                                })
                                                                                ->sum('omset');
                                $omsetKediri = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                $query->where('cabang', 'KDR');
                                                                                })
                                                                                ->sum('omset');
                                $omsetSidoarjo = \App\Models\Antrian::whereHas('sales', function ($query) {
                                                                                $query->where('cabang', 'SDJ');
                                                                                })
                                                                                ->sum('omset');
                            @endphp
                            <th class="bg-warning">Rp{{ number_format($omsetSurabaya, 0, ',', '.') }}</th>
                            <th class="bg-warning">Rp{{ number_format($omsetMalang, 0, ',', '.') }}</th>
                            <th class="bg-warning">Rp{{ number_format($omsetKediri, 0, ',', '.') }}</th>
                            <th class="bg-warning">Rp{{ number_format($omsetSidoarjo, 0, ',', '.') }}</th>
                            @php
                                $total = $omsetSurabaya + $omsetMalang + $omsetKediri + $omsetSidoarjo;
                            @endphp
                            <th class="bg-danger">Rp{{ number_format($total, 0, ',', '.') }}</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

