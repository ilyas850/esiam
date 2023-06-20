@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Absensi Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('rekap_perkuliahan') }}"> Rekap perkuliahan</a></li>
            <li><a href="/cek_rekapan/{{ $bap->id_kurperiode }}">Cek BAP</a></li>
            <li class="active">Absensi Perkuliahan </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
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
            </div>
            <div class="box-body">
                <a href="/print_absensi_cek/{{ $bap->id_kurperiode }}" class="btn btn-success" target="_blank">Print</a>
                <br><br>
                <table class="table table-bordered">
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
                                        @if ($itembs->absn1 == 'ABSEN')
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
                                        @elseif ($itembs->absn4 == 'HADIR')
                                            (X)
                                        @elseif($itembs->absn4 == 'SAKIT')
                                            (S)
                                        @elseif($itembs->absn4 == 'ALFA')
                                            (A)
                                        @elseif($itembs->absn4 == 'IZIN')
                                            (I)
                                        @endif
                                </td>
                                <td>
                                    <center>
                                        @if ($itembs->absn5 == 'ABSEN')
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                                            (&#10003;)
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
                </table>
            </div>
        </div>
    </section>
@endsection
