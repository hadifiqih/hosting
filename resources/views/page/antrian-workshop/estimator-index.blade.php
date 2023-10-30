@extends('layouts.app')

@section('title', 'Antrian Workshop')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Workshop')

@section('content')
{{-- jika ada error --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- Form Upload Progress Produksi (Foto atau Video) dengan keterangan --}}
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
            <form id="filterByCategory" action="{{ route('estimator.filter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="kategori">Kategori Pekerjaan</label>
                    @if(isset($filtered))
                    <select id="kategori" name="kategori" class="custom-select rounded-1" disabled>
                        <option value="Semua">Semua</option>
                        <option value="Stempel" {{ $filtered == "Stempel" ? "selected" : "" }}>Stempel</option>
                        <option value="Advertising" {{ $filtered == "Advertising" ? "selected" : "" }}>Advertising</option>
                        <option value="Non Stempel" {{ $filtered == "Non Stempel" ? "selected" : "" }}>Non Stempel</option>
                        <option value="Digital Printing" {{ $filtered == "Digital Printing" ? "selected" : "" }}>Digital Printing</option>
                    </select>
                    @else
                    <select id="kategori" name="kategori" class="custom-select rounded-1">
                        <option value="Semua">Semua</option>
                        <option value="Stempel">Stempel</option>
                        <option value="Advertising">Advertising</option>
                        <option value="Non Stempel">Non Stempel</option>
                        <option value="Digital Printing">Digital Printing</option>
                    </select>
                    @endif
            </div>
            <div class="col-md-2 align-self-end">
                @if(isset($filtered))
                <a href="{{ route('estimator.index') }}" class="btn btn-danger mt-1">Reset</a>
                @else
                <button type="submit" class="btn btn-primary mt-1">Filter</button>
                @endif
            </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">File Baru Masuk</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Progress Produksi</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai Produksi</a>
                    </li>
                </ul>

                <div class="tab-content" id="custom-content-below-tabContent">

                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">File Baru Masuk</h2>
                            </div>
                            <div class="card-body">
                                <table id="antrianBaruMasuk" class="table table-responsive table-bordered table-striped " style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Ticket Order</th>
                                            <th>Sales</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Desainer</th>
                                            <th>File Desain</th>
                                            <th>File Siap Produksi</th>
                                            <th>Tandai File Aman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fileBaruMasuk as $antrian)
                                            <tr>
                                                <td>{{ $antrian->ticket_order }}</td>
                                                <td>{{ $antrian->sales->sales_name }}</td>
                                                <td>{{ $antrian->job->job_name }} <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detailMasuk{{ $antrian->id }}"><i class="fas fa-info-circle"></i></button></td>

                                                <div class="modal fade" id="detailMasuk{{ $antrian->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title">Detail #{{ $antrian->ticket_order }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="form-label">Sales</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->sales->sales_name }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Nama Pelanggan
                                                                    @if($antrian->customer->frekuensi_order == 0)
                                                                    <span class="badge badge-danger">New Leads</span>
                                                                    @elseif($antrian->customer->frekuensi_order == 1)
                                                                    <span class="badge badge-warning">New Customer</span>
                                                                    @elseif($antrian->customer->frekuensi_order >= 2)
                                                                    <span class="badge badge-success">Repeat Order</span>
                                                                    @endif
                                                                </label>
                                                                <input type="text" class="form-control" value="{{ $antrian->customer->nama }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Nama Produk</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->job->job_name }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Jumlah Produk (Qty)</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->qty }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Keterangan / Spesifikasi Produk</label>
                                                                <textarea class="form-control" rows="6" readonly>{{ $antrian->note }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Deadline</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->end_job }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Desainer</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->order->employee->name }}" readonly>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="form-label">Tempat : </label>
                                                                    @php
                                                                        $tempat = explode(',', $antrian->working_at);
                                                                        foreach ($tempat as $item) {
                                                                                if($item == 'Surabaya'){
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                    }
                                                                                }elseif ($item == 'Kediri') {
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                    }
                                                                                }elseif ($item == 'Malang') {
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                    }
                                                                                }
                                                                            }
                                                                    @endphp
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-4 rounded-2">
                                                                <label>Operator</label>
                                                                <p>
                                                                @if($antrian->operator_id)
                                                                    @php
                                                                        $operatorId = explode(',', $antrian->operator_id);
                                                                        foreach ($operatorId as $item) {
                                                                            if($item == 'rekanan'){
                                                                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                            }
                                                                            else{
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($operatorId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 rounded-2">
                                                                <label>Finishing</label>
                                                                <p>
                                                                @if($antrian->finisher_id)
                                                                    @php
                                                                        $finisherId = explode(',', $antrian->finisher_id);
                                                                        foreach ($finisherId as $item) {
                                                                            if($item == 'rekanan'){
                                                                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                            }
                                                                            else{
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($finisherId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 rounded-2">
                                                                <label>Pengawas / QC</label>
                                                                <p>
                                                                @if($antrian->qc_id)
                                                                    @php
                                                                        $qcId = explode(',', $antrian->qc_id);
                                                                        foreach ($qcId as $item) {
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($qcId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                    @endphp
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="">Mesin : </label>
                                                                @if($antrian->machine_code)
                                                                        @php
                                                                            $machineCode = explode(',', $antrian->machine_code);

                                                                            foreach ($machineCode as $item) {
                                                                                    $antriann = App\Models\Machine::where('machine_code', $item)->first();
                                                                                    //tampilkan name dari tabel machines, jika nama terakhir tidak perlu koma
                                                                                    if($antriann->machine_code == end($machineCode)){
                                                                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                    }
                                                                                }
                                                                        @endphp
                                                                    @else
                                                                        -
                                                                    @endif
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label>Nominal Omset</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($antrian->omset, 0, ',', '.'); }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga Produk</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($antrian->harga_produk, 0, ',', '.'); }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Biaya Jasa Pasang</label>
                                                                <input type="text" class="form-control" value="Rp {{ $antrian->payment->installation_cost == null ? '-' : $antrian->payment->installation_cost }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Biaya Jasa Pengiriman</label>
                                                                <input type="text" class="form-control" value="Rp {{ $antrian->payment->shipping_cost == null ? '-' : $antrian->payment->shipping_cost }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alamat Pengiriman</label>
                                                                <textarea class="form-control" rows="6" readonly>{{ $antrian->alamat_pengiriman == null ? '-' : $antrian->alamat_pengiriman }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Preview ACC Desain</label>
                                                                        <div class="text-muted font-italic">{{ strlen($antrian->order->acc_desain) > 25 ? substr($antrian->order->acc_desain, 0, 25) . '...' : $antrian->order->acc_desain }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesain{{ $antrian->id }}">Lihat</button></div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>Preview Bukti Pembayaran</label>
                                                                        <div class="text-muted font-italic">{{ strlen($antrian->payment->payment_proof) > 25 ? substr($antrian->payment->payment_proof, 0, 25) . '...' : $antrian->payment->payment_proof }}<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-buktiPembayaran{{ $antrian->id }}">Lihat</button></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <td>{{ $antrian->qty }}</td>
                                                <td>{{ $antrian->order->employee->name }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('design.download', $antrian->id) }}" target="_blank" class="btn btn-primary btn-sm">Download</a>
                                                </td>
                                                <td class="text-center">
                                                    @if($antrian->design_id == null)
                                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#unggahFile{{ $antrian->id }}">Unggah</button>
                                                    @else
                                                        <a href="#" target="_blank" class="btn btn-primary btn-sm">Download</a>
                                                    @endif
                                                </td>
                                                <td class="text-center"><a href="{{ route('antrian.markAman', $antrian->id) }}" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Aman</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @foreach ($fileBaruMasuk as $antrian)
                                {{-- Modal Unggah File --}}
                                <div class="modal fade" id="unggahFile{{ $antrian->id }}">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">Upload File Produksi / Jadi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        <form id="formUploadProduksi" action="{{ route('simpanFileProduksi') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                                <input type="hidden" name="idAntrian" id="antrian_id" value="{{ $antrian->id }}">
                                                <input type="hidden" name="ticketOrder" id="ticketOrder" value="{{ $antrian->ticket_order }}">
                                                <input type="hidden" name="desainer" id="desainer" value="{{ Auth::user()->employee->id }}">
                                                <div class="form-group">
                                                    <label for="">Judul File / Produk <span class="text-danger">*</span></label>
                                                    <input type="text" name="judulFile" id="judulFile" class="form-control" placeholder="Contoh : Bantalan Stempel Custom 25x18cm" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fileCetak">Unggah File Produksi <span class="text-danger">(.cdr)</span></label>
                                                    <input type="file" name="fileCetak" id="fileCetak" class="form-control" accept=".cdr" required>
                                                    <p class="font-italic text-muted">*Ukuran file maksimal 100MB</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary submitUpload">Upload</button>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Progress Produksi</h2>
                            </div>
                            <div class="card-body">
                                <table id="progressProduksi" class="table table-responsive table-bordered table-striped " style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Ticket Order</th>
                                            <th>Sales</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Desainer(Awal)</th>
                                            <th>File Desain</th>
                                            <th>Desainer(Lanjut)</th>
                                            <th>File Cetak/Produksi</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($progressProduksi as $antrian)
                                            <tr>
                                                <td>{{ $antrian->ticket_order }}</td>
                                                <td>{{ $antrian->sales->sales_name }}</td>
                                                <td>{{ $antrian->job->job_name }} <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detailProgress{{ $antrian->id }}"><i class="fas fa-info-circle"></i></button></td>

                                                <div class="modal fade" id="detailProgress{{ $antrian->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title">Detail #{{ $antrian->ticket_order }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="form-label">Sales</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->sales->sales_name }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Nama Pelanggan
                                                                    @if($antrian->customer->frekuensi_order == 0)
                                                                    <span class="badge badge-danger">New Leads</span>
                                                                    @elseif($antrian->customer->frekuensi_order == 1)
                                                                    <span class="badge badge-warning">New Customer</span>
                                                                    @elseif($antrian->customer->frekuensi_order >= 2)
                                                                    <span class="badge badge-success">Repeat Order</span>
                                                                    @endif
                                                                </label>
                                                                <input type="text" class="form-control" value="{{ $antrian->customer->nama }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Nama Produk</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->job->job_name }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Jumlah Produk (Qty)</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->qty }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Keterangan / Spesifikasi Produk</label>
                                                                <textarea class="form-control" rows="6" readonly>{{ $antrian->note }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="form-label">Deadline</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->end_job }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Desainer</label>
                                                                <input type="text" class="form-control" value="{{ $antrian->order->employee->name }}" readonly>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="form-label">Tempat : </label>
                                                                    @php
                                                                        $tempat = explode(',', $antrian->working_at);
                                                                        foreach ($tempat as $item) {
                                                                                if($item == 'Surabaya'){
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                    }
                                                                                }elseif ($item == 'Kediri') {
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                    }
                                                                                }elseif ($item == 'Malang') {
                                                                                    if($item == end($tempat)){
                                                                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                    }
                                                                                }
                                                                            }
                                                                    @endphp
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-4 rounded-2">
                                                                <label>Operator</label>
                                                                <p>
                                                                @if($antrian->operator_id)
                                                                    @php
                                                                        $operatorId = explode(',', $antrian->operator_id);
                                                                        foreach ($operatorId as $item) {
                                                                            if($item == 'rekanan'){
                                                                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                            }
                                                                            else{
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($operatorId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 rounded-2">
                                                                <label>Finishing</label>
                                                                <p>
                                                                @if($antrian->finisher_id)
                                                                    @php
                                                                        $finisherId = explode(',', $antrian->finisher_id);
                                                                        foreach ($finisherId as $item) {
                                                                            if($item == 'rekanan'){
                                                                                echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                            }
                                                                            else{
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($finisherId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 rounded-2">
                                                                <label>Pengawas / QC</label>
                                                                <p>
                                                                @if($antrian->qc_id)
                                                                    @php
                                                                        $qcId = explode(',', $antrian->qc_id);
                                                                        foreach ($qcId as $item) {
                                                                                $antriann = App\Models\Employee::find($item);
                                                                                //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                if($antriann->id == end($qcId)){
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                }
                                                                                else{
                                                                                    echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                }
                                                                            }
                                                                    @endphp
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="">Mesin : </label>
                                                                @if($antrian->machine_code)
                                                                        @php
                                                                            $machineCode = explode(',', $antrian->machine_code);

                                                                            foreach ($machineCode as $item) {
                                                                                    $antriann = App\Models\Machine::where('machine_code', $item)->first();
                                                                                    //tampilkan name dari tabel machines, jika nama terakhir tidak perlu koma
                                                                                    if($antriann->machine_code == end($machineCode)){
                                                                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                    }
                                                                                    else{
                                                                                        echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                    }
                                                                                }
                                                                        @endphp
                                                                    @else
                                                                        -
                                                                    @endif
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label>Nominal Omset</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($antrian->omset, 0, ',', '.'); }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga Produk</label>
                                                                <input type="text" class="form-control" value="Rp {{ number_format($antrian->harga_produk, 0, ',', '.'); }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Biaya Jasa Pasang</label>
                                                                <input type="text" class="form-control" value="Rp {{ $antrian->payment->installation_cost == null ? '-' : $antrian->payment->installation_cost }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Biaya Jasa Pengiriman</label>
                                                                <input type="text" class="form-control" value="Rp {{ $antrian->payment->shipping_cost == null ? '-' : $antrian->payment->shipping_cost }}" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alamat Pengiriman</label>
                                                                <textarea class="form-control" rows="6" readonly>{{ $antrian->alamat_pengiriman == null ? '-' : $antrian->alamat_pengiriman }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Preview ACC Desain</label>
                                                                        <div class="text-muted font-italic">{{ strlen($antrian->order->acc_desain) > 25 ? substr($antrian->order->acc_desain, 0, 25) . '...' : $antrian->order->acc_desain }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesainpro{{ $antrian->id }}">Lihat</button></div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>Preview Bukti Pembayaran</label>
                                                                        <div class="text-muted font-italic">{{ strlen($antrian->payment->payment_proof) > 25 ? substr($antrian->payment->payment_proof, 0, 25) . '...' : $antrian->payment->payment_proof }}<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-buktiPembayaranPro{{ $antrian->id }}">Lihat</button></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <td>{{ $antrian->qty }}</td>
                                                <td>{{ $antrian->order->employee->name }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('design.download', $antrian->id) }}" target="_blank" class="btn btn-primary btn-sm">Download</a>
                                                </td>
                                                <td>{{ $antrian->design_id != null ? $antrian->design->employee->name : '-' }}</td>
                                                <td class="text-center">
                                                    @if($antrian->design_id == null && $antrian->is_aman == 1)
                                                        <i class="fas fa-check-circle"></i><span> File Aman</span>
                                                    @elseif($antrian->design_id != null && $antrian->is_aman == 1)
                                                        <a href="{{ route('downloadFileProduksi', $antrian->design_id) }}" target="_blank" class="btn btn-dark btn-sm">Download</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Mengambil 1 data pada tabel dokumproses berdasarkan antrian_id dan orderby created_at --}}
                                                    @php
                                                        if($antrian->design_id != null){
                                                            $dokumproses = App\Models\Dokumproses::where('antrian_id', $antrian->id)->orderBy('created_at', 'desc')->first();
                                                        }
                                                    @endphp
                                                        <strong class="text-danger">{{ $antrian->design_id != null && $dokumproses ? $dokumproses->note : '-' }}</strong><button class="btn btn-sm bg-dark m-2" data-toggle="modal" data-target="#modalProgress{{ $antrian->id }}" >Lihat</button>
                                                        {{-- Modal --}}

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @foreach ($progressProduksi as $antrian)
                                {{-- Modal Progress --}}
                                <div class="modal fade" id="modalProgress{{ $antrian->id }}">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">Dokumentasi Proses Produksi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                            <p><strong>Gambar</strong></p>
                                                @if($antrian->design_id != null && $dokumproses)
                                                <img src="{{ asset('storage/dokum-proses/'.$dokumproses->file_gambar) }}" alt="" class="img-fluid">
                                                @else
                                                <p class="text-danger">Tidak ada gambar</p>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <p><strong>Video</strong></p>
                                                @if($antrian->design_id != null && $dokumproses)
                                                <video src="{{ asset('storage/dokum-proses/'.$dokumproses->file_video) }}" controls class="img-fluid"></video>
                                                @else
                                                <p class="text-danger">Tidak ada video</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Selesai Produksi</h2>
                            </div>
                            <div class="card-body">
                                <table id="selesaiProduksi" class="table table-responsive table-bordered table-striped " style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Ticket Order</th>
                                            <th>Sales</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Desainer(Awal)</th>
                                            <th>File Desain</th>
                                            <th>Desainer(Lanjut)</th>
                                            <th>File Cetak/Produksi</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($selesaiProduksi as $antrian)
                                            <tr>
                                                <td>{{ $antrian->ticket_order }}</td>
                                                <td>{{ $antrian->sales->sales_name }}</td>
                                                <td>{{ $antrian->job->job_name }} <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detailSelesai{{ $antrian->id }}"><i class="fas fa-info-circle"></i></button></td>

                                                    <div class="modal fade" id="detailSelesai{{ $antrian->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title">Detail #{{ $antrian->ticket_order }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="form-label">Sales</label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->sales->sales_name }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="form-label">Nama Pelanggan
                                                                        @if($antrian->customer->frekuensi_order == 0)
                                                                        <span class="badge badge-danger">New Leads</span>
                                                                        @elseif($antrian->customer->frekuensi_order == 1)
                                                                        <span class="badge badge-warning">New Customer</span>
                                                                        @elseif($antrian->customer->frekuensi_order >= 2)
                                                                        <span class="badge badge-success">Repeat Order</span>
                                                                        @endif
                                                                    </label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->customer->nama }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="form-label">Nama Produk</label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->job->job_name }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="form-label">Jumlah Produk (Qty)</label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->qty }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Keterangan / Spesifikasi Produk</label>
                                                                    <textarea class="form-control" rows="6" readonly>{{ $antrian->note }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="form-label">Deadline</label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->end_job }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Desainer</label>
                                                                    <input type="text" class="form-control" value="{{ $antrian->order->employee->name }}" readonly>
                                                                </div>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <label for="form-label">Tempat : </label>
                                                                        @php
                                                                            $tempat = explode(',', $antrian->working_at);
                                                                            foreach ($tempat as $item) {
                                                                                    if($item == 'Surabaya'){
                                                                                        if($item == end($tempat)){
                                                                                            echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                        }
                                                                                        else{
                                                                                            echo '<a class="btn btn-sm btn-danger ml-2 mr-2">Surabaya</a>';
                                                                                        }
                                                                                    }elseif ($item == 'Kediri') {
                                                                                        if($item == end($tempat)){
                                                                                            echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                        }
                                                                                        else{
                                                                                            echo '<a class="btn btn-sm btn-warning ml-2 mr-2">Kediri</a>';
                                                                                        }
                                                                                    }elseif ($item == 'Malang') {
                                                                                        if($item == end($tempat)){
                                                                                            echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                        }
                                                                                        else{
                                                                                            echo '<a class="btn btn-sm btn-success ml-2 mr-2">Malang</a>';
                                                                                        }
                                                                                    }
                                                                                }
                                                                        @endphp
                                                                </div>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-4 rounded-2">
                                                                    <label>Operator</label>
                                                                    <p>
                                                                    @if($antrian->operator_id)
                                                                        @php
                                                                            $operatorId = explode(',', $antrian->operator_id);
                                                                            foreach ($operatorId as $item) {
                                                                                if($item == 'rekanan'){
                                                                                    echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                                }
                                                                                else{
                                                                                    $antriann = App\Models\Employee::find($item);
                                                                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                    if($antriann->id == end($operatorId)){
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                    }
                                                                                    else{
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4 rounded-2">
                                                                    <label>Finishing</label>
                                                                    <p>
                                                                    @if($antrian->finisher_id)
                                                                        @php
                                                                            $finisherId = explode(',', $antrian->finisher_id);
                                                                            foreach ($finisherId as $item) {
                                                                                if($item == 'rekanan'){
                                                                                    echo '<i class="fas fa-user-circle"></i> Rekanan';
                                                                                }
                                                                                else{
                                                                                    $antriann = App\Models\Employee::find($item);
                                                                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                    if($antriann->id == end($finisherId)){
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                    }
                                                                                    else{
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4 rounded-2">
                                                                    <label>Pengawas / QC</label>
                                                                    <p>
                                                                    @if($antrian->qc_id)
                                                                        @php
                                                                            $qcId = explode(',', $antrian->qc_id);
                                                                            foreach ($qcId as $item) {
                                                                                    $antriann = App\Models\Employee::find($item);
                                                                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                                                                    if($antriann->id == end($qcId)){
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name;
                                                                                    }
                                                                                    else{
                                                                                        echo '<i class="fas fa-user-circle"></i>  ' . $antriann->name . "<br>";
                                                                                    }
                                                                                }
                                                                        @endphp
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                </div>
                                                                </div>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <label for="">Mesin : </label>
                                                                    @if($antrian->machine_code)
                                                                            @php
                                                                                $machineCode = explode(',', $antrian->machine_code);

                                                                                foreach ($machineCode as $item) {
                                                                                        $antriann = App\Models\Machine::where('machine_code', $item)->first();
                                                                                        //tampilkan name dari tabel machines, jika nama terakhir tidak perlu koma
                                                                                        if($antriann->machine_code == end($machineCode)){
                                                                                            echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                        }
                                                                                        else{
                                                                                            echo '<a class="btn btn-sm btn-dark ml-2 mr-2">' . $antriann->machine_name . '</a>';
                                                                                        }
                                                                                    }
                                                                            @endphp
                                                                        @else
                                                                            -
                                                                        @endif
                                                                </div>
                                                                <hr>
                                                                <div class="form-group">
                                                                    <label>Nominal Omset</label>
                                                                    <input type="text" class="form-control" value="Rp {{ number_format($antrian->omset, 0, ',', '.'); }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Harga Produk</label>
                                                                    <input type="text" class="form-control" value="Rp {{ number_format($antrian->harga_produk, 0, ',', '.'); }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Biaya Jasa Pasang</label>
                                                                    <input type="text" class="form-control" value="Rp {{ $antrian->payment->installation_cost == null ? '-' : $antrian->payment->installation_cost }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Biaya Jasa Pengiriman</label>
                                                                    <input type="text" class="form-control" value="Rp {{ $antrian->payment->shipping_cost == null ? '-' : $antrian->payment->shipping_cost }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Alamat Pengiriman</label>
                                                                    <textarea class="form-control" rows="6" readonly>{{ $antrian->alamat_pengiriman == null ? '-' : $antrian->alamat_pengiriman }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Preview ACC Desain</label>
                                                                            <div class="text-muted font-italic">{{ strlen($antrian->order->acc_desain) > 25 ? substr($antrian->order->acc_desain, 0, 25) . '...' : $antrian->order->acc_desain }}<button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesain{{ $antrian->id }}">Lihat</button></div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Preview Bukti Pembayaran</label>
                                                                            <div class="text-muted font-italic">{{ strlen($antrian->payment->payment_proof) > 25 ? substr($antrian->payment->payment_proof, 0, 25) . '...' : $antrian->payment->payment_proof }}<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-buktiPembayaran{{ $antrian->id }}">Lihat</button></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                <td>{{ $antrian->qty }}</td>
                                                <td>{{ $antrian->order->employee->name }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('design.download', $antrian->id) }}" target="_blank" class="btn btn-primary btn-sm">Download</a>
                                                </td>
                                                <td>{{ $antrian->design_id != null ? $antrian->design->employee->name : '-' }}</td>
                                                <td class="text-center">
                                                    @if($antrian->design_id == null)
                                                        <i class="fas fa-check-circle"></i><span> File Aman</span>
                                                    @else
                                                        <a href="{{ route('downloadFileProduksi', $antrian->design_id) }}" target="_blank" class="btn btn-dark btn-sm">Download</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{-- Mengambil 1 data pada tabel dokumproses berdasarkan antrian_id dan orderby created_at --}}
                                                    @php
                                                        if($antrian->design_id != null){
                                                            $dokumproses = App\Models\Dokumproses::where('antrian_id', $antrian->id)->orderBy('created_at', 'desc')->first();
                                                        }
                                                    @endphp
                                                        <strong class="text-success">Selesai</strong><button class="btn btn-sm bg-dark m-2" data-toggle="modal" data-target="#selesaiProgress{{ $antrian->id }}" >Lihat</button>
                                                        {{-- Modal --}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @foreach ($selesaiProduksi as $antrian)
                                {{-- Modal Progress --}}
                                <div class="modal fade" id="selesaiProgress{{ $antrian->id }}">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h4 class="modal-title">Dokumentasi Hasil Produksi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                            <p><strong>Gambar</strong></p>
                                                @if($antrian->timer_stop != null)
                                                @php
                                                    $dokumentasi = App\Models\Documentation::where('antrian_id', $antrian->id)->orderBy('created_at', 'desc')->get();
                                                @endphp
                                                @foreach ($dokumentasi as $gambar)
                                                    <img src="{{ asset('storage/dokumentasi/'.$gambar->filename) }}" alt="" class="img-fluid p-3">
                                                @endforeach
                                                @else
                                                <p class="text-danger">Tidak ada gambar</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @foreach ($fileBaruMasuk as $antrian)
                    <div class="modal fade" id="modal-accdesain{{ $antrian->id }}">
                        <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">Preview File Acc Desain</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <img class="img-fluid" src="{{ asset('storage/acc-desain/'.$antrian->order->acc_desain) }}">
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    @endforeach

                    <!-- Modal ACC Desain -->
                    @foreach ($progressProduksi as $antrian)
                    <div class="modal fade" id="modal-accdesainpro{{ $antrian->id }}">
                        <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">Preview File Acc Desain</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <img class="img-fluid" src="{{ asset('storage/acc-desain/'.$antrian->order->acc_desain) }}">
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    @endforeach
                    <!-- Modal ACC Desain -->

                    <!-- Modal Bukti Pembayaran -->
                    @foreach ($fileBaruMasuk as $antrian)
                    <div class="modal fade" id="modal-buktiPembayaran{{ $antrian->id }}">
                        <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">File Bukti Pembayaran</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                {{-- Menampilkan payment_proof dari tabel payments --}}
                                @php
                                    $paymentProof = \App\Models\Payment::where('ticket_order', $antrian->ticket_order)->get();
                                @endphp
                                @foreach ($paymentProof as $item)
                                    @if($item->payment_proof == null)
                                        <p class="text-danger">Tidak ada file</p>
                                    @else
                                    <img class="img-fluid" src="{{ asset('storage/bukti-pembayaran/'.$item->payment_proof) }}">
                                    @endif
                                @endforeach
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                @endforeach

                @foreach ($progressProduksi as $antrian)
                    <div class="modal fade" id="modal-buktiPembayaranPro{{ $antrian->id }}">
                        <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title">File Bukti Pembayaran</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                {{-- Menampilkan payment_proof dari tabel payments --}}
                                @php
                                    $paymentProof = \App\Models\Payment::where('ticket_order', $antrian->ticket_order)->get();
                                @endphp
                                @foreach ($paymentProof as $item)
                                    @if($item->payment_proof == null)
                                        <p class="text-danger">Tidak ada file</p>
                                    @else
                                    <img class="img-fluid" src="{{ asset('storage/bukti-pembayaran/'.$item->payment_proof) }}">
                                    @endif
                                @endforeach
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#antrianBaruMasuk').DataTable({
                'responsive': true,
                'autoWidth': false
            });
            $('#progressProduksi').DataTable({
                'responsive': true,
                'autoWidth': false
            });
            $('#selesaiProduksi').DataTable({
                'responsive': true,
                'autoWidth': false
            });
        });

        @if(session('success'))
                Swal.fire(
                    'Berhasil!',
                    '{{ session('success') }}',
                    'success'
                )
        @endif

        //Menutup modal saat modal lainnya dibuka
        $('.modal').on('show.bs.modal', function (e) {
                $('.modal').not($(this)).each(function () {
                    $(this).modal('hide');
                });
        });
    </script>
@endsection
