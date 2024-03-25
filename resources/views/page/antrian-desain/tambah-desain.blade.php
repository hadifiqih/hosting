@extends('layouts.app')

@section('title', 'Tambah Desain | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Desain')

@section('breadcrumb', 'Tambah Desain')

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
        <form id="formTambahDesain" action="{{ route('storeAddDesain') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input id="sales_id" type="hidden" name="sales_id" value="{{ Auth::user()->sales->id }}">

            <div class="form-group">
                {{-- Judul Desain --}}
                <label for="judul">Judul Desain<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>

            <div class="form-group">
                <label for="kategoriProduk">Kategori Produk<span class="text-danger">*</span></label>
                <select class="custom-select" id="kategoriProduk" name="kategoriProduk" required>
                    <option value="" selected disabled>Pilih Kategori</option>
                    <option value="1">Stempel</option>
                    <option value="3">Advertising</option>
                    <option value="2">Non Stempel</option>
                    <option value="4">Digital Printing</option>
                </select>
            </div>

            <div class="form-group">
                <label for="job_id">Jenis Produk<span class="text-danger">*</span></label>
                <select class="custom-select" id="job_id" name="job_id" style="width: 100%" required>
                    
                </select>
            </div>

            <div class="form-group">
                <label for="ref_desain">Referensi Desain <span class="text-secondary text-xs">(Opsional)</span></label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="ref_desain" name="ref_desain">
                    <label class="custom-file-label" for="ref_desain">Pilih file</label>
                </div>
            </div>

            <div class="form-group">
                <label for="note">Keterangan<span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" id="note" name="note" required></textarea>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="prioritas" name="prioritas">
                    <label class="custom-control-label" for="prioritas">Prioritas</label>
                </div>
            </div>

            <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
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