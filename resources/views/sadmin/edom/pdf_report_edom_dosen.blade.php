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
                    <h4><b>REPORT REKAPITULASI EDOM PER DOSEN </b></h4>
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
    </table>
    <br>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th><span style="font-size:85%">No</span></th>
                <th><span style="font-size:85%">Dosen </span></th>
                <th><span style="font-size:85%">Makul Qty </span></th>
                <th><span style="font-size:85%">Mhs Qty</span></th>
                <th><span style="font-size:85%">Edom Qty</span></th>
                <th><span style="font-size:85%">Nilai Angka</span></th>
                <th><span style="font-size:85%">Nilai Huruf</span></th>
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
                        {{ $item->nama }}
                    </td>
                    <td>
                        <center>
                            {{ $item->makul_qty }}
                        </center>

                    </td>
                    <td>
                        <center>
                            {{ $item->mhs_qty }}
                        </center>
                    </td>
                    <td>
                        <center>
                            {{ $item->edom_qty }}
                        </center>
                    </td>
                    <td>
                        <center>
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
                        </center>
                    </td>
                    <td>
                        <center>
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
                        </center>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
