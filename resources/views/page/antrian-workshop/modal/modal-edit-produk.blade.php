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
                <input type="hidden" id="idBarang" name="idBarang" value="">
                <div class="form-group">
                    <h6 class="font-weight-bold">Kategori Produk</h6>
                    <select name="kategoriProdukEdit" id="kategoriProdukEdit" class="form-control" data-allow-search="true" style="width: 100%" required>
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
                    <select name="namaProdukEdit" id="namaProdukEdit" class="form-control select2" data-allow-search="true" style="width: 100%" required>
                        <option value="" selected disabled>Pilih Produk</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="qty">Qty</label>
                    <input type="number" class="form-control" id="qtyEdit" placeholder="Qty" name="qtyEdit" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="text" class="form-control maskRupiah" id="hargaEdit" placeholder="Harga" name="hargaEdit" required>
                </div>

                <div class="form-group">
                    <label for="keterangan">Note</label>
                    <textarea name="keteranganEdit" id="keteranganEdit" cols="30" rows="5" class="form-control" placeholder="Ukuran : 6x6cm" required></textarea>
                </div>

                <style>
                    .select2-container .select2-selection--single {
                        height: 38px;
                    }
                </style>
                <div class="form-group">
                    <label for="infoPelanggan">Periode Iklan</label>
                    <select class="form-control select2 periode_iklan" id="tahunIklanEdit" name="tahunIklanEdit" style="width: 100%">
                        {{-- Tampilkan tahun dalam 5 tahun terakhir --}}
                        <option value="" selected disabled>Pilih Tahun</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                    <div class="form-group">
                        <select style="display: none" class="form-control select2 periode_iklan mt-2 mb-2" id="bulanIklanEdit" name="bulanIklanEdit" style="width: 100%">
                            <option value="" selected disabled>Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div class="form-group divNamaProdukEdit" style="display: none">
                        <select style="display: none; width: 100%" class="form-control select2 divNamaProdukEdit" id="namaProdukIklanEdit" name="namaProdukIklanEdit">
                            {{-- Nama Produk --}}
                        </select>
                    </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="not_iklanEdit" name="not_iklanEdit">
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

            </div>
            <div class="modal-footer">
                <input id="submitEditProduk" type="submit" class="btn btn-primary btnTambah" value="Edit">
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->