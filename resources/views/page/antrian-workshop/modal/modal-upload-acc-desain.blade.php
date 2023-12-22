<div class="modal fade" id="modalUploadAcc" tabindex="-1" aria-labelledby="modalUploadAccLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalUploadAccLabel">Upload Gambar ACC Desain</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                @csrf
                <input type="hidden" name="ticket_order" id="ticket_order" value="{{ $order->ticket_order }}">
            </form>
        </div>
        <div class="modal-footer">
            <button id="btnBatal" type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button id="btnTambah" type="button" onclick="simpanAcc()" class="btn btn-primary">Simpan</button>
        </div>
    </form>
    </div>
    </div>
</div>