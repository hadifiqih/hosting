<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form id="pelanggan-form" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Pelanggan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalNama" placeholder="Nama Pelanggan" name="modalNama" required>
            </div>

            <div class="form-group">
                <label for="noHp">No. HP / WA <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="modalTelepon" placeholder="Nomor Telepon" name="modalTelepon" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalAlamat" placeholder="Alamat Pelanggan" name="modalAlamat" required>
            </div>
            <div class="form-group">
                <label for="instansi">Instansi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalInstansi" placeholder="Instansi Pelanggan" name="modalInstansi" required>
                <p class="text-muted mt-2">*Jika tidak tau, beri tanda "-"</p>
            </div>
            <div class="form-group">
                <label for="infoPelanggan">Sumber Pelanggan <span class="text-danger">*</span></label>
                <select class="custom-select rounded-0" id="infoPelanggan" name="modalInfoPelanggan" required>
                    <option value="default" selected>Pilih Sumber Pelanggan</option>
                    <option value="Google">Google</option>
                    <option value="G-Maps">G-Maps</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Tokopedia">Tokopedia</option>
                    <option value="Shopee">Shopee</option>
                    <option value="Bukalapak">Bukalapak</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Youtube">Youtube</option>
                    <option value="Snackvideo">Snackvideo</option>
                    <option value="OLX">OLX</option>
                    <option value="Teman/Keluarga/Kerabat">Teman/Keluarga/Kerabat</option>
                    <option value="Iklan Facebook">Iklan Facebook</option>
                    <option value="Iklan Instagram">Iklan Instagram</option>
                    <option value="Iklan Google">Iklan Google</option>
                    <option value="Iklan Tiktok">Iklan Tiktok</option>
                    <option value="Salescall">Salescall</option>
                    <option value="Visit">Visit / Kunjungan</option>
                    <option value="Follow Up">Follow Up</option>
                    <option value="RO WA">RO WhatsApp</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <input type="submit" class="btn btn-primary" id="submitPelanggan" value="Tambah"><span id="loader" class="loader" style="display: none;"></span>
        </div>
    </form>
    </div>
    </div>
</div>