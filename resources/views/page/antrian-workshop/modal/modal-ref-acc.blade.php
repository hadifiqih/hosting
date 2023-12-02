<div class="modal fade" id="modalRefACC" tabindex="-1" aria-labelledby="modalRefACC" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Gambar Referensi / ACC Desain</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <h5>ACC Desain</h5>
            @foreach($items as $item)
                <img src="{{ asset('storage/acc-desain/'. $item->acc_desain) }}" id="acc{{ $item->id }}" class="img-fluid m-2" alt="Gambar Referensi / ACC Desain">
            @endforeach
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->