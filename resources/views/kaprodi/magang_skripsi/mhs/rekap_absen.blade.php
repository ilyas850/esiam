@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $data->makul }} - {{ $data->akt_sks }} SKS</td>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td>{{ $data->periode_tahun }} {{ $data->periode_tipe }}</td>
                    </tr>
                    <tr>
                        <td>Waktu / Ruangan</td>
                        <td>:</td>
                        <td>{{ $data->hari }},
                            @if ($data->id_kelas == 1)
                                {{ $data->jam }} -
                                {{ date('H:i', strtotime($data->jam) + 60 * $data->akt_sks_teori * 50 + 60 * $data->akt_sks_praktek * 120) }}
                            @elseif ($data->id_kelas == 2)
                                {{ $data->jam }} -
                                {{ date('H:i', strtotime($data->jam) + 60 * $data->akt_sks_teori * 45 + 60 * $data->akt_sks_praktek * 90) }}
                            @elseif ($data->id_kelas == 3)
                                {{ $data->jam }} -
                                {{ date('H:i', strtotime($data->jam) + 60 * $data->akt_sks_teori * 45 + 60 * $data->akt_sks_praktek * 90) }}
                            @endif
                            / {{ $data->nama_ruangan }}
                        </td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $data->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Dosen</td>
                        <td>:</td>
                        <td>{{ $data->nama }}, {{ $data->akademik }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $data->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>Pertemuan</center>
                            </th>
                            <th rowspan="2">
                                <center>Tanggal</center>
                            </th>
                            <th rowspan="2">
                                <center>Jam</center>
                            </th>
                            <th colspan="2">
                                <center>Absensi</center>
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <center>Hadir</center>
                            </th>
                            <th>
                                <center>Tidak</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($abs as $item)
                            <tr>
                                <td>
                                    <center>Pertemuan Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tanggal }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jam_mulai }} - {{ $item->jam_selsai }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->absensi == 'ABSEN')
                                            (&#10003;)
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->absensi == 'HADIR')
                                            (X)
                                        @elseif($item->absensi == 'SAKIT')
                                            (S)
                                        @elseif($item->absensi == 'ALFA')
                                            (A)
                                        @elseif($item->absensi == 'IZIN')
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
