<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="4px">
                    <center>No</center>
                </th>
                <th>
                    <center>Nama Mahasiswa</center>
                </th>
                <th width="10%">
                    <center>NIM</center>
                </th>
                <th width="15%">
                    <center>Program Studi</center>
                </th>
                <th width="10%">
                    <center>Kelas</center>
                </th>
                <th width="10%">
                    <center>Angkatan</center>
                </th>
                <th>
                    <center>Total KRS</center>
                </th>
                <th>
                    <center>Aksi</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $data->firstItem(); ?>
            @forelse ($data as $key)
                <tr>
                    <td>
                        <center>{{ $no++ }}</center>
                    </td>
                    <td>{{ $key->nama }}</td>
                    <td>
                        <center>{{ $key->nim }}</center>
                    </td>
                    <td>
                        <center>{{ $key->prodi }}</center>
                    </td>
                    <td>
                        <center>{{ $key->kelas }}</center>
                    </td>
                    <td>
                        <center>{{ $key->angkatan }}</center>
                    </td>
                    <td>
                        <center>{{ $key->jml_krs }}</center>
                    </td>
                    <td>
                        <center>
                            <a class="btn btn-info btn-xs" href="/cek_krs_all_kprd/{{ $key->idstudent }}">Cek KRS</a>
                        </center>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-sm-6">
        <div style="padding-top: 8px;">
            Menampilkan {{ $data->firstItem() ?: 0 }} sampai {{ $data->lastItem() ?: 0 }} dari {{ $data->total() }} data
        </div>
    </div>
    <div class="col-sm-6 text-right">
        {{ $data->appends(request()->only('q', 'per_page'))->links() }}
    </div>
</div>
