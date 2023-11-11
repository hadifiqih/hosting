<!-- Modal -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="tanggalOrder">Tanggal Order</label>
                <input id="tanggalOrder" name="tanggalOrder" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-project">Nama Project</label>
                <input id="nama-project" name="nama-project" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sales">Sales</label>
                <input id="sales" name="sales" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-pelanggan">Nama Pelanggan</label>
                <input id="nama-pelanggan" name="nama-pelanggan" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="telepon">Telepon/WA</label>
                <input id="telepon" name="telepon" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Pelanggan</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" readonly></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="sumber-pelanggan">Sumber Pelanggan</label>
                <input id="sumber-pelanggan" name="sumber-pelanggan" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="nama-produk">Nama Produk</label>
                <input id="nama-produk" name="nama-produk" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="jumlah-produk">Jumlah Produk (Qty)</label>
                <input id="jumlah-produk" name="jumlah-produk" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="keterangan">Keterangan / Spesifikasi Produk</label>
                <textarea id="keterangan" name="keterangan" class="form-control" rows="6" readonly></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="mulai">Mulai</label>
                <input id="mulai" name="mulai" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="deadline">Deadline</label>
                <input id="deadline" name="deadline" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="desainer">Desainer</label>
                <input id="desainer" name="desainer" type="text" class="form-control" value="" readonly>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold tempat"> Tempat : </span>
            </div>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Operator</span>
                <p class="operator">

                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Finishing</span>
                <p class="finishing">
                
                </p>
            </div>
            <div class="col-md-4 rounded-2">
                <span class="form-label font-weight-bold">Pengawas / QC</span>
                <p class="pengawas">
                
                </p>
            </div>
            </div>
            </div>
            <hr>
            <div class="form-group">
                <span class="form-label font-weight-bold mesin">Mesin : </span>
                
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label" for="nominal-omset">Nominal Omset</label>
                <input id="nominal-omset" name="nominal-omset" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="harga-produk">Harga Produk</label>
                <input id="harga-produk" name="harga-produk" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="status-pembayaran">Status Pembayaran</label>
                <input id="status-pembayaran" name="status-pembayaran" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="metode">Metode Pembayaran</label>
                <input id="metode" name="metode" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="bayar">Nominal Pembayaran</label>
                <input id="bayar" name="bayar" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="sisa">Sisa Pembayaran</label>
                <input id="sisa" name="sisa" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pasang">Biaya Jasa Pasang</label>
                <input id="pasang" name="pasang" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="pengiriman">Biaya Jasa Pengiriman</label>
                <input id="pengiriman" name="pengiriman" type="text" class="form-control" value="" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat-kirim">Alamat Pengiriman</label>
                <textarea id="alamat-kirim" name="alamat-kirim" class="form-control" rows="6" readonly></textarea>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Preview ACC Desain</label>
                        <div class="text-muted font-italic file-cetak">
                            <button type="button" class="btn btn-sm btn-primary ml-3" data-toggle="modal" data-target="#modal-accdesain">Lihat</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>


