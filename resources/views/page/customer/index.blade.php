@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('breadcrumb', 'Pelanggan')

@section('page', 'Data Pelanggan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @includeIf('page.customer.partials.search')
            </div>
        </div>
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
        function editForm(url) {
                $('#modal-form').modal('show');
                $('#modal-form .modal-title').text('Edit Data Pelanggan');

                $('#modal-form form')[0].reset();
                var customerID = $('#btnModal').data('id');
                $('#modal-form form').attr('action', '');
                $('#modal-form [name=_method]').val('put');
                $('#modal-form [name=namaPelanggan]').focus();

                $.get(url)
                    .done((response) => {
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
    </script>
@endsection
