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
            <td><span style="font-size:85%">{{ $prd }}</span> </td>
        </tr>
    </table>
    <br>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th><span style="font-size:85%">No</span></th>
                <th><span style="font-size:85%">Dosen </span></th>
                <th><span style="font-size:85%">Matakuliah </span></th>
                <th><span style="font-size:85%">Kelas </span></th>
                <th><span style="font-size:85%">Mhs Qty</span></th>
                <th><span style="font-size:85%">Kuisioner Qty</span></th>
                <th><span style="font-size:85%">Nilai Angka</span></th>
                <th><span style="font-size:85%">Nilai Huruf</span></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td>
                        <center><span style="font-size:85%">{{ $no++ }}</span></center>
                    </td>
                    <td>
                        <span style="font-size:85%">{{ $item->nama }}</span>
                    </td>
                    <td>
                        <span style="font-size:85%">{{ $item->makul }}</span>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">{{ $item->kelas }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%"> {{ $item->mhs_qty }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%"> {{ $item->edom_qty }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">
                                @if ($item->id_periodetahun == 6 && $item->id_periodetipe == 3)
                                    {{ $item->nilai_edom }}
                                @elseif($item->id_periodetahun > 6)
                                    {{ $item->nilai_edom }}
                                @elseif($item->id_periodetahun < 6)
                                    {{ $item->nilai_edom_old }}
                                @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 1)
                                    {{ $item->nilai_edom_old }}
                                @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 2)
                                    {{ $item->nilai_edom_old }}
                                @endif
                            </span>
                        </center>
                    </td>
                    <td>
                        <center><span style="font-size:85%">
                                @if ($item->id_periodetahun == 6 && $item->id_periodetipe == 3)
                                    @if ($item->nilai_edom >= 80)
                                        A
                                    @elseif ($item->nilai_edom >= 75)
                                        B+
                                    @elseif ($item->nilai_edom >= 70)
                                        B
                                    @elseif ($item->nilai_edom >= 65)
                                        C+
                                    @elseif ($item->nilai_edom >= 60)
                                        C
                                    @elseif ($item->nilai_edom >= 50)
                                        D
                                    @elseif ($item->nilai_edom >= 0)
                                        E
                                    @endif
                                @elseif($item->id_periodetahun > 6)
                                    @if ($item->nilai_edom >= 80)
                                        A
                                    @elseif ($item->nilai_edom >= 75)
                                        B+
                                    @elseif ($item->nilai_edom >= 70)
                                        B
                                    @elseif ($item->nilai_edom >= 65)
                                        C+
                                    @elseif ($item->nilai_edom >= 60)
                                        C
                                    @elseif ($item->nilai_edom >= 50)
                                        D
                                    @elseif ($item->nilai_edom >= 0)
                                        E
                                    @endif
                                @elseif($item->id_periodetahun < 6)
                                    @if ($item->nilai_edom_old >= 80)
                                        A
                                    @elseif ($item->nilai_edom_old >= 75)
                                        B+
                                    @elseif ($item->nilai_edom_old >= 70)
                                        B
                                    @elseif ($item->nilai_edom_old >= 65)
                                        C+
                                    @elseif ($item->nilai_edom_old >= 60)
                                        C
                                    @elseif ($item->nilai_edom_old >= 50)
                                        D
                                    @elseif ($item->nilai_edom_old >= 0)
                                        E
                                    @endif
                                @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 1)
                                    @if ($item->nilai_edom_old >= 80)
                                        A
                                    @elseif ($item->nilai_edom_old >= 75)
                                        B+
                                    @elseif ($item->nilai_edom_old >= 70)
                                        B
                                    @elseif ($item->nilai_edom_old >= 65)
                                        C+
                                    @elseif ($item->nilai_edom_old >= 60)
                                        C
                                    @elseif ($item->nilai_edom_old >= 50)
                                        D
                                    @elseif ($item->nilai_edom_old >= 0)
                                        E
                                    @endif
                                @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 2)
                                    @if ($item->nilai_edom_old >= 80)
                                        A
                                    @elseif ($item->nilai_edom_old >= 75)
                                        B+
                                    @elseif ($item->nilai_edom_old >= 70)
                                        B
                                    @elseif ($item->nilai_edom_old >= 65)
                                        C+
                                    @elseif ($item->nilai_edom_old >= 60)
                                        C
                                    @elseif ($item->nilai_edom_old >= 50)
                                        D
                                    @elseif ($item->nilai_edom_old >= 0)
                                        E
                                    @endif
                                @endif
                            </span>
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
