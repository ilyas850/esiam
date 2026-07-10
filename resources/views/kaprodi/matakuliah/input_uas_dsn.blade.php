@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Input Nilai UAS
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_kprd') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/cekmhs_dsn_kprd/{{ $id }}"> Data List Mahasiswa</a></li>
            <li class="active">Data List Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <form action="{{ url('save_nilai_UAS_kprd') }}" method="post">
                {{ csrf_field() }}

                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="4%">
                                    <center>No</center>
                                </th>
                                <th width="8%">
                                    <center>NIM </center>
                                </th>
                                <th width="20%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="8%">
                                    <center>Kelas</center>
                                </th>
                                <th width="8%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai UAS</center>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ck as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td> {{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }} </center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan }} </center>
                                    </td>
                                    <td>
                                            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="text" name="nilai_UAS[]"
                                                    value="{{ $item->nilai_UAS != 0 ? $item->nilai_UAS : '' }}"
                                                    style="width: 60px; text-align: center;">
                                                @if ($item->absen_uas == null)
                                                    <span class="text-warning" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">⚠️ Belum Absen</span>
                                                @elseif ($item->ket_absensi == 'TIDAK MEMENUHI')
                                                    @if ($item->permohonan == 'MENGAJUKAN')
                                                        <span class="text-info" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">ℹ️ Belum di-Acc</span>
                                                    @elseif ($item->permohonan == 'TIDAK DISETUJUI')
                                                        <span class="text-danger" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">❌ Pengajuan Ditolak</span>
                                                    @elseif ($item->permohonan == null)
                                                        <span class="text-danger" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">❌ Absen Tidak Memenuhi</span>
                                                    @endif
                                                @endif
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="id_makul" value="{{ $mkl }}">
                    <input type="hidden" name="id_prodi" value="{{ $kprd }}">
                    <input type="hidden" name="id_kelas" value="{{ $kkls }}">
                    <input type="hidden" name="id_kurperiode" value="{{ $kuri }}">
                    <input class="btn btn-info" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
@endsection
