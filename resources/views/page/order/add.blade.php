@extends('layouts.app')

@section('title', 'Desain Baru | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian Desain')

@section('breadcrumb', 'Tambah Desain')

@section('content')
<style>
  .select2-selection {
    height: 38px !important;
  }
</style>
<div class="card">
  <h5 class="card-header">Tambah Antrian Desain</h5>
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
        <button id="btnTambahProduk" type="button" class="btn btn-sm btn-primary">
          Tambah Produk
        </button>
        <table id="tableProduk" class="table table-bordered mt-3">
          <thead>
            <tr>
              <th>Jenis Produk</th>
              <th>Kategori Produk</th>
              <th>Referensi Desain</th>
              <th>Keterangan</th>
              <th>Jumlah</th>
            </tr>
          </thead>
        </table>
      </div>

      {{-- Checkbox untuk pesanan prioritas / tidak --}}
      <div class="form-group">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="prioritas" name="prioritas">
          <label class="custom-control-label" for="prioritas">Prioritas</label>
        </div>
      </div>

      {{-- Tombol Submit --}}
        <div class="d-flex align-items-center">
            <input type="submit" class="btn btn-sm btn-primary submitButton" value="Submit"><div id="loader" class="loader m-2" style="display: none;"></div>
        </div>
    </form>
  </div>
</div>
@include('page.order.modal.tambah-produk')
@endsection

@section('script')

<script>
  $(document).ready(function() {
    bsCustomFileInput.init();

    $('#btnTambahProduk').on('click', function() {
      $('#modalTambahProduk').modal('show');
    });

    $('#modalTambahProduk').on('hidden.bs.modal', function() {
      $('#formTambahProduk').trigger('reset');
    });

    $('#formTambahProduk').on('submit', function(e) {
      e.preventDefault();
      
      var dataInput = new FormData(this);

      $.ajax({
        url: "{{ route('simpanBarangDariDesain') }}",
        type: "POST",
        data: dataInput,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          $('#modalTambahProduk').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: data.message,
            showConfirmButton: false,
            timer: 3000
          });
          $('#tableProduk').DataTable().ajax.reload();
        },
        error: function(data) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan!',
          });
        }
      });

    });

    $('#tableProduk').DataTable({
      responsive: true,
      autoWidth: false,
      processing: true,
      serverSide: true,
      paging: false,
      searching: false,
      info: false,
      ajax: "{{ route('getAllJobs') }}",
      columns: [
        { data: 'jenis_produk', name: 'jenis_produk' },
        { data: 'kategori_produk', name: 'kategori_produk' },
        { data: 'referensi_desain', name: 'referensi_desain' },
        { data: 'keterangan', name: 'keterangan' },
        { data: 'jumlah', name: 'jumlah' },
      ]
    });

    //mengambil nilai kategori produk
    $('#kategoriProduk').on('change', function() {
      var kategoriProduk = $(this).val();
      $('#jenisProduk').select2({
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
