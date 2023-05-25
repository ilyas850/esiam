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
                    <h3><b>KARTU BIMBINGAN PKL/Magang</b></h3>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="25%" style="font-size:85%"><b>Nama </b></td>
            <td width="2%" style="font-size:85%"> : </td>
            <td width="50%" style="font-size:85%"><b>{{ $mhs->nama }}</b></td>
            <td width="18%" style="font-size:85%"><b>Program Studi</b></td>
            <td width="2%" style="font-size:85%"> : </td>
            <td width="20%" style="font-size:85%"><b>{{ $mhs->prodi }} </b></td>
        </tr>
        <tr>
            <td style="font-size:85%"><b>NIM</b></td>
            <td style="font-size:85%">: </td>
            <td style="font-size:85%"><b>{{ $mhs->nim }}</b></td>
            <td style="font-size:85%"><b>Kelas</b></td>
            <td style="font-size:85%"> : </td>
            <td style="font-size:85%"><b>{{ $mhs->kelas }}</b></td>
        </tr>
    </table>
    <br>
    <table width="100%">

        <tr>
            <td width="11%" style="font-size:85%"> <b>Judul PKL/Magang</b> </td>
            <td width="1%" style="font-size:85%">:</td>
            <td width="40%" colspan="3" style="font-size:85%"><b>{{ $mhs->judul_prausta }}</b></td>
        </tr>
        <tr>
            <td style="font-size:85%"><b>Dosen Pembimbing</b></td>
            <td style="font-size:85%">:</td>
            <td colspan="3" style="font-size:85%"><b>{{ $mhs->dosen_pembimbing }}, {{ $mhs->akademik }}</b></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <td width="5%">
                    <center>No</center>
                </td>
                <th width="13%">
                    <center>Tanggal Bimbingan</center>
                </th>
                <th>
                    <center>Uraian Bimbingan</center>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $key)
                <tr height="1500%">
                    <td>
                        <center>{{ $no++ }}</center>
                    </td>
                    <td>
                        <center>{{ $key->tanggal_bimbingan }}</center>
                    </td>
                    <td>{{ $key->remark_bimbingan }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%"></span></td>
            <td width="50%" align=left><span style="font-size:85%">Cikarang, .................................</span>
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
            <td width="50%" align=left><span style="font-size:85%">({{ $mhs->dosen_pembimbing }},
                    {{ $mhs->akademik }})</span>
            </td>
        </tr>
    </table>
</body>
