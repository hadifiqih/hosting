@extends('layouts.app')

@section('title', 'Detail Project')

@section('breadcrumb', 'Detail Project')

@section('page', 'Antrian')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<style>
    .spesifikasi {
        white-space: pre-line;
    }

    @media (max-width: 767px) {
        .col-sm h5, .col-sm h6 {
            font-size: 14px;
        }
    }
</style>
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
                    $batas = new DateTime($antrian->dataKerja->tgl_selesai);
                    $selesai = new DateTime($antrian->finish_date);
                @endphp
                @if($antrian->status == 1 && $antrian->cabang_id == null)
                    <p class="text-danger"><i class="fas fa-circle"></i> <span class="ml-2">Belum Diantrikan</span></p>
                @elseif($antrian->status == 1 && $antrian->cabang_id != null)
                    <p class="text-primary"><i class="fas fa-circle"></i> <span class="ml-2">Sedang Dikerjakan</span></p>
                @elseif($antrian->status == 2 && $antrian->cabang_id != null)
                    <p class="text-success"><i class="fas fa-circle"></i> <span class="ml-2">Selesai : <strong>{{ date_format($selesai , 'd F Y - H:i')}}</strong></span></p>
                @endif
            </div>
            <div class="row ml-1">
                <p class="text-danger"><i class="fas fa-circle"></i> <span class="ml-2">Deadline Pengerjaan : <strong>{{ $batas != null ? date_format($batas, 'd F Y - H:i') : '-' }}</strong></span></p>
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
            <div class="row table-responsive">
                <div class="col">
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
                </tfoot>
            </table>
            </div>
        </div>
        <div class="row">
            <div class="col pr-4 mt-3">
                <h5><strong>Total Penjualan : </strong><span class="float-right font-weight-bold text-danger">Rp{{ number_format($total, 0, ',', '.') }}</span></h5>
                <h6>Ongkos Kirim<span class="float-right text-danger">Rp{{ !isset($pengiriman->ongkir ) ? '-' : number_format($pengiriman->ongkir, 0, ',', '.') }}</span></h6>
                <h6>Biaya Pasang<span class="float-right text-danger">Rp{{ number_format($antrian->pembayaran->biaya_pasang, 0, ',', '.') }}</span></h6>
                <h6>Biaya Packing<span class="float-right text-danger">Rp{{ number_format($antrian->pembayaran->biaya_packing, 0, ',', '.') }}</span></h6>
                @php
                    $ongkir = !isset($pengiriman->ongkir) ? 0 : $pengiriman->ongkir;
                    $biayaPasang = $antrian->pembayaran->biaya_pasang;
                    $biayaPacking = $antrian->pembayaran->biaya_packing;
                    $totalKeseluruhan = $total + $ongkir + $biayaPasang + $biayaPacking;
                @endphp
                <h5><strong>Total Keseluruhan : </strong><span class="float-right font-weight-bold text-danger" id="totalKeseluruhan">Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span></h5>
                <h6>Diskon<span class="float-right text-danger">-Rp{{ number_format($antrian->pembayaran->diskon, 0, ',', '.') }}</span></h6>
                @php
                    $diskon = $antrian->pembayaran->diskon;
                    $nominal = $antrian->pembayaran->dibayarkan;
                    $totalPendapatan = $totalKeseluruhan - $diskon;
                    $sisaBayar = $totalPendapatan - $nominal;
                @endphp
                <h5><strong>Total Pendapatan : </strong><span class="float-right font-weight-bold text-danger">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</span></h5>
                <h6>Dibayarkan<span class="float-right text-danger">-Rp{{ number_format($nominal, 0, ',', '.') }}</span></h6>
                <h5><strong>Sisa Pembayaran : </strong><span class="float-right font-weight-bold text-danger">Rp{{ number_format($sisaBayar, 0, ',', '.') }}</span></h5>
            </div>
        </div>
        <div class="row mt-2">
                @if($antrian->pembayaran->nominal_pelunasan == null)
                {{-- Alert --}}
                <div class="col-sm-12">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian!</strong> Pesanan ini belum lunas, silahkan konfirmasi pelunasan.
                        <button onclick="modalPelunasan({{ $antrian->ticket_order }})" type="button" class="btn btn-sm btn-danger float-right" style="display: {{ auth()->user()->role_id != 11 ? 'none' : '' }}" >Unggah Pelunasan</button>
                    </div>
                </div>
                @else
                <div class="col-sm-12">
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check mr-1"></i> <strong>Terima Kasih!</strong> Pesanan ini sudah lunas.
                    </div>
                </div>
                {{-- End Alert --}}
                @endif
        </div>
    </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-truck mr-2"></i> <strong>Informasi Pengiriman</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <h5><strong>Alamat Pengiriman</strong></h5>
                    <p>{{ !isset($pengiriman->alamat_pengiriman) ? '-' : $pengiriman->alamat_pengiriman }}</p>
                </div>
                <div class="col-md">
                    <h5><strong>Ekspedisi Pengiriman</strong></h5>
                    <p>{{ !isset($pengiriman->ekspedisi) ? '-' : $pengiriman->ekspedisi}}</p>
                </div>
                <div class="col-md">
                    <h5><strong>Biaya Pengiriman</strong></h5>
                    <p>Rp{{ !isset($pengiriman->ongkir) ? '-' : number_format($pengiriman->ongkir, 0, ',', '.') }}</p>
                </div>
                <div class="col-md">
                    <h5><strong>Resi (Airway Bill)</strong></h5>
                    <p>{{ !isset($pengiriman->no_resi) || $pengiriman->no_resi == null ? '-' : $pengiriman->no_resi }}</p>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard mr-2"></i> <strong>Catatan Admin Workshop</strong></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row ml-1">
                <p class="text-dark keterangan">{{ $antrian->admin_note == null ? '-' : $antrian->admin_note }}</p>
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
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis File</th>
                        <th>Produk</th>
                        <th>Nama File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($desainan as $file)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>File Cetak</td>
                        <td>{{ $file->job->job_name }}</td>
                        <td>{{ $file->file_cetak ?? 'File Belum Diupload' }}</td>
                        <td>
                            <a href="{{ route('design.downloadFile', $file->id) }}" class="btn btn-primary btn-sm @if($file->file_cetak == null) disabled @endif"><i class="fas fa-download"></i> Unduh</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                    <h5><strong>Operator</strong></h5>
                    @if(isset($antrian->dataKerja->operator_id))
                        @php
                            $operatorId = explode(',', $antrian->dataKerja->operator_id);
                            foreach ($operatorId as $item) {
                                if($item == 'r'){
                                    echo '<p class="text-primary mb-0"><i class="fas fa-circle"></i> Rekanan</p>';
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
                    @if(isset($antrian->dataKerja->finishing_id))
                        @php
                            $finisherId = explode(',', $antrian->dataKerja->finishing_id);
                            foreach ($finisherId as $item) {
                                if($item == 'r'){
                                    echo '<p class="text-primary mb-0"><i class="fas fa-circle"></i> Rekanan</p>';
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
                    @if(isset($antrian->dataKerja->qc_id))
                        @php
                            $qcId = explode(',', $antrian->dataKerja->qc_id);
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
                <div class="col-md table-responsive">
                    <h5><strong>Biaya Bahan</strong></h5>
                @if($antrian->done_production_at == null && auth()->user()->role_id == 10)
                    <button class="btn btn-primary btn-sm m-0" onclick="modalBahan()"><i class="fas fa-plus-circle"></i> Tambah</button>
                @endif
                <table id="tabelBahan" class="table table-responsive table-bordered mt-3">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Bahan</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Note</th>
                            <th scope="col">Orderan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-center">Total Biaya Bahan : <span class="text-danger" id="bahanTotal">{{ $totalBahan }}</span></th>
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
                            <th>Rp{{ number_format($biayaSales, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaDesain, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaPenanggungJawab, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaPekerjaan, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaBPJS, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaTransportasi, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaOverhead, 0, ',', '.') }}</th>
                            <th>Rp{{ number_format($biayaAlatListrik, 0, ',', '.') }}</th>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="8" class="text-center">Total Biaya Lainnya : <span class="text-danger" id="totalBiayaLain">Rp{{ number_format($biayaSales + $biayaDesain + $biayaPenanggungJawab + $biayaPekerjaan + $biayaBPJS + $biayaTransportasi + $biayaOverhead + $biayaAlatListrik, 0, ',', '.') }}</span></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="row">
                    <div class="col-sm">
                        <h5 class="font-weight-bold">Profit Perusahaan : <span class="text-success float-right" id="profit">Rp{{ number_format($profit, 0, ',', '.') }}</span></h5>
                        <h6>Omset : <span class="text-dark float-right" id="profit">Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span></h6>
                        <h6>Total Biaya Produksi : <span class="text-danger float-right" id="totalProduksi">-Rp{{ number_format($totalBiaya, 0, ',', '.')}}</span></h6>
                        <h6>Diupdate oleh : <span class="text-dark float-right">{{ $antrian->estimator_id == null ? '-' : $antrian->estimator->name }}</span></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="text-right">
                            @if(auth()->user()->role_id == 10)
                                @if($antrian->done_production_at == null)
                                <button class="btn btn-success btn-sm" onclick="tandaiSelesaiHitungBP()">Tandai Selesai <i class="fas fa-check"></i></button>
                                @else
                                <a href="{{ route('report.tampilBP', $antrian->ticket_order) }}" class="btn btn-warning btn-sm">Unduh BP<i class="fas fa-download"></i></a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('page.antrian-workshop.modal.modal-ref-acc')
    @includeIf('page.antrian-workshop.modal.modal-tambah-bahan')
    @includeIf('page.antrian-workshop.modal.modal-bukti-pembayaran')
    @includeIf('page.antrian-workshop.modal.modal-pelunasan')
</div>

@endsection

@section('script')
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>

    <script>
        //menampilkan modal pelunasan
        function modalPelunasan(id) {
            $('#modalPelunasan').modal('show');
            $('#modalPelunasan #ticketPelunasan').val(id);
        }
        //menampilkan modal gambar acc desain
        function modalRefACC() {
            $('#modalRefACC').modal('show');
        }

        function modalBuktiPembayaran() {
            $('#modalBuktiPembayaran').modal('show');
        }

        //menampilkan form modal tambah bahan
        function modalBahan() {
            $('#modalBahan').modal('show');
        }

        //fungsi untuk menandai selesai hitung BP
        function tandaiSelesaiHitungBP() {
            var omset = $('#totalKeseluruhan').text();

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Biaya Produksi akan disimpan dan tidak dapat diubah lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Tandai Selesai',
                cancelButtonText: 'Batal'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('biaya.produksi.update', $antrian->ticket_order) }}",
                        type: "POST",
                        data: {
                            _method: "PUT",
                            _token: "{{ csrf_token() }}",
                            omsetTotal: omset,
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
                                title: 'Biaya Produksi berhasil disimpan !'
                            });

                            //ajax reload table
                            $('#tabelBahan').DataTable().ajax.reload();
                            //refresh ajax dari route bahan.total
                            $.ajax({
                                url: "{{ route('bahan.total', $antrian->ticket_order) }}",
                                type: "GET",
                                success: function(response) {
                                    $('#totalProduksi').html(response.totalProduksi + ' (' + response.persenProduksi + ')');
                                    $('#bahanTotal').html(response.total);
                                    $('#profit').html(response.profit + ' (' + response.persenProfit + ')');
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

        //menampilkan daftar bahan produksi
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
                    {data: 'barang', name: 'barang'},
                    {data: 'aksi', name: 'aksi'},
                ],
            });

        //ajax untuk menghapus bahan
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
                                    $('#totalProduksi').html(response.totalProduksi + ' (' + response.persenProduksi + ')');
                                    $('#bahanTotal').html(response.total);
                                    $('#profit').html(response.profit + ' (' + response.persenProfit + ')');
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
            //maskMoney untuk nominal pelunasan pada modal
            $('#nominal').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

            //ONCHANGE JENIS PELUNASAN
            $('#jenisPelunasan').on('change', function() {
                var jenis = $(this).val();
                if(jenis == 'TF'){
                    $('#formFilePelunasan').show();
                    $('#filePelunasan').attr('required', true);
                }
                else if(jenis == 'TU'){
                    $('#formFilePelunasan').hide();
                    $('#filePelunasan').attr('required', false);
                }
            });

            $("#filePelunasan").on("change", function() {
                const file = $(this)[0].files[0];

                if (file) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    $("#judulTampilan").show();
                    $("#preview-image").attr("src", event.target.result);
                };

                reader.readAsDataURL(file);
                } else {
                    $("#judulTampilan").hide();
                    $("#preview-image").attr("src", "");
                }
            });

            //ajax untuk menampilkan total biaya bahan
            $.ajax({
                url: "{{ route('bahan.total', $antrian->ticket_order) }}",
                type: "GET",
                success: function(response) {
                    $('#totalProduksi').html(response.totalProduksi + ' (' + response.persenProduksi + ')');
                    $('#bahanTotal').html(response.total);
                    $('#profit').html(response.profit + ' (' + response.persenProfit + ')');
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

            //ajax untuk menambahkan bahan
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
                                    $('#totalProduksi').html(response.totalProduksi + ' (' + response.persenProduksi + ')');
                                    $('#bahanTotal').html(response.total);
                                    $('#profit').html(response.profit + ' (' + response.persenProfit + ')');
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

            $('#nominal').on('keyup', function() {
                var nominal = $(this).val();
                // hilangkan Rp dan titik lalu ubah ke integer
                nominal = parseInt(nominal.replace(/Rp|\.|/g, ''));
                var sisaBayar = {{ $sisaBayar }};
                if(nominal > sisaBayar){
                    $('#errorNominal').text('Nominal tidak boleh melebihi sisa pembayaran!');
                    $('#btnPelunasan').attr('disabled', true);
                }else{
                    $('#errorNominal').text('');
                    $('#btnPelunasan').attr('disabled', false);
                }
            });

            //ajax untuk menampilkan barang yang dipesan
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
