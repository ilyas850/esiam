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
                                <center>
                                    Waktu UTS
                                </center>
                            </th>
                            <th>
                                <center>Absen UTS </center>
                            </th>
                            <th>
                                <center>Waktu UAS </center>
                            </th>
                            <th>
                                <center>Absen UAS </center>
                            </th>
                            <th>
                                <center>Keterangan Absensi </center>
                            </th>
                            <th>
                                <center>Keterangan </center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data_ujian as $item)
                            <tr>
                                <td>{{ $item->makul }}</td>
                                <td align="center">
                                    {{ Carbon\Carbon::parse($item->tgl_uts)->formatLocalized('%A, %d %B %Y') }}
                                </td>
                                <td align="center">
                                    @if ($item->absen_uts == null)
                                        <a href="/absen_ujian_uts/{{ $item->id_studentrecord }}"
                                            class="btn btn-success btn-xs">Absen</a>
                                    @else
                                        {{ $item->absen_uts }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->tgl_uas != null)
                                        {{ Carbon\Carbon::parse($item->tgl_uas)->formatLocalized('%A, %d %B %Y') }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->tgl_uas != null)
                                        @if ($item->absen_uas == null)
                                            @if ($item->id_kelas == 1)
                                                @if ($item->jml_tdk_hdr <= 3)
                                                    <a href="/absen_ujian_uas_memenuhi/{{ $item->id_studentrecord }}"
                                                        class="btn btn-success btn-xs">Absen</a>
                                                @elseif ($item->jml_tdk_hdr > 3)
                                                    <a href="/absen_ujian_uas_tdk_memenuhi/{{ $item->id_studentrecord }}"
                                                        class="btn btn-success btn-xs">Absen</a>
                                                @endif
                                            @elseif($item->id_kelas == 2 or $item->id_kelas == 3 or $item->id_kelas == 4)
                                                @if ($item->jml_tdk_hdr <= 4)
                                                    <a href="/absen_ujian_uas_memenuhi/{{ $item->id_studentrecord }}"
                                                        class="btn btn-success btn-xs">Absen</a>
                                                @elseif ($item->jml_tdk_hdr > 4)
                                                    <a href="/absen_ujian_uas_tdk_memenuhi/{{ $item->id_studentrecord }}"
                                                        class="btn btn-success btn-xs">Absen</a>
                                                @endif
                                            @endif
                                        @else
                                            {{ $item->absen_uas }}
                                        @endif
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->tgl_uas != null)
                                        @if ($item->id_kelas == 1)
                                            @if ($item->jml_tdk_hdr <= 3)
                                                <span class="badge bg-green"><i class="fa fa-check"></i></span>
                                            @elseif ($item->jml_tdk_hdr > 3)
                                                <span class="badge bg-red"><i class="fa fa-close"></i></span>
                                            @endif
                                        @elseif($item->id_kelas == 2 or $item->id_kelas == 3 or $item->id_kelas == 4)
                                            @if ($item->jml_tdk_hdr <= 4)
                                                <span class="badge bg-green"><i class="fa fa-check"></i></span>
                                            @elseif ($item->jml_tdk_hdr > 4)
                                                <span class="badge bg-red"><i class="fa fa-close"></i></span>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->tgl_uas != null)
                                        @if ($item->id_kelas == 1)
                                            @if ($item->jml_tdk_hdr > 3)
                                                @if ($item->permohonan == null)
                                                    <a href="/ajukan_keringanan_absen/{{ $item->id_studentrecord }}"
                                                        class="btn btn-primary btn-xs">Ajukan</a>
                                                @elseif ($item->permohonan == 'MENGAJUKAN')
                                                    <span class="badge bg-yellow"></i>MENGAJUAKN</span>
                                                @elseif ($item->permohonan == 'DISETUJUI')
                                                    <span class="badge bg-green"></i>DISETUJUI</span>
                                                @elseif ($item->permohonan == 'TIDAK DISETUJUI')
                                                    <span class="badge bg-red"></i>TIDAK DISETUJUI</span>
                                                @endif
                                            @endif
                                        @elseif($item->id_kelas == 2 or $item->id_kelas == 3 or $item->id_kelas == 4)
                                            @if ($item->jml_tdk_hdr > 4)
                                                @if ($item->permohonan == null)
                                                    <a href="/ajukan_keringanan_absen/{{ $item->id_studentrecord }}"
                                                        class="btn btn-primary btn-xs">Ajukan</a>
                                                @elseif ($item->permohonan == 'MENGAJUKAN')
                                                    <span class="badge bg-yellow"></i>MENGAJUAKN</span>
                                                @elseif ($item->permohonan == 'DISETUJUI')
                                                    <span class="badge bg-green"></i>DISETUJUI</span>
                                                @elseif ($item->permohonan == 'TIDAK DISETUJUI')
                                                    <span class="badge bg-red"></i>TIDAK DISETUJUI</span>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
