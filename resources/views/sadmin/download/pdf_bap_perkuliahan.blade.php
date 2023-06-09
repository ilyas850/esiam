<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Politeknik META Industri</title>
    <style>
        /* CSS untuk mengatur tampilan PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            padding: 10px;
        }

        table {
            border-collapse: collapse;
        }

        tr.b {
            line-height: 80px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <img src="images/logo meta png.png" width="200" height="75" alt="" align="left">
                </td>
                <td>
                    <center>
                        <img src="images/kop.png" width="200" height="70" alt="" align="right">
                    </center>
                </td>
            </tr>
        </table>
    </div>
    <br><br><br>
    <div class="content">
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
                        {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 170) }}
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
                            <center>{{ Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</center>
                        </td>
                        <td>
                            <center>{{ Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                - {{ Carbon\Carbon::parse($item->jam_selsai)->format('H:i') }}</center>
                        </td>
                        <td>{{ $item->materi_kuliah }}</td>
                        <td>
                            <center>By System</center>
                        </td>
                        <td>
                            <center>
                                @if ($item->tanggal_validasi == '2001-01-01')
                                    BELUM
                                @else
                                    SUDAH
                                @endif
                            </center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td width="67%"><span style="font-size:85%">*) Validasi dilakukan oleh Prodi (Sekretaris Prodi) setiap
                        hari</span></td>
                <td width="33%"></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                
                <td width="60%"></td>
                <td width="40%">Cikarang, .........................</td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                
                <td width="60%">Kepala Program Studi {{ $bap->prodi }}</td>
                <td width="40%">Dosen Pengampu</td>
            </tr>
        </table>
        <br><br><br><br>
        <table width="100%">
            <tr>
                
                <td width="60%">{{ $cekkprd->nama }}, {{ $cekkprd->akademik }}</td>
                <td width="40%">{{ $bap->nama }}, {{ $bap->akademik }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        
    </div>
</body>

</html>
