{{-- modal bootstrap --}}
<div class="modal fade" id="tambahProduk" tabindex="-1" role="dialog" aria-labelledby="tambahProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{-- modal header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="tambahProdukLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- modal body --}}
            <div class="modal-body">
                {{-- Form tambah produk --}}
                <form id="formTambahProduk" action="" enctype="text/plain" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kode_produk">Kode Produk</label>
                        <input type="text" class="form-control" id="kode_produk" name="kode_produk" placeholder="Contoh : P01214140023">
                    </div>
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Contoh : Gagang Flash 4040">
                    </div>
                    <div class="form-group">
                        <label for="harga_kulak">Harga Kulak</label>
                        <input type="text" class="form-control maskMoney" id="harga_kulak" name="harga_kulak" placeholder="Contoh : Rp 8.000">
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="text" class="form-control maskMoney" id="harga_jual" name="harga_jual" placeholder="Contoh : Rp 12.000">
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" placeholder="Contoh : 8">
                    </div>
                    <button id="btnTambahProduk" type="submit" class="btn btn-sm btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>