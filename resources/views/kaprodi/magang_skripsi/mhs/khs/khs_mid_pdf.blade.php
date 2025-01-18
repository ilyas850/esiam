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
                    <h4><b>KARTU HASIL STUDI MAHASISWA (MID-TERM)</b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><b><span style="font-size:85%">Nama </span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $mhs->nama }}</u></span></b></td>
            <td><b><span style="font-size:85%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u> {{ $mhs->kelas }} </span></u></b>
            </td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">NIM</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $mhs->nim }}</u></span></b></td>
            <td><b><span style="font-size:85%">Tahun Ajaran</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $periode_tahun->periode_tahun }}
                            {{ $periode_tipe->periode_tipe }}</u></span></b>
            </td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u> {{ $mhs->prodi }} </u></span></b></td>

        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>

            <tr>
                <th rowspan="2" align=center width="5%"><span style="font-size:85%">No</span></th>
                <th rowspan="2" width="8%"><span style="font-size:85%">Kode </span></th>
                <th rowspan="2"><span style="font-size:85%">Nama Matakuliah</span></th>

                <th rowspan="2" width="10%"><span style="font-size:85%">SKS Teori</span></th>
                <th rowspan="2" width="10%"><span style="font-size:85%">SKS Praktek</span></th>

                <th><span style="font-size:85%">Nilai</span></th>
            </tr>
            <tr>
                <th><span style="font-size:85%">Angka (0-100)</span></th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp

            @foreach ($krs as $item)
                <tr>
                    <td align=center><span style="font-size:80%"><b>{{ ++$i }}</b></span></td>
                    <td><span style="font-size:80%">
                            <center><b>{{ $item->kode }}</center></b>
                        </span>
                    </td>
                    <td><span style="font-size:80%"><b>{{ $item->makul }}</b></span></td>
                    <td align=center><span style="font-size:80%"><b>{{ $item->akt_sks_teori }}</b></span></td>
                    <td align=center><span style="font-size:80%"><b>{{ $item->akt_sks_praktek }}</b></span></td>
                    <td align=center><span style="font-size:80%"><b>{{ $item->nilai_UTS }}</b></span></td>
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" align=right><span style="font-size:85%">Jumlah SKS</span></th>
                <th colspan="2" align=center><span style="font-size:85%">{{ $sks }}</span></th>
                <th align=center><span style="font-size:85%"></span></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="34%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%">Cikarang, {{ $d }} {{ $m }}
                    {{ $y }}</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%">Kepala Program Studi</span></td>
            <td width="50%" align=center><span style="font-size:85%">Kepala BAAK</span></td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%">(..........................................)</span>
            </td>
            <td width="50%" align=center><span style="font-size:85%">(..........................................)</span>
            </td>
        </tr>
    </table>
    <br>
</body>
