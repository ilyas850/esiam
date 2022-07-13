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
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td>
                <center>
                    <h4><b>REPORT REKAPITULASI EDOM PER MATAKULIAH </b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="15%"><span style="font-size:85%">Tahun Akademik / Semester</span></td>
            <td width="1%"><span style="font-size:85%">:</span></td>
            <td width="40%"><span style="font-size:85%">{{ $thn }} - {{ $tp }}</span></td>
        </tr>
        <tr>
            <td width="15%"><span style="font-size:85%">Prodi</span></td>
            <td><span style="font-size:85%">:</span></td>
            <td><span style="font-size:85%">{{ $nama_prd }}</span> </td>
        </tr>
        <tr>
            <td width="15%"><span style="font-size:85%">Kelas</span></td>
            <td><span style="font-size:85%">:</span></td>
            <td><span style="font-size:85%">{{ $nama_kls }}</span> </td>
        </tr>
        <tr>
            <td width="15%"><span style="font-size:85%">Matakuliah</span></td>
            <td><span style="font-size:85%">:</span></td>
            <td><span style="font-size:85%">{{ $nama_mk }}</span> </td>
        </tr>
        <tr>
            <td width="15%"><span style="font-size:85%">Dosen</span></td>
            <td><span style="font-size:85%">:</span></td>
            <td><span style="font-size:85%">{{ $nama_dsn }}</span> </td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th><span style="font-size:85%">No</span></th>
                <th><span style="font-size:85%">Deskripsi </span></th>
                <th><span style="font-size:85%">Nilai 1 </span></th>
                <th><span style="font-size:85%">Nilai 2 </span></th>
                <th><span style="font-size:85%">Nilai 3</span></th>
                <th><span style="font-size:85%">Nilai 4</span></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td>
                        <center>{{ $no++ }}</center>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td>
                        <center> {{ $item->nilai_1 }}</center>
                    </td>
                    <td>
                        <center>{{ $item->nilai_2 }} </center>
                    </td>
                    <td>
                        <center> {{ $item->nilai_3 }} </center>
                    </td>
                    <td>
                        <center> {{ $item->nilai_4 }}</center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
