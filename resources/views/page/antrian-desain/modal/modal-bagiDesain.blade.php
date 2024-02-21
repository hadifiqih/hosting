{{-- Modal Bagi Desain --}}
<div class="modal fade" id="modalBagiDesain">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pilih Desainer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="ticket_order" name="ticket_order" value="{{ $order->ticket_order }}">
          <input type="hidden" id="idBarang" name="barang_id" value="">
            <table id="tableDesainer" class="table table-bordered table-responsive" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Desainer</th>
                        <th>Jumlah Antrian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->