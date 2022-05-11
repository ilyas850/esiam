<table>
    <thead>
        <tr>
            <th>
                <center>No</center>
            </th>
            <th>
                <center>Mahasiswa/NIM</center>
            </th>
            <th>
                <center>Prodi</center>
            </th>
            <th>
                <center>Kelas</center>
            </th>
            <th>
                <center>Angkatan</center>
            </th>
            <th>
                <center>Pembimbing</center>
            </th>
            <th>
                <center>Jumlah Bimbingan</center>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        @foreach ($data as $key)
            <tr>
                <td>
                    <center>{{ $no++ }}</center>
                </td>
                <td>{{ $key->nama }}/{{ $key->nim }}</td>
                <td>{{ $key->prodi }}</td>
                <td>
                    <center>{{ $key->kelas }}</center>
                </td>
                <td>
                    <center>{{ $key->angkatan }}</center>
                </td>
                <td>
                    {{ $key->dosen_pembimbing }}
                </td>
                <td>
                    <center>{{ $key->jml_bim }}</center>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
