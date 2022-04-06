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
                    <h3><b>FORM PENILAIAN PEMBIMBING</b></h3>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="15%" style="font-size:85%">Nama</td>
            <td width="1%" style="font-size:85%"> : </td>
            <td width="44%" style="font-size:85%">{{ $datadiri->nama }}</td>
            <td width="18%" style="font-size:85%">Program Studi</td>
            <td width="1%" style="font-size:85%"> : </td>
            <td width="21%" style="font-size:85%">{{ $datadiri->prodi }} </td>
        </tr>
        <tr>
            <td style="font-size:85%">NIM</td>
            <td style="font-size:85%">: </td>
            <td style="font-size:85%">{{ $datadiri->nim }}</td>
            <td style="font-size:85%">Kelas</td>
            <td style="font-size:85%"> : </td>
            <td style="font-size:85%">{{ $datadiri->kelas }} </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="15%" style="font-size:85%"> Judul Prakerin </td>
            <td width="1%" style="font-size:85%">:</td>
            <td width="84%" colspan="3" style="font-size:85%">{{ $datadiri->judul_prausta }}</td>
        </tr>
        <tr>
            <td style="font-size:85%">Tempat Prakerin</td>
            <td style="font-size:85%">:</td>
            <td colspan="3" style="font-size:85%">{{ $datadiri->tempat_prausta }}</td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th width="5%" style="font-size:85%">
                    <center>No</center>
                </th>
                <th width="53%" style="font-size:85%">
                    <center>Parameter Penilaian</center>
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
                    <td style="font-size:85%">
                        <center>{{ $item->bobot }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai }}</center>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" align="right" style="font-size:85%">Total</td>
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
            <td width="50%" align=center><span style="font-size:85%"></span></td>
            <td width="50%" align=left><span style="font-size:85%">Cikarang, {{ $tglhasil }}</span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%"></span></td>
            <td width="50%" align=left><span style="font-size:85%">Dosen Pembimbing</span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%"></span>
            </td>
            <td width="50%" align=left><span style="font-size:85%">({{ $datadiri->nama_dsn }},
                    {{ $datadiri->akademik }})</span>
            </td>
        </tr>
    </table>
</body>
