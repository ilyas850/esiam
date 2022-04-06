<table>
    <thead>
        <tr>
            <th>
                <center>No. </center>
            </th>
            <th>
                <center>Nama</center>
            </th>
            <th>
                <center>NIM </center>
            </th>
            <th>
                <center>Program Studi </center>
            </th>
            <th>
                <center>Kelas </center>
            </th>
            <th>
                <center>Tempat Prakerin</center>
            </th>
            <th>
                <center>Judul Prakerin</center>
            </th>
            <th>
                <center>Dosen Pembimbing</center>
            </th>
            <th>
                <center>Dosen Penguji I</center>
            </th>
            <th>
                <center>Tanggal Seminar</center>
            </th>
            <th>
                <center>Jam Mulai Seminar</center>
            </th>
            <th>
                <center>Jam Selsai Seminar</center>
            </th>
            <th>
                <center>Ruangan Seminar</center>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        @foreach ($cek as $item)
            <tr>
                <td>
                    <center>{{ $no++ }}</center>
                </td>
                <td>{{ $item->nama }}</td>
                <td>
                    <center>{{ $item->nim }}</center>
                </td>
                <td>{{ $item->prodi }}</td>
                <td>
                    <center>{{ $item->kelas }}</center>
                </td>
                <td>
                    <center>{{ $item->tempat_prausta }}</center>
                </td>
                <td>
                    <center>{{ $item->judul_prausta }}</center>
                </td>
                <td>
                    <center>{{ $item->dosen_pembimbing }}</center>
                </td>
                <td>
                    <center>{{ $item->dosen_penguji_1 }}</center>
                </td>
                <td>
                    <center>{{ $item->tanggal_selesai }}</center>
                </td>
                <td>
                    <center>{{ $item->jam_mulai_sidang }}</center>
                </td>
                <td>
                    <center>{{ $item->jam_selesai_sidang }}</center>
                </td>
                <td>
                    <center>{{ $item->ruangan }}</center>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
