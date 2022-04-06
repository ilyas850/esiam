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
                    <h4><b>KARTU RENCANA STUDI MAHASISWA</b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td><b><span style="font-size:65%">Nama </span></b></td>
            <td> : </td>
            <td><b><span style="font-size:65%"><u>{{ $mhs->nama }}</u></span></b></td>
            <td><b><span style="font-size:65%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:65%"><u>{{ $mhs->kelas }}</span></u></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:65%">NIM</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:65%"><u>{{ $mhs->nim }}</u></span></b></td>
            <td><b><span style="font-size:65%">Tahun Ajaran</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:65%"><u>{{ $thn->periode_tahun }} {{ $tp->periode_tipe }}
                        </u></span></b>
            </td>
        </tr>
        <tr>
            <td><b><span style="font-size:65%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:65%"><u>{{ $mhs->prodi }}</u></span></b></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th align=center width="3%"><span style="font-size:65%">No</span></th>
                <th width="7%"><span style="font-size:65%">Kode </span></th>
                <th><span style="font-size:65%">Nama Matakuliah</span></th>
                <th align=center width="8%"><span style="font-size:65%">Semester</span></th>
                <th width="5%"><span style="font-size:65%">SKS Teori</span></th>
                <th width="5%"><span style="font-size:65%">SKS Praktek</span></th>
                <th width="12%"><span style="font-size:65%">Waktu</span></th>
                <th width="12%"><span style="font-size:65%">Ruangan</span></th>
                <th><span style="font-size:65%">Dosen</span></th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 0;
            @endphp
            @foreach ($krs as $item)
                <tr>
                    <td align=center><span style="font-size:50%"><b>{{ ++$i }}</b></span></td>
                    <td><span style="font-size:50%">
                            <center><b>{{ $item->kode }}</center></b>
                        </span></td>
                    <td><span style="font-size:50%"><b>{{ $item->makul }}</b></span></td>
                    <td align=center><span style="font-size:50%"><b>{{ $item->semester }}</b></span></td>
                    <td align=center><span style="font-size:50%"><b>{{ $item->akt_sks_teori }}</b></span></td>
                    <td align=center><span style="font-size:50%"><b>{{ $item->akt_sks_praktek }}</b></span></td>
                    <td align=center><span style="font-size:50%"><b>{{ $item->hari }},
                                {{ $item->jam }}</b></span></td>
                    <td align=center><span style="font-size:50%"><b>{{ $item->nama_ruangan }}</b></span></td>
                    <td><span style="font-size:50%"><b>{{ $item->nama }}</b></span></td>
                </tr>
            @endforeach
            <tr>
                <td><span style="font-size:50%">.</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><span style="font-size:50%">.</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><span style="font-size:50%">.</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" align=right><span style="font-size:65%">Jumlah SKS</span></th>
                <th colspan="2" align=center><span style="font-size:65%">{{ $sks }}</span></th>
                <th colspan="3" align=center><span style="font-size:65%"></span></th>
            </tr>
        </tfoot>
    </table>
    <span style="font-size:55%">*Note : untuk matakuliah yang mengulang bisa diisi di 3 baris kosong di atas dan diisi
        oleh dosen pembimbing</span>
    <br><br>
    <table width="100%">
        <tr>
            <td width="33%" align=center><span style="font-size:65%"></span></td>
            <td width="34%" align=center><span style="font-size:65%"></span></td>
            <td width="33%" align=center><span style="font-size:65%">Cikarang, {{ $d }} {{ $m }}
                    {{ $y }}</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="33%" align=center><span style="font-size:65%">Diisi oleh,</span></td>
            <td width="34%" align=center><span style="font-size:65%">Diketahui oleh,</span></td>
            <td width="33%" align=center><span style="font-size:65%">Diterima oleh,</span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="33%" align=center><span style="font-size:65%">{{ $mhs->nama }}</span></td>
            <td width="34%" align=center><span style="font-size:65%">Dosen Pembimbing/Kaprodi</span></td>
            <td width="33%" align=center><span style="font-size:65%">BAAK</span></td>
        </tr>
    </table>
    <br>
</body>
