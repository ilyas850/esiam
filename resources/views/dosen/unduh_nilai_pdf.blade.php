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
                    <h4><b>DAFTAR NILAI AKHIR</b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><b><span style="font-size:85%">Kode Matakuliah </span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->kode }}</u></span></b></td>
            <td><b><span style="font-size:85%">Tahun Akademik </span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->periode_tahun }}
                            {{ $data->periode_tipe }}</u></span></b>
            </td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Matakuliah</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->makul }} - {{ $data->akt_sks }} SKS</span></u></b></td>
            <td><b><span style="font-size:85%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->prodi }}</u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Dosen</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->nama }}, {{ $data->akademik }}</u></span></b></td>
            <td><b><span style="font-size:85%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->kelas }}</u></span></b></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th><span style="font-size:85%">No</span></th>
                <th><span style="font-size:85%">NIM </span></th>
                <th><span style="font-size:85%">Nama Mahasiswa</span></th>
                <th><span style="font-size:85%">Nilai KAT</span></th>
                <th><span style="font-size:85%">Nilai UTS</span></th>
                <th><span style="font-size:85%">Nilai UAS</span></th>
                <th><span style="font-size:85%">Nilai AKHIR</span></th>
                <th><span style="font-size:85%">Nilai HURUF</span></th>
            </tr>
        </thead>
        <tbody>
            @php $i=1 @endphp
            @foreach ($tb as $item)
                <tr>
                    <td style="font-size:85%">
                        <center>{{ $i++ }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nim }}</center>
                    </td>
                    <td style="font-size:85%">{{ $item->nama }}</td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_KAT }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_UTS }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_UAS }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_AKHIR_angka }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_AKHIR }}</center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="34%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%">Cikarang, ..............................</span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%"></span></td>
            <td width="50%" align=center><span style="font-size:85%">Dosen Pengampu</span></td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td width="50%" align=center><span style="font-size:85%"></span></td>
            <td width="50%" align=center><span style="font-size:85%">({{ $data->nama }}
                    {{ $data->akademik }})</span></td>
        </tr>
    </table>
    <br>
</body>
