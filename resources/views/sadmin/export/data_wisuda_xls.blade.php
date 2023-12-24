<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tahun Lulus</th>
            <th>Ukuran Toga</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Program Studi</th>
            <th>No. HP</th>
            <th>E-mail</th>
            <th>NPWP</th>
            <th>Tempat Kerja</th>
            <th>Alamat KTP</th>
            <th>Alamat Domisili</th>
            <th>Nama Ayah</th>
            <th>Nama Ibu</th>
            <th>No. HP Ayah</th>
            <th>No. HP Ibu</th>
            <th>Alamat Ortu</th>
            <th>Status Vaksin</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        @foreach ($data as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->tahun_lulus }}</td>
                <td>{{ $item->ukuran_toga }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->prodi }}</td>
                <td>{{ $item->no_hp }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->npwp }}</td>
                <td>{{ $item->tempat_kerja }}</td>
                <td>{{ $item->alamat_ktp }}</td>
                <td>{{ $item->alamat_domisili }}</td>
                <td>{{ $item->nama_ayah }}</td>
                <td>{{ $item->nama_ibu }}</td>
                <td>{{ $item->no_hp_ayah }}</td>
                <td>{{ $item->no_hp_ibu }}</td>
                <td>{{ $item->alamat_ortu }}</td>
                <td>{{ $item->status_vaksin }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
