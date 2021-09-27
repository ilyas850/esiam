<table>
    <thead>
        <tr>
            <th ><center>Prodi</center></th>
            <th ><center>Reguler A/B/C</center></th>
            <th ><center>NIM </center></th>
            <th ><center>Nama</center></th>
            <th ><center>Kode Matkul</center></th>
            <th ><center>Nama Matkul</center></th>
            <th ><center>SKS</center></th>
            <th><center>Nilai Huruf</center></th>
            <th><center>Nilai Angka</center></th>
            <th><center>Nilai x SKS</center></th>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($nilai as $item)
            <tr>
                <td>{{$item->prodi}}</td>
                <td><center>{{$item->kelas}}</center></td>
                <td><center>{{$item->nim}}</center></td>
                <td>{{$item->nama}}</td>
                <td><center>{{$item->kode}}</center></td>
                <td><center>{{$item->makul}}</center></td>
                <td><center>{{$item->akt_sks}}</center></td>
                <td><center>{{$item->nilai_AKHIR}}</center></td>
                <td><center>{{$item->nilai_ANGKA}}</center></td>
                <td><center>{{$item->akt_sks_hasil}}</center></td>
            </tr>
        @endforeach
    </tbody>
</table>