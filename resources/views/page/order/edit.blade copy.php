@extends('layouts.app')

@section('title', 'Desain Baru | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian Desain')

@section('breadcrumb', 'Tambah Desain')

@section('content')

<div class="card card-warning">
  <h5 class="card-header">Edit Antrian Desain</h5>

  {{-- Tampilkan jika ada error apapun --}}
  @if ($errors->any())
  <div class="alert alert-danger" role="alert">
    <ul>
      {{-- Tampilkan semua error yang ada --}}
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="card-body">
    <form id="formEditOrder" action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
      {{-- Inputan untuk judul desain --}}
      <div class="mb-3">
        <label for="title" class="form-label">Judul Project (Keyword) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Contoh : Es Teh Indonesia" value="{{ $order->title }}" required>
      </div>

      {{-- Input Sales bertipe Hidden --}}
      <input type="hidden" name="sales" value="{{ $sales->id }}">

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="custom-select rounded-2" name="kategori" id="kategori" required>
            @if($job != null)
            <option value="0" disabled>--Pilih Kategori--</option>
            <option value="1" {{ $job->kategori_id == "1" && $job != null ? 'selected' : "" }}>Stempel</option>
            <option value="2" {{ $job->kategori_id == "2" && $job != null ? 'selected' : "" }}>Non Stempel</option>
            <option value="3" {{ $job->kategori_id == "3" && $job != null ? 'selected' : "" }}>Advertising</option>
            <option value="4" {{ $job->kategori_id == "4" && $job != null ? 'selected' : "" }}>Digital Printing</option>
            <option value="5" {{ $job->kategori_id == "5" && $job != null ? 'selected' : "" }}>Jasa Servis</option>
            @else
            <option value="0" selected disabled>--Pilih Kategori--</option>
            <option value="1">Stempel</option>
            <option value="2">Non Stempel</option>
            <option value="3">Advertising</option>
            <option value="4">Digital Printing</option>
            <option value="5">Jasa Servis</option>
            @endif
        </select>
      </div>

      <div class="mb-3">
        <label for="job" class="form-label">Jenis Produk <span class="text-danger">*</span></label>
        <select class="custom-select rounded-2" name="job" id="job" required>
            <option disabled>--Pilih Jenis Produk--</option>
            @foreach ($jobs as $job)
              <option value="{{ $job->id }}" {{ $order->job_id == $job->id ? 'selected' : "" }}>{{ $job->job_name }}</option>
            @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ $order->description }}</textarea>
      </div>

      {{-- Uplaoad File Referensi Desain --}}
      <div class="mb-3" id="refdesain">
        <h6><strong>File Ref. Desain </strong><span class="font-italic text-danger text-sm">*(Jika tidak ada perubahan, hiraukan / kosongkan ini)</span></h6>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="refdesain" name="refdesain">
            <label class="custom-file-label" for="refDesain"><span class="text-secondary">Pilih File..</span></label>
          </div>
        </div>
      </div>

      <h6 class="font-weight-bold">Jenis Pekerjaan <span class="text-danger">*</span></h6>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio1" value="baru" {{ $order->type_work == 'baru' ? 'checked' : '' }} required>
        <label class="form-check-label" for="inlineRadio1">Desain Baru</label>
      </div>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio2" value="edit" {{ $order->type_work == 'edit' ? 'checked' : '' }} required>
        <label class="form-check-label" for="inlineRadio2">Edit Desain</label>
      </div>

      {{-- Menangani jika ada error file tidak ditemukan --}}
      @if ($errors->has('design'))
      <div class="alert alert-danger" role="alert">
        {{ $errors->first('design') }}
      </div>
      @endif

      {{-- Checkbox untuk pesanan prioritas / tidak --}}
      <div class="mb-3">
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input custom-control-input-danger" type="checkbox" id="defaultCheck" value="1" name="priority" {{ $order->is_priority == 1 ? 'checked' : '' }}>
          <label class="custom-control-label" for="defaultCheck">
            Prioritas
          </label>
        </div>
      </div>

      {{-- Tombol Submit --}}
        <div class="d-flex align-items-center">
            <input type="submit" class="btn btn-primary submitButton"><span id="loader" class="loader" style="display: none;"></span>
        </div>
    </form>
  </div>
  {{-- Modal Tambah Jenis Produk --}}
  <div class="modal fade" id="exampleModalProduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalProdukLabel">Tambah Produk Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="produkForm" action="{{ route('tambahProdukByModal') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="modalNamaPekerjaan">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalNamaPekerjaan" placeholder="Contoh : Stempel Cup Plastik" name="modalNamaProduk" required>
            </div>
            <div class="form-group">
                <label for="modalJenisPekerjaan">Kategori Produk <span class="text-danger">*</span></label>
                <select class="custom-select rounded-0" id="modalJenisPekerjaan" name="modalJenisProduk" required>
                    <option value="Stempel">Stempel</option>
                    <option value="Advertising">Advertising</option>
                    <option value="Non Stempel">Non Stempel</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <input type="submit" class="btn btn-primary" value="Tambah Produk"><span id="loader" class="loader" style="display: none;"></span>
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
    bsCustomFileInput.init();

    $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'inlineRadio1') {
                $('#refDesain').show();
            } else {
                $('#refDesain').hide();
            }
        });

    $('#kategori').on('change', function() {
      $('#job').empty();
      $('#job').append('<option selected disabled>--Pilih Jenis Produk--</option>');

      $('#job').select2({
        placeholder: 'Pilih Jenis Produk',
        ajax : {
          url: "{{ route('getJobsByCategory') }}",
          type: "GET",
          dataType: 'json',
          delay: 250,
          data: {
            kategoriProduk: $(this).val()
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

    //saat formEditOrder di submit, maka tombol submit akan disabled & loader akan muncul
    $('#formEditOrder').on('submit', function() {
      $(this).find('input[type="submit"]').prop('disabled', true);
      $('#loader').show();
    });

    //Jika radio button desain baru checked, maka tampilkan inputan refdesain
    if ($('#inlineRadio1').is(':checked')) {
      $('#refDesain').show();
    }else{
      $('#refDesain').hide();
    }

    $('#produkForm').submit(function(e) {
      e.preventDefault();

      $('.submitButton').prop('disabled', true);
      $('.loader').show();

      var namaProduk = $('#modalNamaPekerjaan').val();
      var jenisProduk = $('#modalJenisPekerjaan').val();
      var keterangan = $('#modalKeterangan').val();
      $.ajax({
        url: "{{ route('tambahProdukByModal') }}",
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "namaProduk": namaProduk,
          "jenisProduk": jenisProduk,
          "keterangan": keterangan
        },
        success: function(data) {
          $('#exampleModalProduk').modal('hide');
          //menghapus inputan pada modal
            $('#modalNamaPekerjaan').val('');
            $('#modalJenisPekerjaan').val('');
            $('#modalKeterangan').val('');
          //muncul sweetalert2 success
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                showConfirmButton: false,
                timer: 3000
            });

            //reload halaman
            setInterval(() => {
                location.reload();
            }, 3500);

        },
        error: function(data) {
          //muncul sweetalert2 error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
            });
        }
      });
    });

  });
</script>

@endsection
