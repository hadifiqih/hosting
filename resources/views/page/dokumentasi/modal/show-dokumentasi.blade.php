<div class="modal fade" id="modal-tampilDokumentasi">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hasil Dokumentasi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- Tampilkan Gambar --}}
            <img id="gambarDokum" class="img-fluid" alt="Gambar Dokumentasi">
        </div>
        <div class="modal-footer justify-content-between">
          <button id="btnDownloadGambar" onclick="downloadGambar()" type="button" class="btn btn-primary">Download</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->