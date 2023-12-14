@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Antrian Desain')

@section('style')
  <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/dropzone/min/dropzone.min.css">
@endsection

@section('content')

{{-- Jika ada sesi success --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
@endif

{{-- Jika ada sesi error --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                @if(Auth::user()->role == 'sales')
                <li class="nav-item">
                  <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Menunggu Desain</a>
                </li>
                @endif
                <li class="nav-item">
                  <a class="nav-link {{ Auth::user()->role != 'sales' ? 'active' : '' }}" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Progress Desain</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">Selesai Desain</a>
                </li>
                @if(auth()->user()->role == 'sales')
                <li class="nav-item">
                    <a class="nav-link" id="proses-to-produksi-tab" data-toggle="pill" href="#proses-to-produksi" role="tab" aria-controls="proses-to-produksi" aria-selected="false">Input Produksi</a>
                </li>
                @endif
                @if(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                <li class="nav-item">
                    <a class="nav-link" id="revisi-desain-tab" data-toggle="pill" href="#revisi-desain" role="tab" aria-controls="revisi-desain" aria-selected="false">Revisi Desain</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
            @if(Auth::user()->role == 'sales')
            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                <div class="card">
                <div class="card-header">
                  <h2 class="card-title">Antrian Desain</h2>
                  {{-- Tombol tambah order --}}
                    @if(Auth::user()->role == 'sales')
                        <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                    @endif
                </div>
                <div class="card-body">
                {{-- Menampilkan Antrian Desain --}}
                <table id="tableAntrianDesain" class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket Order</th>
                            <th>Judul Desain</th>
                            <th>Ref. Desain</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Status</th>
                            <th>Waktu Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listDesain as $desain)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                                {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                @if($desain->is_priority == '1')
                                    <i class="fas fa-star text-warning"></i>
                                @endif
                            </td>
                            
                            <td>{{ $desain->ticket_order }}</td>

                            <td>{{ $desain->title }}</td>

                            {{-- Jika desain tidak kosong, maka tampilkan nama file desain, jika kosong, maka tampilkan tanda strip (-) --}}
                            @if($desain->desain != null)
                                @php
                                    $refImage = strlen($desain->desain) > 15 ? substr($desain->desain, 0, 15) . '...' : $desain->desain;
                                @endphp
                                    <td scope="row"><a href="{{ asset('storage/ref-desain/'.$desain->desain) }}" target="_blank">{{ $refImage }}</a></td>
                            @else
                                    <td scope="row">-</td>
                            @endif

                            <td>
                                @php
                                if($desain->type_work){
                                    if($desain->type_work == 'baru'){
                                        echo 'Desain Baru';
                                    }elseif($desain->type_work == 'edit'){
                                        echo 'Edit Desain';
                                    }
                                }else{
                                    echo '-';
                                }
                                @endphp
                            </td>

                            {{-- Jika status = 0, maka tampilkan badge warning, jika status = 1, maka tampilkan badge primary, jika status = 2, maka tampilkan badge success --}}
                            @if($desain->status == '0')
                                <td><span class="badge badge-warning">Menunggu</span></td>
                            @elseif($desain->status == '1')
                                <td><span class="badge badge-primary">Dikerjakan</span></td>
                            @elseif($desain->status == '2')
                                <td><span class="badge badge-success">Selesai</span></td>
                            @endif

                            <td>{{ $desain->created_at }}</td>

                            {{-- Jika role = desain, maka tampilkan tombol aksi --}}
                                <td>
                                    <a href="{{ url('order/'. $desain->id .'/edit') }}" class="btn btn-sm btn-primary" {{ Auth::user()->role != 'sales' ? "style=display:none" : '' }}><i class="fas fa-edit"></i></a>
                                    @if(Auth::user()->role == 'sales')
                                    {{-- Tombol untuk membagi desain --}}
                                    <button class="btn btn-sm bg-orange" data-toggle="modal" data-target="#modalBagiDesain"><i class="fas fa-user"></i></button>
                                    @endif
                                    {{-- Tombol Modal Detail Keterangan Desain --}}
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailWorking{{ $desain->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- End Menampilkan Antrian Desain --}}
                @if(Auth::user()->role == 'sales')
                @foreach($listDesain as $desain)
                {{-- Modal Bagi Desain --}}
                <div class="modal fade" id="modalBagiDesain">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Pilih Desainer</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-responsive" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Desainer</th>
                                        <th>Jumlah Antrian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listDesainer as $employee)
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->design_load }}</td>
                                        <td>
                                            <form class="bagiDesain" action="{{ route('order.bagiDesain') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="desainer_id" value="{{ $employee->id }}">
                                                <input type="hidden" name="order_id" value="{{ $desain->id }}">
                                                <input type="submit" class="btn btn-sm btn-primary submitButton" value="Pilih">

                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                  @endforeach
                @endif

                @foreach ($listDesain as $desain)
                <div class="modal fade" id="detailWorking{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Detail #{{ $desain->ticket_order }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="createdAtWorking{{ $desain->id }}">Waktu Dibuat</label>
                                        <input type="text" class="form-control" id="createdAtWorking{{ $desain->id }}" name="createdAt" value="{{ $desain->created_at }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastUpdateWorking{{ $desain->id }}">Terakhir Diupdate</label>
                                        <input type="text" class="form-control" id="lastUpdateWorking{{ $desain->id }}" name="lastUpdate" value="{{ $desain->updated_at }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="namaSalesWorking{{ $desain->id }}">Nama Sales</label>
                                <input type="text" class="form-control" id="namaSalesWorking{{ $desain->id }}" name="namaSales" value="{{ $desain->sales->sales_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="judulDesainWorking{{ $desain->id }}">Judul Desain</label>
                                <input type="text" class="form-control" id="judulDesainWorking{{ $desain->id }}" name="judulDesain" value="{{ $desain->title }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="jenisProdukWorking{{ $desain->id }}">Jenis Produk</label>
                                <input type="text" class="form-control" id="jenisProdukWorking{{ $desain->id }}" name="jenisPekerjaan" value="{{ $desain->job->job_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="keteranganWorking{{ $desain->id }}">Keterangan</label>
                                <textarea class="form-control" id="keteranganWorking{{ $desain->id }}" name="keterangan" rows="3" readonly>{{ $desain->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <h6><strong>Referensi Desain</strong></h6><br>
                                <img src="{{ asset('storage/ref-desain/'. $desain->desain) }}" class="img-fluid" alt="Preview Image">
                            </div>
                            <p class="text-muted font-italic">*Jika ada yang kurang jelas, bisa menghubungi sales yang bersangkutan</p>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="tab-pane fade {{ Auth::user()->role != 'sales' ? 'show active' : '' }}" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianDikerjakan" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticker Order</th>
                                <th>Judul Desain</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                <th>Waktu Dimulai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listDikerjakan as $desain)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                    {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                    @if($desain->is_priority == '1')
                                        <i class="fas fa-star text-warning"></i>
                                    @endif
                                </td>

                                <td>{{ $desain->ticket_order }}</td>

                                <td>{{ $desain->title }}</td>

                                <td>{{ $desain->employee->name }}</td>

                                @if($desain->status == '0')
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                @elseif($desain->status == '1')
                                    <td><span class="badge badge-primary">Dikerjakan</span></td>
                                @elseif($desain->status == '2')
                                    <td><span class="badge badge-success">Selesai</span></td>
                                @endif

                                <td>{{ $desain->time_taken }}</td>

                                <td>
                                    <div class="btn-group">
                                    @if(Auth::user()->role == 'sales')
                                    <a type="button" href="{{ url('order/'. $desain->id .'/edit') }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if(Auth::user()->role == 'desain' || Auth::user()->role == 'stempel' || Auth::user()->role == 'advertising' || Auth::user()->role == 'estimator')
                                        {{-- Button untuk menampilkan modal Upload --}}
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalUpload{{ $desain->id }}"><i class="fas fa-upload"></i> Upload Desain</button>
                                    @endif

                                    {{-- Tombol Modal Detail Keterangan Desain --}}
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailWorking{{ $desain->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                    {{-- Modal Upload --}}
                    @foreach ($listDikerjakan as $desain)
                    {{-- Modal Keterangan Desain --}}
                    <div class="modal fade" id="detailWorking{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">Detail #{{ $desain->ticket_order }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="createdAtWorking{{ $desain->id }}">Waktu Dibuat</label>
                                            <input type="text" class="form-control" id="createdAtWorking{{ $desain->id }}" name="createdAt" value="{{ $desain->created_at }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastUpdateWorking{{ $desain->id }}">Terakhir Diupdate</label>
                                            <input type="text" class="form-control" id="lastUpdateWorking{{ $desain->id }}" name="lastUpdate" value="{{ $desain->updated_at }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="namaSalesWorking{{ $desain->id }}">Nama Sales</label>
                                    <input type="text" class="form-control" id="namaSalesWorking{{ $desain->id }}" name="namaSales" value="{{ $desain->sales->sales_name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="judulDesainWorking{{ $desain->id }}">Judul Desain</label>
                                    <input type="text" class="form-control" id="judulDesainWorking{{ $desain->id }}" name="judulDesain" value="{{ $desain->title }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="jenisProdukWorking{{ $desain->id }}">Jenis Produk</label>
                                    <input type="text" class="form-control" id="jenisProdukWorking{{ $desain->id }}" name="jenisPekerjaan" value="{{ $desain->job->job_name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="keteranganWorking{{ $desain->id }}">Keterangan</label>
                                    <textarea class="form-control" id="keteranganWorking{{ $desain->id }}" name="keterangan" rows="3" readonly>{{ $desain->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <h6><strong>Referensi Desain</strong></h6><br>
                                    <img src="/storage/ref-desain/{{ $desain->desain }}" class="img-fluid" alt="Responsive image">
                                </div>
                                <p class="text-muted font-italic">*Jika ada yang kurang jelas, bisa menghubungi sales yang bersangkutan</p>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalUpload{{ $desain->id }}" aria-labelledby="modalUpload{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">File Upload #{{ $desain->id }}</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{-- Dropzone JS --}}
                                    <form action="{{ route('design.upload') }}" class="dropzone" id="my-dropzone{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $desain->id }}">
                                        <input type="hidden" name="linkFile" value="">
                                        <a href="{{ route('submit.file-cetak', $desain->id) }}" class="btn btn-primary btn-sm submitButtonUpload">Unggah</a>
                                    </form>

                                    <div class="form-group mt-2">
                                        <form action="{{ route('submitLinkUpload') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label for="linkFileUpload{{ $desain->id }}">Link File <span class="text-muted font-italic text-sm">*Opsional</span></label>
                                            <input type="text" class="form-control" id="linkFileUpload{{ $desain->id }}" name="linkFileUpload" placeholder="https://drive.google.com/xxxxx">
                                            <input type="hidden" name="id" value="{{ $desain->id }}">
                                            <input type="submit" class="btn btn-primary btn-sm mt-2 submitLink disabled" value="Simpan">
                                        </form>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                                    <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    {{-- End Modal Upload --}}
                    @endforeach
                  </div>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                <div class="card">
                    <div class="card-header">
                      <h2 class="card-title">Antrian Desain</h2>
                      {{-- Tombol tambah order --}}
                        @if(Auth::user()->role == 'sales')
                            <a href="{{ url('order/create') }}" class="btn btn-sm btn-warning float-right"><strong>Tambah Desain</strong></a>
                        @endif
                    </div>
                    <div class="card-body">
                    {{-- Menampilkan Antrian Desain --}}
                    <table id="tableAntrianSelesai" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket Order</th>
                                <th>Sales</th>
                                <th>Judul Desain</th>
                                <th>Waktu Selesai</th>
                                <th>Periode</th>
                                <th>Produk</th>
                                <th>Desainer</th>
                                <th>Status</th>
                                <th>File Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listSelesai as $desain)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                    {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                    @if($desain->is_priority == '1')
                                        <i class="fas fa-star text-warning"></i>
                                    @endif
                                </td>
                                
                                <td>{{ $desain->ticket_order }}</td>

                                <td>{{ $desain->sales->sales_name }}</td>

                                <td>{{ $desain->title }}</td>

                                <td>{{ $desain->time_end }}</td>

                                <td>
                                    @php
                                        //Hitung periode waktu (time_end - time_taken)
                                        $time_end = strtotime($desain->time_end);
                                        $time_taken = strtotime($desain->time_taken);
                                        $periode = $time_end - $time_taken;
                                        //tampilkan dengan format jam:menit:detik
                                        echo gmdate("H:i:s", $periode);
                                    @endphp
                                </td>

                                <td>{{ $desain->job->job_name }}</td>

                                <td>{{ $desain->employee->name }}</td>

                                @if($desain->status == '0')
                                    <td><span class="badge badge-warning">Menunggu</span></td>
                                @elseif($desain->status == '1')
                                    <td><span class="badge badge-primary">Dikerjakan</span></td>
                                @elseif($desain->status == '2')
                                    <td><span class="badge badge-success">Selesai</span></td>
                                @endif

                                <td>{{ $desain->file_cetak }}
                                    @if(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                                        @if($desain->toWorkshop == 0)
                                            <button class="btn btn-sm btn-warning" data-target="#modalReuploadFile{{ $desain->id }}" data-toggle="modal">Reupload</button>
                                        @else
                                            <button class="btn btn-sm btn-warning disabled">Sudah Diantrikan</button>
                                        @endif
                                    @endif
                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- End Menampilkan Antrian Desain --}}
                  </div>
                  @foreach ($listSelesai as $desain)
                  {{-- Modal Reupload File Desain --}}
                  <div class="modal fade" id="modalReuploadFile{{ $desain->id }}" aria-labelledby="modalReuploadFile{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Reupload Desain#{{ $desain->id }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{-- Dropzone JS --}}
                                <form action="{{ route('design.reuploadFile') }}" class="dropzone" id="my-dropzone-reupload{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $desain->id }}">
                                    <input type="hidden" name="linkReupload" value="">
                                    <a href="{{ route('submit.reupload', $desain->id) }}" class="btn btn-primary btn-sm submitButtonReupload">Unggah</a>
                                </form>
                                <div class="form-group mt-2">
                                    <form action="{{ route('submitLinkReupload') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <label for="linkReupload{{ $desain->id }}">Link File</label>
                                        <input type="text" class="form-control" id="linkReupload{{ $desain->id }}" name="linkReupload" placeholder="https://drive.google.com/xxxxx">
                                        <input type="hidden" name="id" value="{{ $desain->id }}">
                                        <input type="submit" class="btn btn-primary btn-sm mt-2 submitLinkReupload" value="Simpan">
                                    </form>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                                <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                {{-- End Modal Reupload File Desain --}}
                @endforeach
                </div>
            </div>
            @if(auth()->user()->role == 'sales')
            <div class="tab-pane fade" id="proses-to-produksi" role="tabpanel" aria-labelledby="proses-to-produksi-tab">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            Proses ke Produksi
                        </h2>
                    </div>
                    <div class="card-body">
                        <table id="tableInputProduksi" class="table table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Ticket Order</th>
                                    <th>Sales</th>
                                    <th>Judul Desain</th>
                                    <th>Jenis Produk</th>
                                    <th>Desainer</th>
                                    <th>Status</th>
                                    @if(Auth::user()->role == 'sales')
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listSelesai as $desain)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                        {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                        @if($desain->is_priority == '1')
                                            <i class="fas fa-star text-warning"></i>
                                        @endif
                                    </td>
                                    
                                    <td>{{ $desain->ticket_order }}</td>

                                    <td>{{ $desain->sales->sales_name }}</td>

                                    <td>{{ $desain->title }}</td>

                                    <td>{{ $desain->job->job_name }}</td>

                                    <td>{{ $desain->employee->name }}</td>

                                    @if($desain->status == '0')
                                        <td><span class="badge badge-warning">Menunggu</span></td>
                                    @elseif($desain->status == '1')
                                        <td><span class="badge badge-primary">Dikerjakan</span></td>
                                    @elseif($desain->status == '2')
                                        <td><span class="badge badge-success">Selesai</span></td>
                                    @endif

                                    @if(Auth::user()->role == 'sales')
                                    <td>
                                        <div class="btn-group">
                                            @if($desain->toWorkshop == 0)
                                            <a type="button" href="{{ route('order.toAntrian', $desain->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-arrow-circle-right"></i> Antrikan</a>
                                            <a type="button" href="#" class="btn btn-sm btn-danger disabled">Revisi Desain</a>
                                            @else
                                            <a type="button" href="#" class="btn btn-sm btn-success disabled"><i class="fas fa-check"></i> Diantrikan</a>
                                            <a type="button" href="{{ route('report.formOrder', $desain->ticket_order) }}" class="btn btn-sm btn-warning" target="_blank"><i class="fas fa-file"></i> Unduh Form Order</a>
                                            <a type="button" href="{{ route('order.revisiDesain', $desain->id) }}" class="btn btn-sm btn-danger">Revisi Desain</a>
                                            @endif

                                        </div>
                                    </td>
                                    @endif

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <p class="text-muted font-italic">*Untuk pesanan yang sudah diantrikan, dapat melakukan <span class="text-danger font-weight-bold">"Revisi Desain"</span> dengan catatan pesanan belum diproduksi</p>
                    </div>
                </div>
            </div>
            @endif
            @if(auth()->user()->role == 'desain' || auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                <div class="tab-pane fade" id="revisi-desain" role="tabpanel" aria-labelledby="revisi-desain-tab">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Revisi Desain</h6>
                        </div>
                        <div class="card-body">
                            <table id="tableRevisiDesain" class="table table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Sales</th>
                                        <th>Judul Desain</th>
                                        <th>Jenis Produk</th>
                                        <th>ACC Desain</th>
                                        <th>Desainer</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($listRevisi as $desain)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                            {{-- Jika is_priority = 1, maka tambahkan icon star war warna kuning disebelah nomer urut --}}
                                            @if($desain->is_priority == '1')
                                                <i class="fas fa-star text-warning"></i>
                                            @endif
                                        </td>

                                        <td>{{ $desain->sales->sales_name }}</td>

                                        <td>{{ $desain->title }}</td>

                                        <td>{{ $desain->job->job_name }}</td>

                                        <td>
                                            @php
                                                $accDesain = strlen($desain->acc_desain) > 15 ? substr($desain->acc_desain, 0, 15) . '...' : $desain->acc_desain;
                                            @endphp
                                            <a href="{{ asset('storage/acc-desain/'. $desain->acc_desain) }}" target="_blank">{{ $accDesain }}</a>
                                        </td>

                                        <td>{{ $desain->employee->name }}</td>

                                        @if($desain->status == '0')
                                            <td><span class="badge badge-warning">Menunggu</span></td>
                                        @elseif($desain->status == '1')
                                            <td><span class="badge badge-primary">Dikerjakan</span></td>
                                        @elseif($desain->status == '2')
                                            <td><span class="badge badge-success">Selesai</span></td>
                                        @endif

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" data-target="#modalRevisi{{ $desain->id }}" data-toggle="modal" class="btn btn-sm btn-danger"><i class="fas fa-upload"></i> Upload Revisi Desain</button>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @foreach($listRevisi as $desain)
                            <div class="modal fade" id="modalRevisi{{ $desain->id }}" aria-labelledby="modalRevisi{{ $desain->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Upload Revisi Desain#{{ $desain->id }}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- Dropzone JS --}}
                                            <form action="{{ route('uploadRevisi') }}" class="dropzone" id="my-dropzone-revisi{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $desain->id }}">
                                                <input type="hidden" name="linkReupload" value="">
                                                <a href="{{ route('submitRevisi', $desain->id) }}" class="btn btn-primary btn-sm submitButtonRevisi">Unggah</a>
                                            </form>
                                            <div class="form-group mt-2">
                                                <form action="{{ route('submitLinkRevisi') }}" enctype="multipart/form-data" method="POST">
                                                    @csrf
                                                    <label for="linkRevisi{{ $desain->id }}">Link File</label>
                                                    <input type="text" class="form-control" id="linkRevisi{{ $desain->id }}" name="linkRevisi" placeholder="https://drive.google.com/xxxxx">
                                                    <input type="hidden" name="id" value="{{ $desain->id }}">
                                                    <input type="submit" class="btn btn-primary btn-sm mt-2 submitLinkRevisi" value="Simpan">
                                                </form>
                                            </div>

                                            <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                                            <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            </div>

            </div>
        </div>
        </div>
@endsection

@section('script')

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        @foreach($listDikerjakan as $desain)
        Dropzone.options.myDropzone{{ $desain->id }} = { // camelized version of the `id`
            paramName: "fileCetak", // The name that will be used to transfer the file
            clickable: true,
            acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd, .blend, .skp",
            dictInvalidFileType: "Type file ini tidak dizinkan",
            addRemoveLinks: true,
            dictRemoveFile: "Hapus file",
            maxFileSize: 50,
        };

        //Jika input submitLink kosong, maka tambahkan class disabled pada button submit
        $('#linkFileUpload'+{{ $desain->id }}).on('keyup', function() {
            var linkFile = $('#linkFileUpload'+{{ $desain->id }}).val();
            if(linkFile == '') {
                $('.submitLink').addClass('disabled');
                $('.submitButtonUpload').removeClass('disabled');
            } else {
                $('.submitLink').removeClass('disabled');
                $('.submitButtonUpload').addClass('disabled');
            }
        });

        @endforeach

        @foreach ($listRevisi as $revisi)
            Dropzone.options.myDropzoneRevisi{{ $revisi->id }} = { // camelized version of the `id`
                paramName: "fileRevisi", // The name that will be used to transfer the file
                clickable: true,
                acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
                dictInvalidFileType: "Type file ini tidak dizinkan",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                maxFileSize: 50,
            };

        @endforeach

        @foreach ($listSelesai as $item)
            Dropzone.options.myDropzoneReupload{{ $item->id }} = { // camelized version of the `id`
                paramName: "fileReupload", // The name that will be used to transfer the file
                clickable: true,
                acceptedFiles: ".jpeg, .jpg, .png, .pdf, .cdr, .ai, .psd",
                dictInvalidFileType: "Type file ini tidak dizinkan",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus file",
                maxFileSize: 52428800,
            };

        @endforeach
    </script>

<script>
    $(function () {
        $("#tableAntrianDesain").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $("#tableAntrianDikerjakan").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $("#tableAntrianSelesai").DataTable({
            "responsive": true,
            "autoWidth": false,
        });

        $("#tableInputProduksi").DataTable({
            "responsive": true,
            "autoWidth": false,
        });

        $("#tableRevisiDesain").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>

<script>
    $(document).ready(function() {
        // saat form bagiDesain di submit, maka tampilkan loader dan disable button submitnya
        $('.bagiDesain').submit(function() {
            $('.submitButton').prop('disabled', true);
        });

        // saat form upload di submit, maka tampilkan loader dan disable button submitnya
        $('#submitUploadDesain').click(function() {
            $(this).addClass('disabled');
            $('#loader').show();
        });

        $('#submitRevisiDesain').click(function() {
            $(this).addClass('disabled');
            $('#loader').show();
        });

    });
</script>

@if(session('success-submit'))
<script>
$(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-right',
      showConfirmButton: false,
      timer: 5000
    });
      Toast.fire({
        icon: 'success',
        title: '{{ session('success-submit') }}'
      });
});
</script>
@endif

@if(session('error-filecetak'))
    <script>
    $(function() {
        var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
        });
        Toast.fire({
            icon: 'error',
            title: '{{ session('error-filecetak') }}'
        });
    });
    </script>
@endif
<script>
    @if(session('error-take'))
        Swal.fire(
            'Gagal!',
            '{{ session('error-take') }}',
            'error'
        )
    @endif
</script>

@endsection
