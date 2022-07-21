<table>
    <thead>
        <tr>
            <td>NIM</td>
            <td>NM PD</td>
            <td>STAT</td>
            <td>SKS SEMESTER</td>
            <td>IPS</td>
            <td>TOTAL SKS</td>
            <td>IPK</td>
            <td>BIAYA</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key)
            <tr>
                <td>
                    <center>{{ $key->nim }}</center>
                </td>
                <td>{{ $key->nama }}</td>
                <td>
                    <center>
                        @if ($key->status_mahasiswa == 'Aktif')
                            @if ($key->sks_semester > 0)
                                Aktif
                            @elseif($key->sks_semester == 0)
                                Non-Aktif
                            @endif
                        @elseif ($key->status_mahasiswa == 'Cuti')
                            Cuti
                        @endif
                    </center>
                </td>
                <td>
                    <center>
                        {{ $key->sks_semester }} SKS
                    </center>
                </td>
                <td>
                    <center>
                        {{ $key->ips }}
                    </center>
                </td>
                <td>
                    <center>
                        {{ $key->total_sks }} SKS
                    </center>
                </td>
                <td>
                    <center>{{ $key->ipk }}</center>
                </td>
                <td>
                    <center>{{ $key->biaya }}</center>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
