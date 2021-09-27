<table>
    <thead>
        <tr>
            <th ><center>No</center></th>
            <th ><center>NIM </center></th>
            <th ><center>Nama</center></th>
            <th ><center>Program Studi</center></th>
            <th ><center>Kelas</center></th>
            <th ><center>Angkatan</center></th>
            <th><center>Nilai KAT</center></th>
            <th><center>Nilai UTS</center></th>
            <th><center>Nilai UAS</center></th>
            <th><center>Nilai AKHIR</center></th>
            <th><center>Nilai HURUF</center></th>
        </tr>
    </thead>
    <tbody>
        @php $i=1 @endphp
        @foreach ($ck as $item)
            <tr>
                <td><center>{{$i++}}</center></td>
                <td><center>{{$item->nim}}</center></td>
                <td>{{$item->nama}}</td>
                <td>
                    @foreach ($prd as $itemprd)
                        @if ($item->kodeprodi == $itemprd->kodeprodi)
                            {{$itemprd->prodi}}
                        @endif
                    @endforeach
                </td>
                <td><center>
                    @foreach ($kls as $itemkls)
                        @if ($item->idstatus == $itemkls->idkelas)
                            {{$itemkls->kelas}}
                        @endif
                    @endforeach
                </center></td>
                <td><center>
                    @foreach ($angk as $itemangk)
                        @if ($item->idangkatan == $itemangk->idangkatan)
                            {{$itemangk->angkatan}}
                        @endif
                    @endforeach
                </center></td>
                <td><center>{{$item->nilai_KAT}}</center></td>
                <td><center>{{$item->nilai_UTS}}</center></td>
                <td><center>{{$item->nilai_UAS}}</center></td>
                <td><center>{{$item->nilai_AKHIR_angka}}</center></td>
                <td><center>{{$item->nilai_AKHIR}}</center></td>
            </tr>
        @endforeach
    </tbody>
</table>
