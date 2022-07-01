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
                    <h4><b>BERITA ACARA SEMINAR PKL</b></h4>
                </center>
            </td>
        </tr>
    </table>


    <table width="100%">
        <tr>
            <td width="25%">Nama Mahasiswa</td>
            <td width="2%"> : </td>
            <td width="68%">{{ $data->nama }}</td>
        </tr>
        <tr>
            <td width="18%">NIM</td>
            <td width="2%">: </td>
            <td width="80%">{{ $data->nim }}</td>
        </tr>
        <tr>
            <td width="18%">Dosen Pembimbing</td>
            <td width="2%">: </td>
            <td width="80%">{{ $data->dosen_pembimbing }}</td>
        </tr>
        <tr>
            <td width="18%">Pembimbing Lapangan</td>
            <td width="2%">: </td>
            <td width="80%"></td>
        </tr>
        <tr>
            <td>Judul Laporan PKL </td>
            <td> : </td>
            <td>{{ $data->judul_prausta }}</td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>
                <p align="justify">
                    Pada tanggal {{ $tglhasil }}, telah diselenggarakan Seminar PKL terhadap mahasiswa Politeknik
                    META Industri
                    Cikarang. Berdasarkan kebutuhan penilaian terhadap mahasiswa, maka dilakukan seminar laporan PKL.
                    Adapun keterangan hasil penilaian seminar PKL, sebagai berikut:
                </p>
            </td>
        </tr>
        <tr>
            <td>
                Keterangan Hasil Seminar PKL :
            </td>
        </tr>
    </table>
    <table width="100%" border="1">
        <tr>
            <td rowspan="2">Parameter Penilaian</td>
            <td colspan="3" align="center">Nilai</td>
        </tr>
        <tr>
            <td align="center">Pembimbing Lapangan (1)</td>
            <td align="center">Dosen Pembimbing (2)</td>
            <td align="center">Ujian Seminar (3)</td>
        </tr>
        <tr height="10px">
            <td align="center">Total Nilai</td>
            <td align="center">{{ $data->nilai_1 }}</td>
            <td align="center">{{ $data->nilai_2 }}</td>
            <td align="center">{{ $data->nilai_3 }}</td>
        </tr>
        <tr height="10px">
            <td align="center">Nilai Akhir Angka (rata-rata)</td>
            <td colspan="3" align="center">{{ round(($data->nilai_1 + $data->nilai_2 + $data->nilai_3) / 3, 2) }}
            </td>

        </tr>
        <tr>
            <td align="center">Nilai Akhir Huruf</td>
            <td colspan="3" align="center">{{ $data->nilai_huruf }}</td>

        </tr>
    </table>
    <br>

    <table width="100%">
        <tr>
            <td width="60%">
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
            <td width="40%">

            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="60%" align=left style="font-size:85%"></td>
            <td width="40%" align=left style="font-size:85%"><span>Cikarang,
                    {{ $tglhasil }}</span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="60%" align=left style="font-size:85%">Kepala Program Studi </td>
            <td width="40%" align=left style="font-size:85%"><span>Dosen Pembimbing</span></td>
        </tr>
    </table>
    <br><br><br><br><br>
    <table width="100%">
        <tr>
            <td width="60%" align=left style="font-size:85%"><span>{{ $nama_kaprodi }},
                    {{ $akademik_kaprodi }}</span>
            </td>
            <td width="40%" align=left style="font-size:85%"><span>{{ $data->nama_dsn }},
                    {{ $data->akademik }}</span>
            </td>
        </tr>
        <tr>
            <td width="60%" align=left style="font-size:85%"><span>NIP : {{ $nik_kaprodi }}</span>
            </td>
            <td width="40%" align=left style="font-size:85%"><span>NIP : {{ $data->nik }}</span>
            </td>
        </tr>
    </table>
</body>
