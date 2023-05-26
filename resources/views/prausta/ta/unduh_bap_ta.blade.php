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
                    <h3><b>BERITA ACARA SIDANG TUGAS AKHIR/SKRIPSI</b></h3>
                </center>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td>Nama Mahasiswa</td>
            <td> : </td>
            <td>{{ $data->nama }}</td>
        </tr>
        <tr>
            <td width="18%">NIM</td>
            <td width="2%">: </td>
            <td width="80%">{{ $data->nim }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td> : </td>
            <td>{{ $data->prodi }}</td>
        </tr>
        <tr>
            <td>Laporan </td>
            <td> : </td>
            <td>{{ $data->judul_prausta }}</td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>
                Berdasarkan penilaian pelaksanaan Tugas Akhir/Skripsi dan Ujian Sidang Tugas Akhir/Skripsi dibawah ini :
            </td>
        </tr>
    </table>
    <table width="100%" border="1">
        <tr>
            <td width="30%" align="center">Komponen Penilaian</td>
            <td width="15%" align="center">Bobot</td>
            <td width="15%" align="center">Nilai</td>
            <td width="15%" align="center">Nilai x Bobot</td>
        </tr>
        <tr>
            <td colspan="3"> Nilai Bimbingan Tugas Akhir/Skripsi</td>
            <td align="center"></td>
        </tr>
        <tr>
            <td>Dosen Pembimbing</td>
            <td align="center">60%</td>
            <td align="center">{{ $data->nilai_1 }}</td>
            <td align="center">{{ ($data->nilai_1 * 60) / 100 }}</td>
        </tr>
        <tr>
            <td colspan="3">Nilai Sidang Tugas Akhir/Skripsi</td>
            <td align="center"></td>
        </tr>
        <tr>
            <td>Dosen Penguji I</td>
            <td align="center">20%</td>
            <td align="center">{{ $data->nilai_2 }}</td>
            <td align="center">{{ ($data->nilai_2 * 20) / 100 }}</td>
        </tr>
        <tr>
            <td>Dosen Penguji II</td>
            <td align="center">20%</td>
            <td align="center">{{ $data->nilai_3 }}</td>
            <td align="center">{{ ($data->nilai_3 * 20) / 100 }}</td>
        </tr>
        <tr>
            <td align="right" colspan="3">Nilai rata-rata (Angka)</td>
            <td align="center">
                {{ ($data->nilai_1 * 60) / 100 + ($data->nilai_2 * 20) / 100 + ($data->nilai_3 * 20) / 100 }}
            </td>
        </tr>
        <tr>
            <td align="right" colspan="3">Nilai rata-rata (Huruf)</td>
            <td align="center">{{ $data->nilai_huruf }}</td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>
                Hasil penilaian menyatakan bahwa nama tersebut di atas dinyatakan :
            </td>
        </tr>
    </table>
   
    <table width="100%" border="1">
        <tr>
            <td align="center">
                @if ($data->nilai_huruf == 'A' or $data->nilai_huruf == 'B+' or $data->nilai_huruf == 'B' or $data->nilai_huruf == 'C+' or $data->nilai_huruf == 'C')
                    <h3>LULUS / <s>TIDAK LULUS</s></h3>
                @elseif($data->nilai_huruf == 'D' or $data->nilai_huruf == 'E')
                    <h3> <s style="height: 30px">LULUS</s> / TIDAK LULUS</h3>
                @endif

            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td>
                Yang memberi penilaian :
            </td>
        </tr>
    </table>
    
    <table width="100%" border="1">
        <tr>
            <td align="center" width="5%">NO</td>
            <td align="center" width="15%">KOMPONEN</td>
            <td align="center" width="50%">NAMA</td>
            <td align="center" width="20%">TANDA TANGAN</td>
        </tr>
        <tr height="100%">
            <td align="center">1</td>
            <td>Pembimbing</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospem->nama }}, {{ $dospem->akademik }}
                @endif
            </td>
            <td></td>
        </tr>
        <tr height="100%">
            <td align="center">2</td>
            <td>Penguji I</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospeng1->nama }}, {{ $dospeng1->akademik }} 
                @endif
            </td>
            <td></td>
        </tr>
        <tr height="100%">
            <td align="center">3</td>
            <td>Penguji II</td>
            <td style="font-size:85%">
                @if ($dospem != null)
                    {{ $dospeng2->nama }}, {{ $dospeng2->akademik }}
                @endif
            </td>
            <td></td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td width="60%">
                <span style="font-size: 80%">Kriteria Penilaian</span>
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
