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
                    <h4><b>REPORT KUISIONER BAAK {{ $namaperiodetahun }} -
                            {{ $namaperiodetipe }} </b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th><span style="font-size:85%">No</span></th>
                <th><span style="font-size:85%">Prodi </span></th>
                <th><span style="font-size:85%">Mhs Qty</span></th>
                <th><span style="font-size:85%">Kuisioner Qty</span></th>
                <th><span style="font-size:85%">Nilai Angka</span></th>
                <th><span style="font-size:85%">Nilai Huruf</span></th>
            </tr>

        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach ($data as $item)
                <tr>
                    <td style="font-size:85%">
                        <center>{{ $i++ }}</center>
                    </td>
                    <td style="font-size:85%">
                        {{ $item->prodi }}
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->mhs_qty }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->kuisioner_qty }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_angka }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>
                            @if ($item->nilai_angka >= 80)
                                A
                            @elseif ($item->nilai_angka >= 75)
                                B+
                            @elseif ($item->nilai_angka >= 70)
                                B
                            @elseif ($item->nilai_angka >= 65)
                                C+
                            @elseif ($item->nilai_angka >= 60)
                                C
                            @elseif ($item->nilai_angka >= 50)
                                D
                            @elseif ($item->nilai_angka >= 0)
                                E
                            @endif
                        </center>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
