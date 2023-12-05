@extends('layouts.app')

@section('title', 'Detail Project')

@section('breadcrumb', 'Detail Project')

@section('page', 'Antrian')

@section('content')
<input type="hidden" id="ticket_order" value="{{ $antrian->ticket_order }}">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Status Pesanan </strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                @php
                    $batas = $antrian->end_job;
                    $batas = date_create($batas);
                @endphp
                <p class="text-primary"><i class="fas fa-circle"></i> <span class="ml-2">Sedang Diproses</span></p>
            </div>
            <div class="row ml-1">
                <p class="text-danger"><i class="fas fa-circle"></i> <span class="ml-2">Deadline Pengerjaan : <strong>{{ date_format($batas, 'd F Y - H:i') }}</strong></span></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user mr-2"></i> <strong>Data Pelanggan</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="nama">Nama Pelanggan </label>
                        <p>{{ $antrian->customer->nama }} <span class="badge bg-danger">Repeat Order</span></p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="nama">Telepon</label>
                        <p>{{ $antrian->customer->telepon }}</p>
                    </div>
                </div>
                <div class="col-md">
                    <label for="alamat">Sumber Pelanggan</label>
                    <p>{{ $antrian->customer->infoPelanggan }}</p>
                </div>
                <div class="col-md">
                    <label for="alamat">Instansi</label>
                    <p>{{ $antrian->customer->instansi }}</p>
                </div>
                <div class="col-md">
                    <label for="iklan">Status Iklan</label>
                    <p>Facebook / -</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="alamat">Alamat</label>
                    <p>{{ $antrian->customer->alamat }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i> <strong>Informasi Pembayaran</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <h6 class="mb-3 mr-2"><strong><i class="fas fa-circle"></i> <span class="ml-2">Ticket Order - </span></strong>{{ $antrian->ticket_order }}</h6>
                <h6 class="mb-3 ml-2"><strong><i class="fas fa-circle"></i> <span class="ml-2">Sales : {{ $antrian->sales->sales_name }}</span></strong></h6>
            </div>
            <div class="row">
            <table id="tableItems" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Note</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>{{ 'Rp '.number_format($total, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Diskon</th>
                        <th>{{ 'Rp '.number_format($payment->diskon, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        @php
                            $subtotal = $total - $payment->diskon;
                        @endphp
                        <th colspan="5" class="text-right">Subtotal</th>
                        <th>{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Dibayarkan</th>
                        <th>{{ 'Rp '.number_format($payment->payment_amount, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        @php
                            $sisa = $total - $payment->diskon - $payment->payment_amount;
                        @endphp
                        <th colspan="5" class="text-right">Sisa Pembayaran</th>
                        <th>{{ 'Rp '.number_format($sisa, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>            
        </div>
    </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Catatan / Spesifikasi</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <p class="text-dark keterangan">{{ $antrian->note }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-folder-open mr-2"></i> <strong>File Cetak & Pendukung</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <div class="row">
                        <div class="col-2 mt-2">
                            <div class="bg-dark text-center rounded-lg py-2 text-sm">{{ strtoupper(substr($antrian->order->file_cetak, -3)) }}</div>
                        </div>
                        <div class="col-4 my-auto">
                            <a href="{{ route('design.download', $antrian->id) }}" class="font-weight-bold my-0">File Cetak</a>
                            <p class="text-muted">{{ date_format($antrian->order->updated_at, 'd F Y - H:i') }}</p>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="bg-dark text-center rounded-lg py-2 text-sm">{{ strtoupper(substr($antrian->payment->payment_proof, -3)) }}</div>
                        </div>
                        <div class="col-4 my-auto">
                            <a class="font-weight-bold my-0" onclick="modalBuktiPembayaran()">Bukti Pembayaran</a>
                            <p class="text-muted">{{ date_format($antrian->payment->updated_at, 'd F Y - H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Penugasan Pekerjaan</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <div class="col">
                    <h5><strong>Desain</strong></h5>
                    <p class="text-dark"><i class="fas fa-circle"></i> {{ $antrian->order->employee->name }}</p>
                </div>
                <div class="col">
                    <h5><strong>Operator</strong></h5>
                    @if($antrian->operator_id != null)
                        @php
                            $operatorId = explode(',', $antrian->operator_id);
                            foreach ($operatorId as $item) {
                                if($item == 'rekanan'){
                                    echo '- Rekanan';
                                }
                                else{
                                    $antriann = App\Models\Employee::find($item);
                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                    if($antriann->id == end($operatorId)){
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                    else{
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                }
                            }
                        @endphp
                    @else
                    -
                    @endif
                </div>
                <div class="col">
                    <h5><strong>Finishing</strong></h5>
                    @if($antrian->finisher_id != null)
                        @php
                            $finisherId = explode(',', $antrian->finisher_id);
                            foreach ($finisherId as $item) {
                                if($item == 'rekanan'){
                                    echo '- Rekanan';
                                }
                                else{
                                    $antriann = App\Models\Employee::find($item);
                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                    if($antriann->id == end($finisherId)){
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                    else{
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                }
                            }
                        @endphp
                    @else
                    -
                    @endif
                </div>
                <div class="col">
                    <h5><strong>Quality Control</strong></h5>
                    @if($antrian->qc_id)
                        @php
                            $qcId = explode(',', $antrian->qc_id);
                            foreach ($qcId as $item) {
                                    $antriann = App\Models\Employee::find($item);
                                    //tampilkan name dari tabel employees, jika nama terakhir tidak perlu koma
                                    if($antriann->id == end($qcId)){
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                    else{
                                        echo '<p class="text-dark mb-0"><i class="fas fa-circle"></i> '.$antriann->name.'</p>';
                                    }
                                }
                        @endphp
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-money-check mr-2"></i> <strong>Biaya Produksi</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @php
                        $omset = $antrian->omset;
                    @endphp
                    <h5 class="font-weight-bold">Nominal Omset : <span class="text-danger" id="omset">Rp{{ number_format($antrian->omset, 0, ',', '.') }}</span></h5>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md table-responsive">
                    <h5><strong>Biaya Bahan</strong></h5>
                <button class="btn btn-primary btn-sm m-0" onclick="modalBahan()"><i class="fas fa-plus-circle"></i> Tambah</button>
                <table id="tabelBahan" class="table table-responsive table-bordered mt-3">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Bahan</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Note</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bahan as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->nama_bahan }}</td>
                            <td>{{ 'Rp '.number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->note }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-center">Total Biaya Bahan : <span class="text-danger" id="bahanTotal">{{ $totalBahan }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                
                <hr>
                <h5><strong>Biaya Lainnya</strong></h5>
                <table id="tableBahan" class="table table-responsive table-bordered table-hover mt-3" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Biaya Sales (3%)</th>
                            <th>Biaya Desain (2%)</th>
                            <th>Biaya Penanggung Jawab (3%)</th>
                            <th>Biaya Pekerjaan (5%)</th>
                            <th>BPJS (2,5%)</th>
                            <th>Biaya Transportasi (1%)</th>
                            <th>Biaya Overhead / Lainnya (2,5%)</th>
                            <th>Biaya Alat & Listrik (2%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Rp{{ number_format($omset * 0.03, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.02, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.03, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.05, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.025, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.01, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.025, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($omset * 0.02, 0, ',', '.') }}</th>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-center">Total Biaya Lainnya : <span class="text-danger" id="totalBiayaLain">Rp{{ number_format($omset * 0.03 + $omset * 0.02 + $omset * 0.03 + $omset * 0.05 + $omset * 0.025 + $omset * 0.01 + $omset * 0.025 + $omset * 0.02, 0, ',', '.') }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col">
                        @php
                            $totalBiaya = ($omset * 0.03 + $omset * 0.02 + $omset * 0.03 + $omset * 0.05 + $omset * 0.025 + $omset * 0.01 + $omset * 0.025 + $omset * 0.02) + $totalBahan;
                            $profit = $omset - $totalBiaya;
                        @endphp
                        <h5 class="font-weight-bold">Profit Perusahaan : <span class="text-danger" id="profit">Rp{{ number_format($profit, 0, ',', '.') }}</span></h5>
                    </div>
                    <div class="col">
                        <div class="text-right">
                            @if($antrian->done_production_at == null)
                            <button class="btn btn-success btn-sm">Tandai Selesai <i class="fas fa-check"></i></button>
                            @else
                            <button class="btn btn-warning btn-sm">Unduh BP <i class="fas fa-download"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('page.antrian-workshop.modal.modal-ref-acc')
    @includeIf('page.antrian-workshop.modal.modal-tambah-bahan')
</div>

@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>

    <script>
        function modalRefACC() {
            $('#modalRefACC').modal('show');
        }

        function modalBahan() {
            $('#modalBahan').modal('show');
        }

        $('#tabelBahan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                info: false,
                ajax: {
                    url: "{{ route('bahan.show', $antrian->ticket_order) }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'nama_bahan', name: 'nama_bahan'},
                    {data: 'harga', name: 'harga'},
                    {data: 'note', name: 'note'},
                    {data: 'aksi', name: 'aksi'},
                ],
            });

        function deleteBahan(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Bahan akan dihapus dari daftar biaya produksi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('bahan') }}/"+id,
                        type: "POST",
                        data: {
                            "_method": "DELETE",
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            //muncul toast success
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Bahan berhasil dihapus'
                            });
            
                            //ajax reload table
                            $('#tabelBahan').DataTable().ajax.reload();
                            //refresh ajax dari route bahan.total
                            $.ajax({
                                url: "{{ route('bahan.total', $antrian->ticket_order) }}",
                                type: "GET",
                                success: function(response) {
                                    $('#bahanTotal').html(response.total);
                                    $('#profit').html(response.profit);
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                }
                            });

                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            })
        }

        $(document).ready(function() {

            $.ajax({
                url: "{{ route('bahan.total', $antrian->ticket_order) }}",
                type: "GET",
                success: function(response) {
                    $('#bahanTotal').html(response.total);
                    $('#profit').html(response.profit);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

            //format rupiah menggunakan maskMoney
            $('#harga_bahan').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

            $('.keterangan').each(function() {
                var text = $(this).text();
                $(this).html(text.replace(/\n/g, '<br/>'));
            });

            $('#formBahan').on('submit', function(e){
                e.preventDefault();
                var nama_bahan = $('#nama_bahan').val();
                var harga_bahan = $('#harga_bahan').val();
                var note = $('#note').val();
                var ticket_order = $('#ticket_order').val();

                $.ajax({
                    url: "{{ route('bahan.store') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        nama_bahan: nama_bahan,
                        harga_bahan: harga_bahan,
                        note: note,
                        ticket_order: ticket_order
                    },
                    success: function(response) {
                        $('#modalBahan').modal('hide');
                        //muncul toast success
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Bahan berhasil ditambahkan'
                        });
        
                        //ajax reload table
                        $('#tabelBahan').DataTable().ajax.reload();
                        //refresh ajax dari route bahan.total
                        $.ajax({
                                url: "{{ route('bahan.total', $antrian->ticket_order) }}",
                                type: "GET",
                                success: function(response) {
                                    $('#bahanTotal').html(response.total);
                                    $('#profit').html(response.profit);
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                }
                            });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            })

            $('#tableItems').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                info: false,
                ajax: {
                    url: "{{ route('barang.show', $antrian->ticket_order) }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'nama_produk', name: 'nama_produk'},
                    {data: 'note', name: 'note'},
                    {data: 'harga', name: 'harga'},
                    {data: 'qty', name: 'qty'},
                    {data: 'subtotal', name: 'subtotal'},
                ],
            });
        });
    </script>
@endsection
