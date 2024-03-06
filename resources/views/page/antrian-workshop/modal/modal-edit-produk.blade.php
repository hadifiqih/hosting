<div class="modal fade" id="modalEditProduk">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <form id="formEditProduk" action="" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <h6 class="font-weight-bold">Kategori Produk</h6>
                    <select name="kategoriProduk" id="kategoriProduk" class="form-control" data-allow-search="true" style="width: 100%" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        <option value="1">Stempel</option>
                        <option value="2">Non Stempel</option>
                        <option value="3">Advertising</option>
                        <option value="4">Digital Printing</option>
                        <option value="5">Jasa Servis</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label class="font-weight-bold">Nama Produk</label>
                    <select name="namaProduk" id="namaProduk" class="form-control select2" data-allow-search="true" style="width: 100%" required>
                        <option value="" selected disabled>Pilih Produk</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="qty">Qty</label>
                    <input type="number" class="form-control" id="qty" placeholder="Qty" name="qty" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="text" class="form-control maskRupiah" id="harga" placeholder="Harga" name="harga" required>
                </div>

                <div class="form-group">
                    <label for="keterangan">Note</label>
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Ukuran : 6x6cm" required></textarea>
                </div>

                <style>
                    .select2-container .select2-selection--single {
                        height: 38px;
                    }
                </style>
                <div class="form-group mb-1">
                    <label for="infoPelanggan">Periode Iklan</label>
                    <select class="form-control select2 periode_iklan" id="periode_iklanEdit" name="periode_iklan" style="width: 100%">
                    </select>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="not_iklanEdit" name="not_iklan">
                    <label class="form-check-label">Tidak dari Iklan</label>
                </div>

                <div class="form-group mt-2 mb-1">
                    <label for="fileAccDesain">File ACC Desain</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fileAccDesainEdit" name="fileAccDesainEdit" required>
                            <label class="custom-file-label" for="fileAccDesainEdit">Pilih File</label>
                        </div>
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="kosongAccEdit" name="kosongAccEdit">
                    <label class="form-check-label">Tidak ada ACC Desain</label>
                </div>

                <input type="hidden" name="ticket_order" id="ticket_order" value="{{ $order->ticket_order }}">
            </div>
            <div class="modal-footer">
                <input id="submitProduk" type="submit" class="btn btn-primary btnTambah" value="Tambah">
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->