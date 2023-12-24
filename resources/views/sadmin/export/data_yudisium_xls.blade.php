<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>NIK</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        @foreach ($data as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->prodi }}</td>
                <td>{{ $item->tmpt_lahir }}</td>
                <td>{{ $item->tgl_lahir->isoFormat('D MMMM Y') }}
                </td>
                <td>{{ $item->nik }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
