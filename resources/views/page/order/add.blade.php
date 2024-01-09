@extends('layouts.app')

@section('title', 'Desain Baru | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian Desain')

@section('breadcrumb', 'Tambah Desain')

@section('content')

<div class="card">
  <h5 class="card-header">Tambah Antrian Desain</h5>

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
    <form id="formOrder" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      {{-- Inputan untuk judul desain --}}
      <div class="mb-3">
        <label for="title" class="form-label">Judul Project (Keyword)<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Contoh : Es Teh Indonesia" required>
      </div>

      {{-- Input Sales bertipe Hidden --}}
      <input type="hidden" name="sales" value="{{ $sales->id }}">

      <div class="mb-3">
        <label for="job" class="form-label">Jenis Produk <span class="text-danger">*</span></label>
        <br>
        <select multiple="multiple" class="custom-select rounded-2" name="job[]" id="job" required style="width: 100%">

        </select>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Contoh : Ukuran 6x6cm, ditambah No. Telp : 08xxxxxxxx"></textarea>
      </div>

      <div class="form-group">
        <label for="refdesain">Gambar Referensi <span class="text-danger">*</span></label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="refdesain" name="refdesain" required>
            <label class="custom-file-label" for="refdesain">Pilih Gambar</label>
        </div>
      </div>

      <h6 class="font-weight-bold">Jenis Pekerjaan <span class="text-danger">*</span></h6>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio1" value="baru" required>
        <label class="form-check-label" for="inlineRadio1">Desain Baru</label>
      </div>

      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio2" value="edit" required>
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
          <input class="custom-control-input custom-control-input-danger" type="checkbox" id="defaultCheck" value="1" name="priority">
          <label class="custom-control-label" for="defaultCheck">
            Prioritas
          </label>
        </div>
      </div>

      {{-- Tombol Submit --}}
        <div class="d-flex align-items-center">
            <input type="submit" class="btn btn-primary submitButton" value="Submit"><div id="loader" class="loader m-2" style="display: none;"></div>
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
                <label for="modalNamaProduk">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalNamaProduk" placeholder="Contoh : Stempel Cup Plastik" name="modalNamaProduk" required>
            </div>
            <div class="form-group">
                <label for="modalJenisProduk">Kategori Produk <span class="text-danger">*</span></label>
                <select class="custom-select rounded-0" id="modalJenisProduk" name="modalJenisProduk" required>
                    <option selected disabled>--Pilih Kategori--</option>
                    <option value="Stempel">Stempel</option>
                    <option value="Advertising">Advertising</option>
                    <option value="Non Stempel">Non Stempel</option>
                    <option value="Digital Printing">Digital Printing</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <input type="submit" class="btn btn-primary submitButton" value="Tambah Produk"><div id="loader" class="loader" style="display: none;"></div>
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

    $('#job').select2({
      placeholder: 'Pilih Jenis Produk',
      ajax : {
        url: "{{ route('getJobsByCategory') }}",
        type: "GET",
        dataType: 'json',
        delay: 250,
        data: {
          kategori: $(this).val()
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

    $('#formOrder').on('submit', function(e) {
        e.preventDefault();
        $('.submitButton').attr('disabled', true);
        $('.loader').show();
        this.submit();
    });

    $('#produkForm').submit(function(e) {
      e.preventDefault();
      var modalNamaProduk = $('#modalNamaProduk').val();
      var modalJenisProduk = $('#modalJenisProduk').val();

      if(modalNamaProduk == '' || modalJenisProduk == '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap isi semua inputan!',
        });
        $('.submitButton').attr('disabled', false);
        $('.loader').hide();
        return false;
      }

      $.ajax({
        url: "{{ route('tambahProdukByModal') }}",
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "namaProduk": modalNamaProduk,
          "jenisProduk": modalJenisProduk,
        },
        success: function(data) {
          $('#exampleModalProduk').modal('hide');
          //menghapus inputan pada modal
            $('#modalNamaProduk').val('');
            $('#modalJenisProduk').val('');
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
            }, 2500);

        },
        error: function(data) {
          //muncul sweetalert2 error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
            });
        }
      });
    });
  });
</script>
@endsection
