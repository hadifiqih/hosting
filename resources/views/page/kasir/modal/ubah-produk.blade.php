{{-- modal bootstrap --}}
<div class="modal fade" id="ubahProduk" tabindex="-1" role="dialog" aria-labelledby="ubahProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{-- modal header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="ubahProdukLabel">Ubah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- modal body --}}
            <div class="modal-body">
                {{-- Form Ubah produk --}}
                <form id="formUbahProduk" action="" enctype="text/plain">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="kode_produk">Kode Produk</label>
                        <input type="text" class="form-control" id="kode_produk" name="kode_produk" placeholder="Contoh : GF2243T">
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
                    <button id="btnTambahProduk" type="submit" class="btn btn-sm btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>