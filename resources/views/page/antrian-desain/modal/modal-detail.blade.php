<div class="modal fade" id="detailWorking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Detail #{{ $desain->ticket_order }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="createdAtWorking{{ $desain->id }}">Waktu Dibuat</label>
                        <input type="text" class="form-control" id="createdAtWorking{{ $desain->id }}" name="createdAt" value="{{ $desain->created_at }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastUpdateWorking{{ $desain->id }}">Terakhir Diupdate</label>
                        <input type="text" class="form-control" id="lastUpdateWorking{{ $desain->id }}" name="lastUpdate" value="{{ $desain->updated_at }}" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="namaSalesWorking{{ $desain->id }}">Nama Sales</label>
                <input type="text" class="form-control" id="namaSalesWorking{{ $desain->id }}" name="namaSales" value="{{ $desain->sales->sales_name }}" readonly>
            </div>
            <div class="form-group">
                <label for="judulDesainWorking{{ $desain->id }}">Judul Desain</label>
                <input type="text" class="form-control" id="judulDesainWorking{{ $desain->id }}" name="judulDesain" value="{{ $desain->title }}" readonly>
            </div>
            <div class="form-group">
                <label for="jenisProdukWorking{{ $desain->id }}">Jenis Produk</label>
                <input type="text" class="form-control" id="jenisProdukWorking{{ $desain->id }}" name="jenisPekerjaan" value="{{ $desain->job->job_name }}" readonly>
            </div>
            <div class="form-group">
                <label for="keteranganWorking{{ $desain->id }}">Keterangan</label>
                <textarea class="form-control" id="keteranganWorking{{ $desain->id }}" name="keterangan" rows="3" readonly>{{ $desain->description }}</textarea>
            </div>
            <div class="form-group">
                <h6><strong>Referensi Desain</strong></h6><br>
                <img src="{{ asset('storage/ref-desain/'. $desain->desain) }}" class="img-fluid" alt="Preview Image">
            </div>
            <p class="text-muted font-italic">*Jika ada yang kurang jelas, bisa menghubungi sales yang bersangkutan</p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
    </div>
</div>