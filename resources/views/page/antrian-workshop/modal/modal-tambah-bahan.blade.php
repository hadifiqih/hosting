<div class="modal fade" id="modalBahan" tabindex="-1" aria-labelledby="modalBahan" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Tambah Bahan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form id="formBahan" enctype="multipart/form-data" action="">
                <div class="form-group">
                    <label for="nama_bahan">Nama Bahan<span class="text-danger">*</span></label>
                    <input type="text" name="nama_bahan" id="nama_bahan" class="form-control" placeholder="Nama Bahan" required>
                </div>
                <div class="form-group">
                    <label for="harga_bahan">Harga Bahan<span class="text-danger">*</span></label>
                    <input type="text" name="harga_bahan" id="harga_bahan" class="form-control" placeholder="Harga Bahan" required>
                </div>
                <div class="form-group">
                    <label for="total_harga_bahan">Note</label>
                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Note"></textarea>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <input id="btnTambahBahan" type="submit" class="btn btn-primary" value="Tambah">
        </div>
    </form>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->