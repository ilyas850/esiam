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
                    <h4><b>KARTU UJIAN TENGAH SEMESTER (UTS)</b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><span style="font-size:85%">Nama </span></td>
            <td> : </td>
            <td><span style="font-size:85%">{{ $datamhs->nama }}</span></td>


            <td><span style="font-size:85%">Tahun Ajaran</span></td>
            <td> : </td>
            <td><span style="font-size:85%">{{ $periodetahun }}</span>
            </td>
        </tr>
        <tr>
            <td><span style="font-size:85%">NIM</span></td>
            <td> : </td>
            <td><span style="font-size:85%">{{ $datamhs->nim }}</span></td>
            <td><span style="font-size:85%">Semester</span></td>
            <td> : </td>
            <td><span style="font-size:85%">{{ $periodetipe }}</span></td>
        </tr>
        <tr>
            <td><span style="font-size:85%">Program Studi</span></td>
            <td> : </td>
            <td><span style="font-size:85%"> {{ $datamhs->prodi }} </span></td>
            <td><span style="font-size:85%">Kelas</span></td>
            <td> : </td>
            <td><span style="font-size:85%"> {{ $datamhs->kelas }} </span>
            </td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>

            <tr>
                <th><span style="font-size:80%">No</span></th>
                <th><span style="font-size:80%">Jadwal Ujian </span></th>
                <th><span style="font-size:80%">Waktu Ujian</span></th>
                <th><span style="font-size:80%">Kode MK</span></th>
                <th><span style="font-size:80%">Matakuliah</span></th>
                <th><span style="font-size:80%">Ruangan</span></th>
                <th><span style="font-size:80%">Ttd <br> Pengawas</span></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data_uts as $item)
                <tr>
                    <td align="center"><span style="font-size:80%">{{ $no++ }}</span></td>
                    <td>
                        <span style="font-size:80%">
                            {{ Carbon\Carbon::parse($item->tanggal_ujian)->formatLocalized('%A, %d %B %Y') }}</span>
                    </td>
                    <td align="center">
                        <span style="font-size:80%">{{ $item->jam }} -
                            {{ date('H:i', strtotime($item->jam) + 60 * 100) }}
                        </span>
                    </td>
                    <td align="center"><span style="font-size:80%">{{ $item->kode }}</span></td>
                    <td><span style="font-size:80%">{{ $item->makul }}</span></td>
                    <td align="center"><span style="font-size:80%">{{ $item->nama_ruangan }}</span></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%">

        <td width="60%">
            <span style="font-size: 80%"> <b> Ketentuan mengikuti ujian :</b></span>
            <br>
            <span style="font-size: 80%">- Membawa Kartu Ujian </span><br>
            <span style="font-size: 80%">- Memakai Jas Almamater</span> <br>
            <span style="font-size: 80%">- Membawa Kartu Tanda Mahasiswa</span>

        </td>
        <td width="40%">
            <table width="100%">
                <tr>
                    <td width="80%" align=left style="font-size:85%"><span>Cikarang,
                            {{ $d }} {{ $m }} {{ $y }}</span>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <img src="images/validated.png" width="150" height="50" />
                    </td>
                </tr>
            </table>
            <br>
            <table width="100%">
                <tr>
                    <td width="100%" align=left style="font-size:85%"><span>Kepala BAAK</span>
                    </td>
                </tr>
            </table>
        </td>

    </table>
</body>
