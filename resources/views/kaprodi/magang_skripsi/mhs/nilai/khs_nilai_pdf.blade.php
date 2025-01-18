<style media="screen">
    table {
        border-collapse: collapse;
    }

    tr.b {
        line-height: 80px;
    }

</style>

<body>
    <table width="100%">
        <tr>
            <td>
                <img src="images/logo meta png.png" width="200" height="75" alt="" align="left" />
            </td>
            <td>
                <center>
                    <img src="images/kop.png" width="200" height="70" alt="" align="right" />
                </center>
            </td>
        </tr>
    </table><br><br><br>
    <table width="100%">
        <tr>
            <td>
                <center>
                    <h4><b>KARTU HASIL STUDI MAHASISWA </b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><b><span style="font-size:85%">TA Semester </span></b></td>
            <td>:</td>
            <td><b><u><span style="font-size:85%">
                            {{ $periodetahun }}
                            {{ $periodetipe }}</span>
                </b></u>
            </td>
            <td align=right><span style="font-size:85%">Jumlah SKS Maksimal </span> </td>
            <td> : </td>
            <td><span style="font-size:85%"> 24</span> </td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Nama </span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $mhs->nama }}</u></span></b></td>
            <td align=right><span style="font-size:85%">SKS Tempuh </span> </td>
            <td> : </td>
            <td> <span style="font-size:85%">{{ $sks }} </span></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">NIM</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $mhs->nim }}</u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u> {{ $mhs->prodi }} </u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u> {{ $mhs->kelas }} </span></u></b>
            </td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th style="font-size:85%" rowspan="2" style="width: 10px" align=center>
                    <center>No</center>
                </th>
                <th rowspan="2" style="font-size:85%">
                    <center>Kode</center>
                </th>
                <th rowspan="2" style="font-size:85%">
                    <center>Matakuliah</center>
                </th>
                <th colspan="2" style="font-size:85%">
                    <center>SKS</center>
                </th>
                <th colspan="2" style="font-size:85%">
                    <center>Nilai</center>
                </th>
                <th rowspan="2" style="font-size:85%">
                    <center>Nilai x SKS</center>
                </th>
            </tr>
            <tr>
                <th style="font-size:85%">
                    <center>Teori</center>
                </th>
                <th style="font-size:85%">
                    <center>Praktek</center>
                </th>
                <th style="font-size:85%">
                    <center>Huruf</center>
                </th>
                <th style="font-size:85%">
                    <center>Angka</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td style="font-size:85%">
                        <center>{{ $no++ }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center> {{ $item->kode }}</center>
                    </td>
                    <td style="font-size:85%"> {{ $item->makul }}</td>
                    <td style="font-size:85%">
                        <center>{{ $item->akt_sks_teori }}
                        </center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->akt_sks_praktek }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_AKHIR }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_ANGKA }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>
                            @if ($item->nilai_AKHIR == 'A')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 4 }}
                            @elseif ($item->nilai_AKHIR == 'B+')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 3.5 }}
                            @elseif ($item->nilai_AKHIR == 'B')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 3.0 }}
                            @elseif ($item->nilai_AKHIR == 'C+')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 2.5 }}
                            @elseif ($item->nilai_AKHIR == 'C')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 2.0 }}
                            @elseif ($item->nilai_AKHIR == 'D')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 1.0 }}
                            @elseif ($item->nilai_AKHIR == 'E')
                                {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 0.0 }}
                            @elseif ($item->nilai_AKHIR == null)
                                0
                            @endif
                        </center>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="font-size:85%">
                    <center>Jumlah</center>
                </td>
                <td colspan="2" style="font-size:85%">
                    <center><b>{{ $sks }}</b></center>
                </td>
                <td colspan="2"></td>
                <td style="font-size:85%">
                    <center><b>{{ $nxsks }}</b></center>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="font-size:85%">
                    <center>Indeks Prestasi Semester (IPS)</center>
                </td>
                <td colspan="2" style="font-size:85%">
                    <center><b>{{ round($nxsks / $sks, 2) }}</b></center>
                </td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
</body>
