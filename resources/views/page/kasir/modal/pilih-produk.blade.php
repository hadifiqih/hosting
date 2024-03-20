{{-- Modal Bootstrap --}}
<div class="modal fade" id="pilihProduk" tabindex="-1" role="dialog" aria-labelledby="pilihProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="pilihProdukLabel">Pilih Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Modal Body --}}
            <div class="modal-body">
                {{-- Table Produk --}}
                <div class="table-responsive">
                    <table id="tableProduct" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Kode Produk</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga Jual</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
