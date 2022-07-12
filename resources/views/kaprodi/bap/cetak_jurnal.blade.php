<!DOCTYPE html>
<html lang="en">

<head>
    <title>Politeknik META Industri Cikarang</title>
</head>

<body>
    <table width="100%">
        <tr>
            <td>
                <img src="{{ asset('images/logo meta png.png') }}" width="200" height="75" alt=""
                    align="left" />
            </td>
            <td>
                <center>
                    <img src="{{ asset('images/kop.png') }}" width="200" height="70" alt="" align="right" />
                </center>
            </td>
        </tr>
    </table><br>
    <table width="100%">
        <tr>
            <td>Matakuliah</td>
            <td>:</td>
            <td>{{ $bap->makul }} - {{ $bap->akt_sks }} SKS</td>
            <td>Tahun Akademik</td>
            <td>:</td>
            <td>{{ $bap->periode_tahun }} {{ $bap->periode_tipe }}</td>
        </tr>
        <tr>
            <td>Waktu / Ruangan</td>
            <td>:</td>
            <td>{{ $bap->hari }},
                @if ($bap->id_kelas == 1)
                    {{ $bap->jam }} -
                    {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120) }}
                @elseif ($bap->id_kelas == 2)
                    {{ $bap->jam }} -
                    {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90) }}
                @elseif ($bap->id_kelas == 3)
                    {{ $bap->jam }} -
                    {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90) }}
                @endif
                / {{ $bap->nama_ruangan }}
            </td>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $bap->prodi }}</td>
        </tr>
        <tr>
            <td>Dosen</td>
            <td>:</td>
            <td>{{ $bap->nama }}, {{ $bap->akademik }}</td>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $bap->kelas }}</td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>
                    <center>No</center>
                </th>
                <th>
                    <center>Tanggal </center>
                </th>
                <th>
                    <center>Jam</center>
                </th>
                <th>
                    <center>Jam Validasi</center>
                </th>
                <th>
                    <center>Materi</center>
                </th>
                <th>
                    <center>Paraf Dosen</center>
                </th>
                <th>
                    <center>Validasi</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td>
                        <center>{{ $no++ }}</center>
                    </td>
                    <td>
                        <center>{{ $item->tanggal }}</center>
                    </td>
                    <td>
                        <center>{{ $item->jam_mulai }} - {{ $item->jam_selsai }}</center>
                    </td>
                    <td>
                        <center>{{ $item->val_jam_mulai }} - {{ $item->val_jam_selesai }}</center>
                    </td>
                    <td>{{ $item->materi_kuliah }}</td>
                    <td>
                        <center>By System</center>
                    </td>
                    <td>
                        <center>
                            @if ($item->tanggal_validasi == '2001-01-01')
                                <span class="badge bg-info">Sudah</span>
                            @else
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <table width="100%">
        <tr>
            <td width="34%" align=left><span style="font-size:85%">*) Validasi dilakukan oleh Prodi (Sekretaris
                    Prodi) setiap hari</span></td>
            <td width="33%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%">Cikarang, {{ $d }}
                    {{ $m }} {{ $y }}</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="34%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%">Kepala Program Studi</span><br><span
                    style="font-size:85%">{{ $bap->prodi }}</span></td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td width="34%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%">(..........................................)
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="34%" align=center><span style="font-size:85%"></span></td>
            <td width="33%" align=center><span style="font-size:85%"></span></td>
            <td width="19%" align=left><span style="font-size:85%">NIDN.</span></td>
        </tr>
    </table>
    <script>
        window.print();
    </script>
</body>

</html>
