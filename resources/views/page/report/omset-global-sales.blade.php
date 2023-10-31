@extends('layouts.app')

@section('title', 'Omset Global Sales')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Omset Global Sales')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Omset Global Sales</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-borderless table-responsive">
                        <thead>
                            <tr>
                                <th>Periode</th>

                                @php
                                    $sales = \App\Models\Sales::all();
                                @endphp
                                @foreach ($sales as $item)
                                    <th class="bg-danger">{{ $item->sales_name }}</th>
                                @endforeach

                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dateRange as $date)
                            <tr>
                                <td>{{ $date }}</td>
                                @foreach ($sales as $item)
                                    @php
                                        $omset = \App\Models\Antrian::where('sales_id', $item->id)->where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                    @endphp
                                    <td>Rp{{ number_format($omset, 0, ',', '.') }}</td>
                                @endforeach
                                @php
                                    $total = \App\Models\Antrian::where('created_at', 'like' , '%'.$date.'%')->sum('omset');
                                @endphp
                                <td class="bg-warning">Rp{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                @foreach ($sales as $item)
                                    @php
                                        $omset = \App\Models\Antrian::where('sales_id', $item->id)->sum('omset');
                                    @endphp
                                    <th class="bg-warning">Rp{{ number_format($omset, 0, ',', '.') }}</th>
                                @endforeach
                                @php
                                    $total = \App\Models\Antrian::sum('omset');
                                @endphp
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

