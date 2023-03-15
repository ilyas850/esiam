<table>
    <thead>
        <tr>
            <th>
                <center>No</center>
            </th>
            <th>
                <center>Tahun Akademik</center>
            </th>
            <th>
                <center>NIM</center>
            </th>
            <th>
                <center>Nama Mahasiswa</center>
            </th>
            <th>
                <center>Program Studi</center>
            </th>
            <th>
                <center>Kelas</center>
            </th>
            <th>
                <center>Semester</center>
            </th>
            <th>
                <center>IPK</center>
            </th>
            <th>
                <center>Beasiswa</center>
            </th>
        </tr>
    </thead>
    <tbody>
        @php $i=1 @endphp
        @foreach ($data as $item)
            <tr>
                <td>
                    <center>{{ $i++ }}</center>
                </td>
                <td>
                    <center>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</center>
                </td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nama }}</td>
                <td>
                    <center>{{ $item->prodi }}</center>
                </td>
                <td>
                    <center>{{ $item->kelas }}</center>
                </td>
                <td>
                    <center>{{ $item->semester }}</center>
                </td>
                <td align="center">{{ $item->ipk }}</td>
                <td align="center">{{ $item->beasiswa }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
