{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <title>Politeknik META Industri Cikarang</title>
</head> --}}

<style media="screen">
	table {
		border-collapse: collapse;
	}
	tr.b{
		line-height:80px;
	}

</style>

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
                    <center>NIM </center>
                </th>
                <th>
                    <center>Nama</center>
                </th>
                <th>
                    <center>1</center>
                </th>
                <th>
                    <center>2</center>
                </th>
                <th>
                    <center>3</center>
                </th>
                <th>
                    <center>4</center>
                </th>
                <th>
                    <center>5</center>
                </th>
                <th>
                    <center>6</center>
                </th>
                <th>
                    <center>7</center>
                </th>
                <th>
                    <center>8</center>
                </th>
                <th>
                    <center>9</center>
                </th>
                <th>
                    <center>10</center>
                </th>
                <th>
                    <center>11</center>
                </th>
                <th>
                    <center>12</center>
                </th>
                <th>
                    <center>13</center>
                </th>
                <th>
                    <center>14</center>
                </th>
                <th>
                    <center>15</center>
                </th>
                <th>
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
                            @foreach ($abs1 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs2 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs3 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs4 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs5 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs6 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs7 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs8 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs9 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs10 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs11 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs12 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs13 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs14 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs15 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
                        </center>
                    </td>
                    <td>
                        <center>
                            @foreach ($abs16 as $item1)
                                @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                    @if ($item1->absensi == 'ABSEN')
                                        (&#10003;)
                                    @elseif ($item1->absensi == 'HADIR')
                                        (X)
                                    @elseif($item1->absensi == 'SAKIT')
                                        (S)
                                    @elseif($item1->absensi == 'ALFA')
                                        (A)
                                    @elseif($item1->absensi == 'IZIN')
                                        (I)
                                    @endif
                                @endif
                            @endforeach
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
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%"><span style="font-size:85%">Cikarang, </span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%" align=center><span style="font-size:85%"></span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%"><span style="font-size:85%">{{ $bap->nama }}, {{ $bap->akademik }}</td>
        </tr>
    </table>
    <script>
        window.print();
    </script>
</body>

{{-- </html> --}}
