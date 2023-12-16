{{-- Modal Reupload File Desain --}}
<div class="modal fade" id="modalReuploadFile{{ $desain->id }}" aria-labelledby="modalReuploadFile{{ $desain->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reupload Desain#{{ $desain->id }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Dropzone JS --}}
                <form action="{{ route('design.reuploadFile') }}" class="dropzone" id="my-dropzone-reupload{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $desain->id }}">
                    <input type="hidden" name="linkReupload" value="">
                    <a href="{{ route('submit.reupload', $desain->id) }}" class="btn btn-primary btn-sm submitButtonReupload">Unggah</a>
                </form>
                <div class="form-group mt-2">
                    <form action="{{ route('submitLinkReupload') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <label for="linkReupload{{ $desain->id }}">Link File</label>
                        <input type="text" class="form-control" id="linkReupload{{ $desain->id }}" name="linkReupload" placeholder="https://drive.google.com/xxxxx">
                        <input type="hidden" name="id" value="{{ $desain->id }}">
                        <input type="submit" class="btn btn-primary btn-sm mt-2 submitLinkReupload" value="Simpan">
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
{{-- End Modal Reupload File Desain --}}