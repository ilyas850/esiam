<table>
    <thead>
        <tr>
            <th>
                <center>No</center>
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
                <center>Angkatan</center>
            </th>
            <th>
                <center>Intake</center>
            </th>
        </tr>
    </thead>
    <tbody>
        @php $i=1 @endphp
        @foreach ($val as $key)
            <tr>
                <td>
                    <center>{{ $i++ }}</center>
                </td>
                <td>
                    <center>{{ $key->nim }}</center>
                </td>
                <td>{{ $key->nama }}</td>
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
                    <center>
                        @if ($key->intake == 1)
                            Ganjil
                        @elseif($key->intake == 2)
                            Genap
                        @endif
                    </center>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
