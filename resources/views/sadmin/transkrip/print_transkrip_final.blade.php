<style media="screen">
    table {
        border-collapse: collapse;
    }

    tr.b {
        line-height: 50px;
    }

</style>

<body>
    <table width="100%">
        <tr>
            <center><b><span style="font-size:205%">POLITEKNIK META INDUSTRI CIKARANG</span></b></center>
        </tr>
        <tr>
            <center><b><span style="font-size:145%">PROGRAM PENDIDIKAN VOKASI</span></b></center>
        </tr>
        <tr>
            <center>
                <span style="font-size:85%">(Berdasarkan Keputusan Menteri Pendidikan dan Kebudayaan <br>
                    Nomor 404/E/O/2014 tanggal 11 September 2014)</span>
            </center>
        </tr>
    </table>
    <hr style="height:2px;color:black;background-color:black">
    <table width="100%">
        <tr>
            <center><b><u><span>TRANSKRIP NILAI</span></u></b></center>
        </tr>
        <tr>
            <td width="30%"></td>
            <td width="14%">No. Ijazah</td>
            <td>:</td>
            <td>{{ $item->no_ijazah }}</td>
        </tr>
        <tr>
            <td></td>
            <td>No. Transkrip</td>
            <td>:</td>
            <td>{{ $item->no_transkrip_final }}</td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="20%"><span style="font-size:65%">Nama</span></td>
            <td>: <span style="font-size:65%">{{ $nama }}</span></td>
        </tr>
        <tr>
            <td><span style="font-size:65%">Tempat dan Tanggal Lahir</span></td>
            <td><span style="font-size:65%">: {{ $item->tmptlahir }},
                    {{ $item->tgllahir->isoFormat('D MMMM Y') }}</span></td>
        </tr>
        <tr>
            <td><span style="font-size:65%">Nomor Induk Mahasiswa</span></td>
            <td><span style="font-size:65%">: {{ $item->nim }}</span></td>
        </tr>
        <tr>
            <td><span style="font-size:65%">Program Pendiidkan</span></td>
            <td><span style="font-size:65%">: DIPLOMA III (D-III)</span></td>
        </tr>
        <tr>
            <td><span style="font-size:65%">Program Studi STUDI</span></td>
            <td><span style="font-size:65%">: {{ $item->prodi }}</span></td>
        </tr>

        <tr>
            <td><span style="font-size:65%">Tanggal Kelulusan</span></td>
            <td><span style="font-size:65%">: </span></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th rowspan="2">
                    <center><span style="font-size:65%">No</span></center>
                </th>
                <th rowspan="2">
                    <center><span style="font-size:65%">Kode MK</span></center>
                </th>
                <th rowspan="2">
                    <center><span style="font-size:65%">NAMA MATAKULIAH</span></center>
                </th>
                <th rowspan="2">
                    <center><span style="font-size:65%">SKS</span></center>
                </th>
                <th colspan="2">
                    <center><span style="font-size:65%">NILAI</span></center>
                </th>
                <th rowspan="2">
                    <center><span style="font-size:65%">NILAI x SKS</span></center>
                </th>
            </tr>
            <tr>
                <th>
                    <center><span style="font-size:65%">HURUF</span></center>
                </th>
                <th>
                    <center><span style="font-size:65%">ANGKA</span></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $key)
                <tr>
                    <td>
                        <center><span style="font-size:65%">{{ $no++ }}</span></center>
                    </td>
                    <td>
                        <center><span style="font-size:65%">{{ $key->kode }}</span></center>
                    </td>
                    <td><span style="font-size:65%">{{ $key->makul }}</span></td>
                    <td>
                        <center><span style="font-size:65%">{{ $key->akt_sks }}</span></center>
                    </td>
                    <td>
                        <center><span style="font-size:65%">{{ $key->nilai_AKHIR }}</span></center>
                    </td>
                    <td>
                        <center><span style="font-size:65%">{{ $key->nilai_ANGKA }}</span></center>
                    </td>
                    <td>
                        <center><span style="font-size:65%">{{ $key->nilai_sks }}</span></center>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3">
                    <center><span style="font-size:65%">TOTAL SKS</span></center>
                </td>
                <td>
                    <center>
                        <span style="font-size:65%">{{ $keysks->total_sks }}</span>
                    </center>
                </td>
                <td colspan="2"></td>
                <td>
                    <center>
                        <span style="font-size:65%">{{ $keysks->nilai_sks }}</span>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table border="1" width="100%">
        <tbody>
            <tr>
                <td width="18%"><span style="font-size:65%">Judul Laporan</span></td>
                <td><span style="font-size:65%"> : - </span></td>
            </tr>
            <tr>
                <td><span style="font-size:65%">Pembimbing</span></td>
                <td><span style="font-size:65%"> : -</span></td>
            </tr>
            <tr>
                <td><span style="font-size:65%">Indeks Prestasi Kumulatif</span></td>
                <td>:<span style="font-size:65%"> {{ $keysks->nilai_sks }} / {{ $keysks->total_sks }} =
                        {{ $keysks->IPK }}</span> </td>
            </tr>
            <tr>
                <td><span style="font-size:65%">Predikat Kelulusan</span></td>
                <td><span style="font-size:65%">: - </span></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="60%"><span style="font-size:65%"></span></td>
            <td width="33%"><span style="font-size:65%">Cikarang, </span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="60%"><span style="font-size:65%"></span></td>
            <td width="33%"><span style="font-size:65%">Wakil Direktur I Bidang Akademik</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="60%"><span style="font-size:65%"></span></td>
            <td width="33%"><span style="font-size:65%">Politeknik META Industri</span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="60%"><span style="font-size:65%"></span></td>
            <td width="33%"><span style="font-size:65%">Fransisca Debora, S.Si., M.T.</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="60%"><span style="font-size:65%"></span></td>
            <td width="33%"><span style="font-size:65%">NIDN. 0414029402</span></td>
        </tr>
    </table>
    <script>
        window.print();
    </script>
</body>
