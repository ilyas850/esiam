<!-- Modal Add Master Angkatan -->
<div class="modal fade" id="addangkatan" tabindex="-1" role="dialog" aria-labelledby="addAngkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="{{ url('simpan_angkatan') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAngkatanModalLabel">Tambah Master Angkatan</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Angkatan</label>
                        <input type="text" class="form-control" name="idangkatan" placeholder="Masukan ID">
                    </div>
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" class="form-control" name="angkatan" placeholder="Masukan Tahun">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
