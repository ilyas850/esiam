<!-- Modal Delete Master Angkatan -->
<div class="modal fade" id="modalHapusAngkatan{{ $item->idangkatan }}" tabindex="-1" aria-labelledby="deleteAngkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-center">Apakah anda yakin menghapus data master angkatan ini?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ url('delete_angkatan') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="idangkatan" value="{{ $item->idangkatan }}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Data!</button>
                </form>
            </div>
        </div>
    </div>
</div>
