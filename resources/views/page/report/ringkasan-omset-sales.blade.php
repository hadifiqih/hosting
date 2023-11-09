@extends('layouts.app')

@section('title', 'Omset Per Cabang')

@section('username', Auth::user()->name)

@section('page', 'Laporan')

@section('breadcrumb', 'Ringkasan Penjualan')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Filter</h3>
            </div>
            <div class="card-body">
                    <form action="{{ route('ringkasan.omsetSalesFilter') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    @if($isFilter == true)
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $date }}" disabled>
                                    @else
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sales">Sales</label>
                                    @if($isFilter == false)
                                    <select name="sales" id="sales" class="form-control" required>
                                        <option value="">-- Pilih Sales --</option>
                                        @foreach ($listSales as $sales)
                                            <option value="{{ $sales->id }}">{{ $sales->sales_name }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" name="sales" id="sales" class="form-control" value="{{ $salesName->sales_name }}" disabled>
                                    @endif
                                </div>
                                @if($isFilter == false)
                                <button type="submit" class="btn btn-primary float-right px-3">Filter</button>
                                @else
                                <a href="{{ route('ringkasan.omsetSales') }}" class="btn btn-danger float-right px-3">Reset</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Ringkasan Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <table id="tableRingkasan" class="table table-borderless table-hover table-responsive" style="width: 100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Tanggal Order</th>
                                    <th>Ticket Order</th>
                                    <th>Sales</th>
                                    <th>Pelanggan</th>
                                    <th>Jenis Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Produk</th>
                                    <th>Deadline</th>
                                    <th>File Desain</th>
                                    <th>Pelunasan</th>
                                </tr>
                            </thead>
                        </table>
                        @includeIf('page.report.modal-detail-antrian')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let table;
        let rupiah = Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

        function rupiahFormat(value) {
            //format in rupiah
            let result = rupiah.format(value);
            //remove decimal
            result = result.replace(/\,00$/, '');
            
            return result;
        }

        function lihatAntrian(ticket){
            $('#modalDetail').modal('show');
            $('#modalDetail .modal-title').text('Detail Antrian #' + ticket);

            $.get('/antrian/' + ticket + '/show')
                .done((response) => {
                    let tanggalOrder = response.created_at;

                    //reset clone a pada tempat
                    $("#modalDetail").find(".tempata").remove();
                    $("#modalDetail").find(".mesina").remove();

                    // Set the value of tanggalOrder to the formatted date
                    $('#modalDetail [name=tanggalOrder]').val(tanggalOrder.split('T')[0] + ' ' + tanggalOrder.split('T')[1].split('.')[0]);
                    $('#modalDetail [name=nama-project]').val(response.order.title);
                    $('#modalDetail [name=sales]').val(response.sales.sales_name);
                    $('#modalDetail [name=nama-pelanggan]').val(response.customer.nama);
                    $('#modalDetail [name=telepon]').val(response.customer.telepon);
                    $('#modalDetail [name=alamat]').val(response.customer.alamat);
                    $('#modalDetail [name=sumber-pelanggan]').val(response.customer.infoPelanggan);
                    $('#modalDetail [name=nama-produk]').val(response.job.job_name);
                    $('#modalDetail [name=jumlah-produk]').val(response.qty);
                    $('#modalDetail [name=keterangan]').val(response.note);
                    $('#modalDetail [name=mulai]').val(response.start_job);
                    $('#modalDetail [name=deadline]').val(response.end_job);
                    $('#modalDetail [name=nominal-omset]').val(rupiahFormat(response.omset));
                    $('#modalDetail [name=harga-produk]').val(rupiahFormat(response.harga_produk));
                    $('#modalDetail [name=status-pembayaran]').val(response.payment.payment_status);
                    $('#modalDetail [name=metode]').val(response.payment.payment_method);
                    $('#modalDetail [name=bayar]').val(rupiahFormat(response.payment.payment_amount));
                    $('#modalDetail [name=sisa]').val(rupiahFormat(response.payment.remaining_payment));
                    $('#modalDetail [name=pasang]').val(rupiahFormat(response.payment.installation_cost));
                    $('#modalDetail [name=pengiriman]').val(rupiahFormat(response.payment.shipping_cost));
                    $('#modalDetail [name=alamat-kirim]').val(response.alamat_pengiriman);

                    var spans = $("#modalDetail").find(".tempat");
                    var listTempat = response.working_at;
                    var a = $("<a class='btn btn-sm btn-danger ml-2 mr-2 tempata'></a>");
                    //Jika listTempat tidak kosong ada tanda koma, lakukan split, jika tidak ada koma langsung tambahkan a setelah spans
                    if(listTempat.includes(',')){
                        var tempat = listTempat.split(',');
                        for(var i = 0; i < tempat.length; i++){
                            a.clone().text(tempat[i]).appendTo(spans);
                        }
                    }else{
                        a.clone().text(listTempat).appendTo(spans);
                    }

                    var spans2 = $("#modalDetail").find(".mesin");
                    var listMesin = response.machine_code;
                    var b = $("<a class='btn btn-sm btn-danger ml-2 mr-2 mesina'></a>");
                    if(listMesin.includes(',')){
                        var mesin = listMesin.split(',');
                        for(var i = 0; i < mesin.length; i++){
                            b.clone().text(mesin[i]).appendTo(spans2);
                        }
                    }


                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });

            $.get('/antrian/' + ticket + '/order')
                .done((response) => {
                    $('#modalDetail [name=desainer]').val(response.employee.name);
                })
        }


        function lihatPelunasan(ticket){
            $('#modalPelunasan').modal('show');
            $('#modalPelunasan .modal-title').text('Detail Pelunasan #' + ticket);
        }

        $(function() {
            $('#tableRingkasan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                ajax: {
                    url: "{{ route('ringkasan.omsetSales') }}",
                },
                columns: [
                    {data : 'created_at', name: 'Tanggal Order'},
                    {data : 'ticket_order', name: 'Ticket Order'},
                    {data : 'sales', name: 'Sales'},
                    {data : 'customer', name: 'Pelanggan'},
                    {data : 'job', name: 'Jenis Produk'},
                    {data : 'qty', name: 'Qty'},
                    {data : 'harga_produk', name: 'Harga Produk'},
                    {data : 'end_job', name: 'Deadline'},
                    {data : 'file_cetak', name: 'File Desain'},
                    {data : 'action', name: 'Pelunasan'},
                ]
            });
        });

        //Menutup modal saat modal lainnya dibuka
        $('.modal').on('show.bs.modal', function (e) {
                $('.modal').not($(this)).each(function () {
                    $(this).modal('hide');
                });
            });

    </script>
@endsection
