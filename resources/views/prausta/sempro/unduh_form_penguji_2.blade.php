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
    </table><br><br><br><br>
    <table width="100%">
        <tr>
            <td>
                <center>
                    <h4><b>FORM PENILAIAN SEMINAR PROPOSAL</b> <br> (Dosen Penguji II)</h4>

                </center>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="18%">Nama Mahasiswa</td>
            <td width="2%"> : </td>
            <td width="80%">{{ $datadiri->nama }}</td>
        </tr>
        <tr>
            <td width="18%">NIM</td>
            <td width="2%">: </td>
            <td width="80%">{{ $datadiri->nim }}</td>
        </tr>
        <tr>
            <td width="18%">Waktu Seminar</td>
            <td width="2%">: </td>
            <td width="80%">{{ $hari }}, {{ $tglhasil }}</td>
        </tr>
        <tr>
            <td>Judul Proposal </td>
            <td> : </td>
            <td>{{ $datadiri->judul_prausta }}</td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th width="5%" style="font-size:85%">
                    <center>No</center>
                </th>
                <th width="25%" style="font-size:85%">
                    <center>Komponen Penilaian</center>
                </th>
                <th width="28%" style="font-size:85%">
                    <center>Acuan Penilaian</center>
                </th>
                <th style="font-size:85%" width="10%">
                    <center>Bobot (%)</center>
                </th>
                <th style="font-size:85%" width="10%">
                    <center>Nilai</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($datanilai as $item)
                <tr>
                    <td style="font-size:85%">
                        <center>{{ $no++ }}</center>
                    </td>
                    <td style="font-size:85%">{{ $item->komponen }}</td>
                    <td style="font-size:85%">{{ $item->acuan }}</td>
                    <td style="font-size:85%">
                        <center>{{ $item->bobot }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai }}</center>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="right" style="font-size:85%">Total</td>
                <td style="font-size:85%">
                    <center>100 </center>
                </td>
                <td style="font-size:85%">
                    <center>{{ $hasil }}</center>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="50%">
                <span style="font-size: 100%">Panduan Penilaian</span>
                <table border="1" width="60%">
                    <tr>
                        <td align=center style="font-size:70%">Rentang Nilai</td>
                        <td align=center style="font-size:70%">Huruf Mutu</td>
                        <td align=center style="font-size:70%">Keterangan</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">A</td>
                        <td align=center style="font-size:70%">80 - 100</td>
                        <td align=center style="font-size:70%">Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">B+</td>
                        <td align=center style="font-size:70%">75 - 79</td>
                        <td align=center style="font-size:70%">Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">B</td>
                        <td align=center style="font-size:70%">70 - 74</td>
                        <td align=center style="font-size:70%">Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">C+</td>
                        <td align=center style="font-size:70%">65 - 69</td>
                        <td align=center style="font-size:70%">Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">C</td>
                        <td align=center style="font-size:70%">60 - 64</td>
                        <td align=center style="font-size:70%">Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">D</td>
                        <td align=center style="font-size:70%">50 - 59</td>
                        <td align=center style="font-size:70%">Tidak Lulus</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">E</td>
                        <td align=center style="font-size:70%">0 - 49</td>
                        <td align=center style="font-size:70%">Tidak Lulus</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="80%" align=left style="font-size:85%"><span>Cikarang,
                                {{ $tglhasil }}<span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="80%" align=left style="font-size:85%"><span>Dosen Penguji II</span></td>
                    </tr>
                </table>
                <br><br><br><br><br>
                <table width="100%">
                    <tr>
                        <td width="100%" align=left style="font-size:85%"><span>({{ $datadiri->nama_dsn }},
                                {{ $datadiri->akademik }})</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
