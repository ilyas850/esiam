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
                    <h3><b>BERITA ACARA SEMINAR PROPOSAL PENELITIAN MAHASISWA</b></h3>
                </center>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td>
                <center>
                    <h3><b> <u>TELAH DISEMINARKAN</u></b></h3>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="18%">Nama Mahasiswa</td>
            <td width="2%"> : </td>
            <td width="80%">{{ $data->nama }}</td>
        </tr>
        <tr>
            <td width="18%">NIM</td>
            <td width="2%">: </td>
            <td width="80%">{{ $data->nim }}</td>
        </tr>

        <tr>
            <td>Judul Proposal </td>
            <td> : </td>
            <td>{{ $data->judul_prausta }}</td>
        </tr>
    </table>
    <br><br>
    <table width="100%">
        <tr>
            <td>
                Untuk dapat melakukan penelitian dalam rangka penulisan Laporan Akhir dengan hasil nilai sebagai
                berikut:
            </td>
        </tr>
    </table>
    <table width="100%" border="1">
        <tr>
            <td width="35%"></td>
            <td width="50%" align="center">Dosen</td>
            <td width="15%" align="center">Nilai Proposal</td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospem->nama }}, {{ $dospem->akademik }}
                @endif
            </td>
            <td align="center">{{ $data->nilai_1 }}</td>
        </tr>
        <tr>
            <td>Penguji I</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospeng1->nama }}, {{ $dospeng1->akademik }}
                @endif
            </td>
            <td align="center">{{ $data->nilai_2 }}</td>
        </tr>
        <tr>
            <td>Penguji II</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospeng2->nama }}, {{ $dospeng2->akademik }}
                @endif
            </td>
            <td align="center">{{ $data->nilai_3 }}</td>
        </tr>

        <tr>
            <td colspan="2">Nilai Akhir Angka (rata-rata)</td>
            <td align="center">{{ round(($data->nilai_1 + $data->nilai_2 + $data->nilai_3) / 3, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">Nilai Huruf</td>
            <td align="center">{{ $data->nilai_huruf }}</td>
        </tr>
    </table>
    
    <br>
    <table width="100%">
        <tr>
            <td width="60%">
                <span style="font-size: 80%">Kriteria Penilaian</span>
                <table border="1" width="40%">
                    <tr>
                        <td align=center style="font-size:70%" width="20%">Rentang Nilai</td>
                        <td align=center style="font-size:70%" width="20%">Huruf Mutu</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">A</td>
                        <td align=center style="font-size:70%">80 - 100</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">B+</td>
                        <td align=center style="font-size:70%">75 - 79</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">B</td>
                        <td align=center style="font-size:70%">70 - 74</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">C+</td>
                        <td align=center style="font-size:70%">65 - 69</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">C</td>
                        <td align=center style="font-size:70%">60 - 64</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">D</td>
                        <td align=center style="font-size:70%">50 - 59</td>
                    </tr>
                    <tr>
                        <td align=center style="font-size:70%">E</td>
                        <td align=center style="font-size:70%">0 - 49</td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <table width="100%">
                    <tr>
                        <td width="80%" align=left style="font-size:85%"><span>Cikarang,
                                {{ $tglhasil }}</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>

                        <td width="80%" align=left style="font-size:85%"><span>Dosen Pembimbing</span></td>
                    </tr>
                </table>
                <br><br><br><br>
                <table width="100%">
                    <tr>
                        <td width="100%" align=left style="font-size:85%"><span>{{ $data->nama_dsn }},
                                {{ $data->akademik }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
