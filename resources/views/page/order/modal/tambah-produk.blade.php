<div class="modal fade" id="modalTambahProduk">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Produk</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formTambahProduk" action="{{ route('barang.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_order" value="{{ $ticketOrder }}">

                <div class="form-group">
                    <label for="kategoriProduk">Kategori Produk<span class="text-danger">*</span></label>
                    <select class="custom-select" id="kategoriProduk" name="kategoriProduk" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        <option value="1">Stempel</option>
                        <option value="3">Advertising</option>
                        <option value="2">Non Stempel</option>
                        <option value="4">Digital Printing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jenisProduk">Jenis Produk<span class="text-danger">*</span></label>
                    <select class="custom-select" id="jenisProduk" name="jenisProduk" style="width: 100%" required>
                        
                    </select>
                </div>

                <div class="form-group">
                    <label for="fileProduk">Referensi Desain<span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input accept="image/*" type="file" class="custom-file-input" id="fileProduk" name="refdesain" required>
                        <label class="custom-file-label" for="fileProduk">Pilih file</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan<span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="4" id="keterangan" name="note" required></textarea>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="jumlah" name="qty" required>
                </div>
    
                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                </form>
                </div>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->