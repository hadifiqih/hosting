<div class="modal fade" id="modalRevisi{{ $desain->id }}" aria-labelledby="modalRevisi{{ $desain->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Revisi Desain#{{ $desain->id }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Dropzone JS --}}
                <form action="{{ route('uploadRevisi') }}" class="dropzone" id="my-dropzone-revisi{{ $desain->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $desain->id }}">
                    <input type="hidden" name="linkReupload" value="">
                    <a href="{{ route('submitRevisi', $desain->id) }}" class="btn btn-primary btn-sm submitButtonRevisi">Unggah</a>
                </form>
                <div class="form-group mt-2">
                    <form action="{{ route('submitLinkRevisi') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <label for="linkRevisi{{ $desain->id }}">Link File</label>
                        <input type="text" class="form-control" id="linkRevisi{{ $desain->id }}" name="linkRevisi" placeholder="https://drive.google.com/xxxxx">
                        <input type="hidden" name="id" value="{{ $desain->id }}">
                        <input type="submit" class="btn btn-primary btn-sm mt-2 submitLinkRevisi" value="Simpan">
                    </form>
                </div>
                <p class="text-sm text-danger mt-1 mb-0"><strong>Perhatian!</strong></p>
                <p class="text-sm text-secondary font-italic">*Pastikan file yang diunggah sudah benar, jika ada kesalahan cetak dari file yang diupload, maka biaya kerugian cetak ditanggung pribadi.</p>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>