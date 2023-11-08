@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('breadcrumb', 'Pelanggan')

@section('page', 'Data Pelanggan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        @includeIf('page.customer.partials.table')
                    </div>
                    @includeIf('page.customer.partials.modal-edit')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    let table;

    var toastr = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Data Pelanggan');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=namaPelanggan]').focus();
            
            $.get(url)
                .done((response) => {
                    $('#modal-form [name=sales]').val(response.sales_id);
                    $('#modal-form [name=namaPelanggan]').val(response.nama);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                    $('#modal-form [name=infoPelanggan]').val(response.infoPelanggan);
                    $('#modal-form [name=instansi]').val(response.instansi);
                    $('#modal-form [name=wilayah]').val(response.wilayah);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

    function deleteForm(url){
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // Memberi notifikasi sukses dengan toastr
                        toastr.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !'
                        })

                        // Menghapus data pada datatable
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        // Memberi notifikasi error dengan toastr
                        toastr.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan !'
                        })

                        // Menghapus data pada datatable
                        table.ajax.reload();
                    }
                });
            }
        })
    }

    $(function () {

        table = $('#customer-table').DataTable({
            responsive: true,
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('customer.indexJson') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'sales', name: 'Sales'},
                {data: 'nama', name: 'Nama'},
                {data: 'telepon', name: 'Telepon'},
                {data: 'alamat', name: 'Alamat'},
                {data: 'infoPelanggan', name: 'Info Pelanggan'},
                {data: 'instansi', name: 'Instansi'},
                {data: 'wilayah', name: 'Wilayah'},
                {data: 'action', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').on('submit', function (e) {
            // Menghilangkan fungsi default form submit
            e.preventDefault();
            
            // Mengambil url action pada form
            let url = $('#modal-form form').attr('action');

            // Mengambil data pada form
            let data = $('#modal-form form').serialize();

            // Mengirim data ke url action dengan method PUT
            $.ajax({
                url: url,
                method: 'PUT',
                data: data,
                success: function (response) {
                    // Menutup modal
                    $('#modal-form').modal('hide');

                    // Memberi notifikasi sukses dengan toastr
                    toastr.fire({
                        icon: 'success',
                        title: 'Berhasil diubah !'
                    })

                    // Menghapus data pada datatable
                    table.ajax.reload();
                },
                error: function (xhr) {
                    // Menutup modal
                    $('#modal-form').modal('hide');

                    // Memberi notifikasi error dengan toastr
                    toastr.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan !'
                    })

                    // Menghapus data pada datatable
                    table.ajax.reload();
                }
            });
        });
    });
</script>
@endsection
