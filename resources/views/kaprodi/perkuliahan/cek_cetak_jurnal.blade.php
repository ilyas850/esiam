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
                    <img src="{{ asset('images/kop.png') }}" width="200" height="70" alt=""
                        align="right" />
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
                    {{ date('H:i', strtotime($bap->jam)) }} -
                    {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120) }}
                @elseif ($bap->id_kelas == 2 || $bap->id_kelas == 3)
                    {{ date('H:i', strtotime($bap->jam)) }} -
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
                <th width="4%">
                    <center>No</center>
                </th>
                <th width="12%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Tanggal </center>
                </th>
                <th width="14%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Jam</center>
                </th>
                <th>
                    <center>Materi</center>
                </th>
                <th width="12%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Paraf Dosen</center>
                </th>
                <th width="10%" nowrap="nowrap" style="white-space: nowrap;">
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
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center>{{ $item->tanggal ? Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '' }}</center>
                    </td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center>{{ $item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : '' }} - {{ $item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : '' }}</center>
                    </td>
                    <td>{{ $item->materi_kuliah }}</td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center>By System</center>
                    </td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
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
    <?php
        $uasItem = $data->first(function ($item) {
            $materi = isset($item->materi_kuliah) ? strtoupper(trim($item->materi_kuliah)) : '';
            $jenis = isset($item->jenis_kuliah) ? strtoupper(trim($item->jenis_kuliah)) : '';
            $tipe = isset($item->tipe_kuliah) ? strtoupper(trim($item->tipe_kuliah)) : '';

            return $materi === 'UAS' || strpos($materi, 'UAS') !== false || $jenis === 'UAS' || $tipe === 'UAS';
        });

        $tgl_cikarang = '.........................';
        if ($uasItem && !empty($uasItem->tanggal)) {
            $bulanArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $carbonTgl = \Carbon\Carbon::parse($uasItem->tanggal)->addDays(7);
            $tgl_cikarang = $carbonTgl->format('d') . ' ' . $bulanArr[(int)$carbonTgl->format('m')] . ' ' . $carbonTgl->format('Y');
        }
    ?>
    <div style="page-break-inside: avoid; break-inside: avoid;">
        <table width="100%">
            <tr>
                <td width="3%"></td>
                <td width="50%"></td>
                <td width="47%"><span style="font-size:85%">Cikarang, {{ $tgl_cikarang }}</span></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="3%"></td>
                <td width="50%"><span style="font-size:85%">Kepala Program Studi {{ $bap->prodi }}</span></td>
                <td width="47%"><span style="font-size:85%">Dosen Pengampu</span></td>
            </tr>
        </table>
        <br><br><br>
        <table width="100%">
            <tr>
                <td width="3%"></td>
                <td width="50%"><span style="font-size:85%">{{ $cekkprd ? $cekkprd->nama . ', ' . $cekkprd->akademik : '' }}</span></td>
                <td width="47%"><span style="font-size:85%">{{ $bap->nama }}, {{ $bap->akademik }}</span></td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
