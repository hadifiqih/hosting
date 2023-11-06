<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="namaPelanggan">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="namaPelanggan" placeholder="Masukkan Nama Pelanggan" name="namaPelanggan">
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" placeholder="Masukkan Telepon" name="telepon">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" rows="3" placeholder="Masukkan Alamat" name="alamat"></textarea>
                </div>
                <div class="form-group">
                    <label for="sumber">Info Pelanggan</label>
                    <input type="text" class="form-control" id="sumber" placeholder="Masukkan Info Pelanggan" name="infoPelanggan">
                </div>
                <div class="form-group">
                    <label for="instansi">Instansi</label>
                    <input type="text" class="form-control" id="instansi" placeholder="Masukkan Instansi" name="instansi">
                </div>
                <div class="form-group">
                    <label for="wilayah">Wilayah</label>
                    <input type="text" class="form-control" id="wilayah" placeholder="Masukkan Wilayah" name="wilayah">
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
