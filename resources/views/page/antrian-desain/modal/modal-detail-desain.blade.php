<div class="modal fade" id="detailWorking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 id="ticketDetail" class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="createdAtWorking">Waktu Dibuat</label>
                        <input type="text" class="form-control" id="createdAtWorking" name="createdAt" value="" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastUpdateWorking">Terakhir Diupdate</label>
                        <input type="text" class="form-control" id="lastUpdateWorking" name="lastUpdate" value="" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="namaSalesWorking">Nama Sales</label>
                <input type="text" class="form-control" id="namaSalesWorking" name="namaSales" value="" readonly>
            </div>
            <div class="form-group">
                <label for="judulDesainWorking">Judul Desain</label>
                <input type="text" class="form-control" id="judulDesainWorking" name="judulDesain" value="" readonly>
            </div>
            <div class="form-group">
                <label for="jenisProdukWorking">Jenis Produk</label>
                <input type="text" class="form-control" id="jenisProdukWorking" name="jenisPekerjaan" value="" readonly>
            </div>
            <div class="form-group">
                <label for="keteranganWorking">Keterangan</label>
                <textarea class="form-control" id="keteranganWorking" name="keterangan" rows="3" readonly></textarea>
            </div>
            <div class="form-group">
                <h6><strong>Referensi Desain</strong></h6><br>
                <img id="refgambar" class="img-fluid" alt="Preview Image">
            </div>
            <p class="text-muted font-italic">*Jika ada yang kurang jelas, bisa menghubungi sales yang bersangkutan</p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
    </div>
</div>