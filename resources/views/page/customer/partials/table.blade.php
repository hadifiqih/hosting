<table id="customer-table" class="table table-borderless table-hover table-responsive">
    <thead class="thead-dark">
        <th>#</th>
        <th>Sales</th>
        <th>Nama</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Sumber</th>
        <th>Instansi</th>
        <th>Wilayah</th>
        <th>Aksi</th>
    </thead>
</table>

@section('script')
<script>
    let table;

    function editForm(url) {
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Edit Pelanggan');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_produk]').focus();
    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_produk]').val(response.nama_produk);
            $('#modal-form [name=id_kategori]').val(response.id_kategori);
            $('#modal-form [name=merk]').val(response.merk);
            $('#modal-form [name=harga_beli]').val(response.harga_beli);
            $('#modal-form [name=harga_jual]').val(response.harga_jual);
            $('#modal-form [name=diskon]').val(response.diskon);
            $('#modal-form [name=stok]').val(response.stok);
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan data');
            return;
        });
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
    });
</script>
@endsection
