{{-- Modal Pelunasan  --}}
<div class="modal fade" id="modalPelunasan" tabindex="-1" aria-labelledby="modalPelunasanLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Update Pelunasan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('unggahPelunasan') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="ticketPelunasan" id="ticketPelunasan">

                {{-- Jenis Pelunasan --}}
                <div class="form-group">
                    <label for="jenisPelunasan">Jenis Pelunasan</label>
                    <select class="form-control" id="jenisPelunasan" name="jenisPelunasan">
                        <option value="TF" selected>Transfer</option>
                        <option value="TU">Tunai</option>
                    </select>
                </div>

                <div id="formFilePelunasan" class="form-group">
                    <label for="filePelunasan">Bukti Pembayaran</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="buktiPelunasan" class="custom-file-input" id="filePelunasan" accept="image/jpg, image/jpeg, image/png, application/pdf">
                            <label class="custom-file-label" for="filePelunasan">Pilih file</label>
                        </div>
                    </div>
                </div>

                {{-- Sisa Pembayaran --}}
                <div class="form-group">
                    <label for="sisaPembayaran">Sisa Pembayaran : </label> <span id="sisaPembayaran" class="text-danger"> Rp{{ number_format($sisaBayar,0,',','.') }}</span>
                </div>
                {{-- Nominal --}}
                <div class="form-group">
                    <label for="nominal">Nominal</label>
                    <input type="text" class="form-control" id="nominal" name="nominal" placeholder="Nominal" required>
                    <h6 id="errorNominal" class="text-danger mt-1"></h6>
                </div>
                
                <div class="form-group">
                    <label id="judulTampilan" for="preview-image" style="display: none">Tampilan Bukti Pembayaran</label>
                    <img id="preview-image" class="img-preview" width="100%"></img>
                </div>

                <button id="btnPelunasan" type="submit" class="btn btn-sm btn-primary">Unggah</button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
