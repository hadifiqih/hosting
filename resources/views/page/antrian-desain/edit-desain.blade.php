@extends('layouts.app')

@section('title', 'Edit Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Desain')

@section('breadcrumb', 'Edit Desain')

@section('content')
<style>
    .select2-selection {
      height: 38px !important;
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Desain</h5>
        </div>
        <div class="card-body">
        <form id="formTambahDesain" action="{{ route('updateDesain', $design->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input id="sales_id" type="hidden" name="sales_id" value="{{ Auth::user()->sales->id }}">

            <div class="form-group">
                {{-- Judul Desain --}}
                <label for="judul">Judul Desain<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ $design->judul }}" required>
            </div>

            <div class="form-group">
                @php
                    $kategoriProduk = $design->job->kategori->id;
                @endphp
                <label for="kategoriProduk">Kategori Produk<span class="text-danger">*</span></label>
                <select class="custom-select" id="kategoriProduk" name="kategoriProduk" required>
                    <option value="" selected disabled>Pilih Kategori</option>
                    <option value="1" {{ $kategoriProduk == 1 ? 'selected' : '' }}>Stempel</option>
                    <option value="3" {{ $kategoriProduk == 3 ? 'selected' : '' }}>Advertising</option>
                    <option value="2" {{ $kategoriProduk == 2 ? 'selected' : '' }}>Non Stempel</option>
                    <option value="4" {{ $kategoriProduk == 4 ? 'selected' : '' }}>Digital Printing</option>
                </select>
            </div>

            <div class="form-group">
                <label for="job_id">Jenis Produk<span class="text-danger">*</span></label>
                <select class="custom-select" id="job_id" name="job_id" style="width: 100%" required>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ $design->job_id == $job->id ? 'selected' : '' }}>{{ $job->job_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-1">
                <label for="ref_desain">Referensi Desain <span class="text-secondary text-xs">(Opsional)</span></label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="ref_desain" name="ref_desain">
                    <label class="custom-file-label" for="ref_desain">Pilih file</label>
                </div>
            </div>
            <p class="text-secondary text-sm font-italic mt-0">* Jika terdapat perubahan pada referensi desain, silahkan upload kembali pada form diatas.</p>
            
            @if($design->ref_desain)
                <div class="form-group">
                    <label>File yang sudah diunggah:</label><br>
                    <div class="card">
                    @if(pathinfo($design->ref_desain, PATHINFO_EXTENSION) === 'jpg' || pathinfo($design->ref_desain, PATHINFO_EXTENSION) === 'png' || pathinfo($design->ref_desain, PATHINFO_EXTENSION) === 'jpeg')
                    <a href="{{ asset('storage/ref-desain/'. $design->ref_desain) }}" target="_blank"><img src="{{ asset('storage/ref-desain/'. $design->ref_desain) }}" alt="Preview" style="max-width: 200px; max-height: 200px;"></a>
                    @else
                    <a href="{{ asset('storage/ref-desain/'. $design->ref_desain) }}" target="_blank">Lihat file</a>
                    @endif
                    </div>
                </div>
            @endif
            
            <div class="form-group">
                <label for="note">Keterangan<span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" id="note" name="note" required>{{ $design->note }}</textarea>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="prioritas" name="prioritas" {{ $design->prioritas == 1 ? 'checked' : ''  }}>
                    <label class="custom-control-label" for="prioritas">Prioritas</label>
                </div>
            </div>

            <button type="submit" class="btn btn-sm btn-primary">Terapkan</button>
        </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#kategoriProduk').on('change', function() {
      var kategoriProduk = $(this).val();

      //mengosongkan inputan jenis produk
      $('#job_id').val(null).trigger('change');

      $('#job_id').select2({
        placeholder: 'Pilih Jenis Produk',
        ajax : {
          url: "{{ route('job.searchByCategory') }}",
          dataType: 'json',
          delay: 250,
            data: function(params) {
              return {
                kategoriProduk: kategoriProduk,
                q: params.term
              }
            },
            processResults: function (data) {
              return {
                results:  $.map(data, function (item) {
                  return {
                    text: item.job_name,
                    id: item.id
                  }
                })
              };
            },
            cache: true
          }
        });
    });
</script>
@endsection