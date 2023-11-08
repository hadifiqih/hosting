<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
      <form action="" method="POST">
        @csrf
        @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-group">
                  <label for="sales">Sales</label>
                  <select class="form-control" id="sales" name="sales" required>
                    <option value="0">-- Pilih Sales --</option>
                    @foreach($salesAll as $key => $sales)
                        <option value="{{ $key }}">{{ $sales }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <label for="namaPelanggan">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="namaPelanggan" placeholder="Masukkan Nama Pelanggan" name="namaPelanggan" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" placeholder="Masukkan Telepon" name="telepon" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" rows="3" placeholder="Masukkan Alamat" name="alamat" required></textarea>
                </div>
                <div class="form-group">
                    <label for="sumber">Info Pelanggan</label>
                    <input type="text" class="form-control" id="sumber" placeholder="Masukkan Info Pelanggan" name="infoPelanggan" required>
                </div>
                <div class="form-group">
                    <label for="instansi">Instansi</label>
                    <input type="text" class="form-control" id="instansi" placeholder="Masukkan Instansi" name="instansi" required>
                </div>
                <div class="form-group">
                    <label for="wilayah">Wilayah</label>
                    <input type="text" class="form-control" id="wilayah" placeholder="Masukkan Wilayah" name="wilayah" required>
                </div>
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary btn-sm submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </form>
    </div>
    </
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->