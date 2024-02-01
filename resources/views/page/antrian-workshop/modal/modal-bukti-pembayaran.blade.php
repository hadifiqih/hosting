<div class="modal fade" id="modalBuktiPembayaran">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Bukti Pembayaran</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                @if($antrian->buktiBayar->gambar)
                    <img src="{{ asset('storage/bukti-pembayaran/'.$antrian->buktiBayar->gambar) }}" alt="Bukti Pembayaran" class="img-fluid">
                @else
                    <p class="text-center">Belum ada bukti pembayaran</p>
                @endif
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->