@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <b> ABSENSI UJIAN MAHASISWA</b>
                <table width="100%">
                    <tr>
                        <td width="15%">Nama</td>
                        <td>:</td>
                        <td>{{ $datamhs->nama }}</td>
                        <td width="15%">Tahun Ajaran</td>
                        <td>:</td>
                        <td>{{ $periode_tahun->periode_tahun }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $datamhs->nim }}</td>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $periode_tipe->periode_tipe }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi </td>
                        <td>:</td>
                        <td>{{ $datamhs->prodi }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $datamhs->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                Tanggal UTS
                            </th>
                            <th>Jam UTS</th>
                            <th>
                                Soal UTS
                            </th>
                            <th>
                                Tanggal UAS
                            </th>
                            <th>Jam UAS</th>
                            <th>
                                Soal UAS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        $daysInIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; ?>
                        @foreach ($soal_ujian as $item)
                            <?php
                            $currentDateTime = now(); // Waktu saat ini
                            $jadwalUts = $item->tgl_uts && $item->jam_uts ? Carbon\Carbon::parse($item->tgl_uts . ' ' . $item->jam_uts) : null;
                            $jadwalUas = $item->tgl_uas && $item->jam_uas ? Carbon\Carbon::parse($item->tgl_uas . ' ' . $item->jam_uas) : null;
                            ?>

                            @if (($jadwalUts && $currentDateTime >= $jadwalUts) || ($jadwalUas && $currentDateTime >= $jadwalUas))
                                <tr>
                                    <td>{{ $item->kode }} - {{ $item->makul }}</td>
                                    <td>
                                        {{ $item->tgl_uts ? $daysInIndonesian[Carbon\Carbon::parse($item->tgl_uts)->format('N')] : 'Tanggal Belum Diatur' }},
                                        {{ $item->tgl_uts ? Carbon\Carbon::parse($item->tgl_uts)->format('d-m-Y') : 'Tanggal Belum Diatur' }}
                                    </td>
                                    <td>{{ $item->jam_uts ?: 'Jam Belum Diatur' }}</td>
                                    <td><a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                            target="_blank" style="font: white"> Soal UTS</a>
                                    </td>
                                    <td>{{ $item->tgl_uas ? $daysInIndonesian[Carbon\Carbon::parse($item->tgl_uas)->format('N')] : 'Hari Belum Diatur' }},
                                        {{ $item->tgl_uas ? Carbon\Carbon::parse($item->tgl_uas)->format('d-m-Y') : 'Tanggal Belum Diatur' }}
                                    </td>
                                    <td>{{ $item->jam_uas ?: 'Jam Belum Diatur' }}</td>
                                    <td>
                                        @if ($item->soal_uas != null)
                                            <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                target="_blank" style="font: white"> Soal UAS</a>
                                        @else
                                            Soal Belum Ada
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            {{-- <tr>
                                <td>{{ $item->kode }} - {{ $item->makul }}</td>
                                <td>
                                    {{ $daysInIndonesian[Carbon\Carbon::parse($item->tgl_uts)->format('N')] }},
                                    {{ Carbon\Carbon::parse($item->tgl_uts)->format('d-m-Y') }}
                                </td>
                                <td>{{ $item->jam_uts }}</td>
                                <td><a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                        target="_blank" style="font: white"> Soal UTS</a>
                                </td>
                                <td>{{ $daysInIndonesian[Carbon\Carbon::parse($item->tgl_uas)->format('N')] }},
                                    {{ Carbon\Carbon::parse($item->tgl_uas)->format('d-m-Y') }}</td>
                                <td>{{ $item->jam_uas }}</td>
                                <td><a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                        target="_blank" style="font: white"> Soal UAS</a></td>
                            </tr> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
