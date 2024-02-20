<div class="modal fade" id="modalSpesifikasi" tabindex="-1" aria-labelledby="modalSpesifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal1Label">Detail Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <style>
                #gambarDok {
                    height: 50%;
                    object-fit: cover;
                }

                #spesifikasi {
                    white-space: pre-line;
                }
            </style>
            <!-- Image here -->
          <img id="gambarDokum" src="" class="img-fluid" height="300">
            <div class="row mt-3">
                <div class="col-md-6">
                <h5 class="text-secondary">Spesifikasi</h5>
                <p id="spesifikasi"></p>
                </div>

                <div class="col-md-6">
                    <h5 class="text-secondary">Harga</h5>
                    <strong class="text-danger"><p id="harga"></p></strong>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button id="btnUnduhGambar" type="button" class="btn btn-sm btn-primary">Unduh Gambar</button>
        </div>
      </div>
    </div>
  </div>