<!-- Modal Edit Master Angkatan -->
<div class="modal fade" id="modalUpdateAngkatan{{ $item->idangkatan }}" tabindex="-1" aria-labelledby="updateAngkatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('update_angkatan/' . $item->idangkatan) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateAngkatanModalLabel">Update Master Angkatan</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Angkatan</label>
                        <input type="text" class="form-control" name="idangkatan" value="{{ $item->idangkatan }}">
                    </div>
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" class="form-control" name="angkatan" value="{{ $item->angkatan }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
