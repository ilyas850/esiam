<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Politeknik META Industri</title>
    <style>
        /* CSS untuk mengatur tampilan PDF */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .check-mark {
            font-family: DejaVu Sans, sans-serif;
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
    <div style="clear: both; margin-bottom: 10px;"></div>
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
                    <th width="4%">
                        <center>No</center>
                    </th>
                    <th width="10%">
                        <center>NIM </center>
                    </th>
                    <th>
                        <center>Nama</center>
                    </th>
                    <th width="3%">
                        <center>1</center>
                    </th>
                    <th width="3%">
                        <center>2</center>
                    </th>
                    <th width="3%">
                        <center>3</center>
                    </th>
                    <th width="3%">
                        <center>4</center>
                    </th>
                    <th width="3%">
                        <center>5</center>
                    </th>
                    <th width="3%">
                        <center>6</center>
                    </th>
                    <th width="3%">
                        <center>7</center>
                    </th>
                    <th width="3%">
                        <center>8</center>
                    </th>
                    <th width="3%">
                        <center>9</center>
                    </th>
                    <th width="3%">
                        <center>10</center>
                    </th>
                    <th width="3%">
                        <center>11</center>
                    </th>
                    <th width="3%">
                        <center>12</center>
                    </th>
                    <th width="3%">
                        <center>13</center>
                    </th>
                    <th width="3%">
                        <center>14</center>
                    </th>
                    <th width="3%">
                        <center>15</center>
                    </th>
                    <th width="3%">
                        <center>16</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @foreach ($abs as $itembs)
                    <tr>
                        <td>
                            <center>{{ $no++ }}</center>
                        </td>
                        <td>
                            <center>{{ $itembs->nim }}</center>
                        </td>
                        <td>{{ $itembs->nama }}</td>
                        <td>
                            <center>
                                @if ($itembs->absn1 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn1 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn1 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn1 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn1 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn2 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn2 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn2 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn2 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn2 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn3 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn3 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn3 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn3 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn3 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn4 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn4 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn4 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn4 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn4 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn5 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn5 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn5 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn5 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn5 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn6 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn6 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn6 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn6 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn6 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn7 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn7 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn7 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn7 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn7 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn8 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn8 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn8 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn8 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn8 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn9 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn9 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn9 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn9 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn9 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn10 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn10 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn10 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn10 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn10 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn11 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn11 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn11 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn11 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn11 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn12 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn12 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn12 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn12 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn12 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn13 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn13 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn13 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn13 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn13 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn14 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn14 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn14 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn14 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn14 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn15 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn15 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn15 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn15 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn15 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if ($itembs->absn16 == 'ABSEN')
                                    (<span class="check-mark">&#10003;</span>)
                                @elseif ($itembs->absn16 == 'HADIR')
                                    (X)
                                @elseif($itembs->absn16 == 'SAKIT')
                                    (S)
                                @elseif($itembs->absn16 == 'ALFA')
                                    (A)
                                @elseif($itembs->absn16 == 'IZIN')
                                    (I)
                                @endif
                            </center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" align="right">Paraf Dosen</td>
                    <td>

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <br><br>
        <?php
            $bap_data = \Illuminate\Support\Facades\DB::table('bap')
                ->leftJoin('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->where('bap.id_kurperiode', $bap->id_kurperiode)
                ->where('bap.status', 'ACTIVE')
                ->select('bap.*', 'kuliah_tipe.tipe_kuliah')
                ->get();

            $uasItem = $bap_data->first(function ($item) {
                $materi = isset($item->materi_kuliah) ? strtoupper(trim($item->materi_kuliah)) : '';
                $jenis = isset($item->jenis_kuliah) ? strtoupper(trim($item->jenis_kuliah)) : '';
                $tipe = isset($item->tipe_kuliah) ? strtoupper(trim($item->tipe_kuliah)) : '';
                $pertemuan = isset($item->pertemuan) ? (int)$item->pertemuan : 0;

                return $materi === 'UAS' || strpos($materi, 'UAS') !== false || $jenis === 'UAS' || $tipe === 'UAS' || $pertemuan === 16;
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
                    <td width="70%" align=center></td>
                    <td width="30%">Cikarang, {{ $tgl_cikarang }}</td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="70%" align=center></td>
                    <td width="30%" align=center></td>
                </tr>
            </table>
            <br><br><br><br><br>
            <table width="100%">
                <tr>
                    <td width="70%" align=center></td>
                    <td width="30%">{{ $bap->nama }}, {{ $bap->akademik }}</td>
                </tr>
            </table>
        </div>
</body>

</html>
