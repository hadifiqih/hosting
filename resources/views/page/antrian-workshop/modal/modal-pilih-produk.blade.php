<div class="modal fade" id="modalPilihProduk">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <form id="formTambahProduk" action="" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" id="harga" placeholder="Harga" name="harga" required>
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
                <div class="form-group">
                    <label for="infoPelanggan">Periode Iklan</label>
                    <select class="form-control select2" id="periode_iklan" name="iklan" style="width: 100%">
                    </select>
                </div>

                <div class="form-group">
                    <label for="fileAccDesain">File ACC Desain</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fileAccDesain" name="fileAccDesain" required>
                            <label class="custom-file-label" for="fileAccDesain">Pilih File</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="kosongAcc" name="kosongAcc">
                        <label for="kosongAcc" class="custom-control-label">Tidak Ada Gambar ACC</label>
                    </div>
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