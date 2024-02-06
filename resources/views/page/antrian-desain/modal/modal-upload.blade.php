<div class="modal fade" id="modalUpload" aria-labelledby="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">File Upload #</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Dropzone JS --}}
                <form action="{{ route('design.upload') }}" class="dropzone" id="my-dropzone" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="idOrder" name="idOrder" class="idOrder" value="">
                </form>
                
                <div class="row mt-2 mr-2 float-right">
                    <button id="submitCetak" href="javascript:void(0)" class="btn btn-primary btn-sm">Unggah</button>
                </div>

                <div class="form-group mt-4">
                    <form action="{{ route('submitLinkUpload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="linkFileUpload">Link File <span class="text-muted font-italic text-sm">*Opsional</span></label>
                        <input type="text" class="form-control" id="linkFileUpload" name="linkFileUpload" placeholder="https://drive.google.com/xxxxx">
                        <input type="hidden" name="idOrder" class="" value="">
                        <input type="submit" class="btn btn-primary btn-sm mt-2 submitLink disabled" value="Simpan">
                    </form>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{{-- End Modal Upload --}}