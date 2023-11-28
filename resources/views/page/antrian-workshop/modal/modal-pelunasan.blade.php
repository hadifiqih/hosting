{{-- Modal Pelunasan  --}}
<div class="modal fade" id="modalPelunasan{{ $antrian->id }}" tabindex="-1" aria-labelledby="modalPelunasanLabel{{ $antrian->id }}" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Update Pelunasan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <form id="formPelunasan{{ $antrian->id }}" action="{{ route('payment.pelunasan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Jumlah Pembayaran --}}
                <div class="form-group mb-1">
                    <label class="form-label" for="metodePembayaran{{ $antrian->id }}">Metode Pembayaran <span class="text-danger font-weight-bold">*</span></label>
                        <select class="custom-select rounded-0 metodePembayaran" id="metodePembayaran{{ $antrian->id }}" name="metodePembayaran">
                            <option value="" disabled selected>-- Pilih Jenis Pembayaran --</option>
                            <option value="Cash">Tunai</option>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Transfer BRI">Transfer BRI</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                        </select>
                </div>
                <div class="form-group mb-1">
                    <label class="form-label" for="jumlahPembayaran{{ $antrian->id }}">Jumlah Pembayaran <span class="text-danger font-weight-bold">*</span></label>
                    <input id="jumlahPembayaran{{ $antrian->id }}" type="text" class="form-control maskRupiah jumlahPembayaran" name="jumlahPembayaran" placeholder="Contoh : Rp 10.000" required>
                </div>
                    <p id="keterangan{{ $antrian->id }}" class="my-1 keterangan"></p>
                {{-- File Bukti Pembayaran --}}
                <div class="filePelunasan">
                <p class="mb-2"><strong>Bukti Pembayaran <span class="text-danger font-weight-bold">*</span></strong></p>
                <div class="input-group">
                    <div class="custom-file">
                    <input type="file" class="custom-file-input" id="filePelunasan{{ $antrian->id }}" name="filePelunasan" />
                    <label class="custom-file-label" for="filePelunasan{{ $antrian->id }}">Pilih File</label>
                    </div>
                </div>
                </div>
                <div class="form-group mt-1">
                    <label class="form-label" for="sisaPembayaran{{ $antrian->id }}">Sisa Pembayaran</label>
                    <input class="form-control sisaPembayaran" type="text" id="sisaPembayaran{{ $antrian->id }}" name="sisaPembayaran" value="Rp {{ number_format($antrian->payment->remaining_payment, 0, ',', '.') }}" disabled>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <input type="hidden" name="ticketAntrian" value="{{ $antrian->ticket_order }}">
            <input id="submitUnggahBayar{{ $antrian->id }}" type="submit" class="btn btn-primary submitUnggahBayar" value="Unggah" disabled>
        </form>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
