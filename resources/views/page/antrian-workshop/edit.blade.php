@extends('layouts.app')

@section('title', 'Edit Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Edit Antrian')

@section('content')
<style>
    .spesifikasi {
        white-space: pre-line;
    }
</style>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Informasi Produk</h2>
            </div>
            <div class="card-body">
            {{-- Data Barang --}}
            <div class="table-responsive">
                <table id="tabelBarang" class="table table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Catatan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th id="jumlahBarang">{{ $totalBarang }}</th>
                            <th id="jumlahHargaBarang">Rp{{ $totalHargaBarang }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title">Penugasan Pengerjaan</h2>
            </div>
            <div class="card-body">
    <form id="formEditAntrian" action="{{ route('antrian.update', $antrian->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row ml-1">
        <h6 class="font-weight-bold">Pilih Operator :</h6>
    </div>
    @if($operators != null)
    <div class="row">
        @foreach($operators as $operator)
        <div class="col-md-3">
            <div class="form-group">
                <div class="form-check">
                    @php
                        $isCheckedOperator = in_array($operator->id, $operatorId) ? 'checked' : '';
                    @endphp
                    <input name="operator_id[]" value="{{ $operator->id }}" class="form-check-input" type="checkbox" {{ $isCheckedOperator }}>
                    <label class="form-check-label">{{ $operator->name }}</label>
                </div>
            </div>
        </div>  
        @endforeach
        <div class="col-md-3">
            <div class="form-group">
                <div class="form-check">
                    @php
                        $isOprRekanan = in_array('r', $operatorId) ? 'checked' : '';
                    @endphp
                    <input name="operator_id[]" value="r" class="form-check-input" type="checkbox" {{ $isOprRekanan }}>
                    <label class="form-check-label"><span>Rekanan</span></label>
                </div>
            </div>
        </div>
    </div>
    @else
    -
    @endif
    <hr>

    <div class="row ml-1">
        <h6 class="font-weight-bold">Pilih Finishing :</h6>
    </div>
    @if($operators != null)
    <div class="row">
        @foreach($operators as $operator)
        <div class="col-md-3">
            <div class="form-group">
                <div class="form-check">
                    @php
                        $isCheckedFinishing = in_array($operator->id, $finishingId) ? 'checked' : '';
                    @endphp
                    <input name="finishing_id[]" value="{{ $operator->id }}" class="form-check-input" type="checkbox" {{ $isCheckedFinishing }}>
                    <label class="form-check-label">{{ $operator->name }}</label>
                </div>
            </div>
        </div>  
        @endforeach
        <div class="col-md-3">
            <div class="form-group">
                <div class="form-check">
                    @php
                        $isFinRekanan = in_array('r', $finishingId) ? 'checked' : '';
                    @endphp
                    <input name="finishing_id[]" value="{{ $operator->id }}" class="form-check-input" type="checkbox" {{ $isFinRekanan }}>
                    <label class="form-check-label"><span>Rekanan</span></label>
                </div>
            </div>
        </div>
    </div>
    @else
    -
    @endif
    <hr>

    <div class="row ml-1">
        <h6 class="font-weight-bold">Pilih QC : </h6>
    </div>
    @if($qualitys != null)
    <div class="row">
        @foreach($qualitys as $qc)
        <div class="col-md-3">
            <div class="form-group">
                <div class="form-check">
                    @php
                        $isCheckedQC = is_array($qualityId) && in_array($qc->id, $qualityId);
                    @endphp
                    <input name="qc_id[]" value="{{ $qc->id }}" class="form-check-input" type="checkbox" {{ $isCheckedQC ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $qc->name }}</label>
                </div>
            </div>
        </div>  
        @endforeach
    </div>
    @else
    -
    @endif
    <hr>

    {{-- Memilih tempat pengerjaan di Surabaya, Kediri, Malang --}}
    <div class="mb-3">
        {{-- Pilih Tempat Pengerjaan Menggunakan Checkbox --}}
        <div class="row ml-1">
            <h6 class="font-weight-bold">Tempat Pengerjaan :</h6>
        </div>
        <div class="row">
        @foreach($tempatCabang as $cabang => $value)
            <div class="col-md-3">
                <div class="form-check">
                    @php
                        $isCheckedCabang = is_array($cabangId) && in_array($cabang, $cabangId);
                    @endphp
                    <input name="cabang_id[]" value="{{ $cabang }}" class="form-check-input" type="checkbox" {{ $isCheckedCabang ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $value }}</label>
                </div>
            </div>
        @endforeach
        </div>
    </div>

    {{-- Memilih jenis mesin berdasarkan tempat --}}
    <div class="mb-3">
        <div class="form-group">
            <label>Jenis Mesin :</label>
            <select id="cariMesin" class="form-control select2" multiple="multiple" name="jenisMesin[]" style="width: 100%">
            </select>
            @if($antrian->cabang_id != null)
                <p class="text-sm text-danger font-italic mt-1">*Jika tidak ada perubahan, <strong>biarkan kosong.</strong></p>
            @endif
        </div>
    </div>

    <div class="mb-3">
        {{-- Masukkan start job --}}
        <label for="start_job" class="form-label">Mulai<span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="start_job" aria-describedby="start_job" name="start_job" value="{{ $antrian->dataKerja->tgl_mulai }}" required>
    </div>
    <div class="mb-3">
        {{-- Masukkan Deadline --}}
        <label for="deadline" class="form-label">Deadline<span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="deadline" aria-describedby="deadline" name="end_job" value="{{ $antrian->dataKerja->tgl_selesai }}" required>
    </div>
    <div class="mb-3">
        {{-- Masukkan Keterangan --}}
        <label for="keterangan" class="form-label">Catatan Admin <span class="text-muted font-italic text-sm">(Opsional)</span></label>
        <textarea class="form-control" id="keterangan" rows="3" name="admin_note">{{ $antrian->admin_note != null ? $antrian->admin_note : "" }}</textarea>
    </div>

    <input type="hidden" name="isEdited" value="{{ $isEdited }}">
    <div class="d-flex align-items-center">
        <input id="submitEdit" type="submit" class="btn btn-primary" value="Submit"><span id="loader" class="loader m-2" style="display: none"></span>
    </div>
</form>
</div>
</div>
</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Tabel Barang Datatables
        $('#tabelBarang').DataTable({
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
        

        $('#formEditAntrian').on('submit', function() {
            $(this).find('input[type="submit"]').prop('disabled', true);
            $('#loader').show();
        });

        $('.select2').select2({
            placeholder: "Pilih Mesin",
            allowClear: true,
            ajax: {
                url: '{{ route("mesin.search") }}',
                dataType: 'json',
                delay: 250, 
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
    });


</script>
@endsection
