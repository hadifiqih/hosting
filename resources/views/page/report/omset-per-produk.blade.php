@extends('layouts.app')

@section('title', 'Omset Per Produk')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Omset Per Produk')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Omset Per Produk</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-borderless table-responsive" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Stempel</th>
                                <th>Non Stempel</th>
                                <th>Advertising</th>
                                <th>Digital Printing</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dateRange as $date)
                            <tr>
                                <td>{{ $date }}</td>
                                @php
                                    $omsetStempel = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                        $query->where('job_type', 'Stempel');
                                                                        })->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetNonStempel = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                      $query->where('job_type', 'Non Stempel');
                                                                      })->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetAdvertising = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                    $query->where('job_type', 'Advertising');
                                                                    })->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $omsetDigitalPrinting = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                        $query->where('job_type', 'Digital Printing');
                                                                        })->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    $total = $omsetStempel + $omsetNonStempel + $omsetAdvertising + $omsetDigitalPrinting;
                                @endphp
                                <td>Rp{{ number_format($omsetStempel, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetNonStempel, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetAdvertising, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($omsetDigitalPrinting, 0, ',', '.') }}</td>
                                <td class="bg-warning">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                    @php
                                        $omsetStempel = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                            $query->where('job_type', 'Stempel');
                                                                            })->sum('omset');
                                        $omsetNonStempel = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                          $query->where('job_type', 'Non Stempel');
                                                                          })->sum('omset');
                                        $omsetAdvertising = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                        $query->where('job_type', 'Advertising');
                                                                        })->sum('omset');
                                        $omsetDigitalPrinting = \App\Models\Antrian::whereHas('job', function ($query) {
                                                                            $query->where('job_type', 'Digital Printing');
                                                                            })->sum('omset');
                                        $total = $omsetStempel + $omsetNonStempel + $omsetAdvertising + $omsetDigitalPrinting;
                                    @endphp
                                <th>Rp{{ number_format($omsetStempel, 0, ',', '.') }}</th>
                                <th>Rp{{ number_format($omsetNonStempel, 0, ',', '.') }}</th>
                                <th>Rp{{ number_format($omsetAdvertising, 0, ',', '.') }}</th>
                                <th>Rp{{ number_format($omsetDigitalPrinting, 0, ',', '.') }}</th>
                                <th>Rp{{ number_format($total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

