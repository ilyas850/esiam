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
                <a href="/view_abs/{{ $data->id_kurperiode }}" class="btn btn-warning">Rekap Absensi</a>
                <br><br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Pertemuan</center>
                            </th>
                            <th>
                                <center>Tanggal</center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>
                                <center>Aktual Materi Pembelajaran</center>
                            </th>
                            <th>
                                <center>Aktual Materi Praktikum</center>
                            </th>
                            {{-- <th>
                                <center>Kesesuaian RPS</center>
                            </th> --}}
                            <th>
                                <center>Tipe Kuliah</center>
                            </th>
                            <th>
                                <center>Absensi <br> (Hadir/Tidak)</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bap as $item)
                            <tr>
                                <td>
                                    <center>Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td>
                                    <center>
                                        <center>{{ Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</center>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}-{{ Carbon\Carbon::parse($item->jam_selsai)->format('H:i') }}
                                    </center>
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td>{{ $item->praktikum }}</td>
                                {{-- <td align="center"><b>
                                        @if ($item->kesesuaian_rps == 'SESUAI')
                                            <span>&#10003;</span>
                                        @elseif($item->kesesuaian_rps == 'TIDAK SESUAI')
                                            <span>&#10007;</span>
                                        @endif
                                    </b>
                                </td> --}}
                                <td>
                                    <center>{{ $item->tipe_kuliah }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->hadir }} / {{ $item->tidak_hadir }}</center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/view_bap_mhs/{{ $item->id_bap }}" class="btn btn-info btn-xs"
                                            title="klik untuk lihat"> <i class="fa fa-eye"></i> Lihat</a>
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
