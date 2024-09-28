@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Mahasiswa Tugas Akhir
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data mahasiswa Tugas Akhir</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="10%">
                                <center>NIM</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Nilai 1</center>
                            </th>
                            <th>
                                <center>Nilai 2</center>
                            </th>
                            <th>
                                <center>Nilai 3</center>
                            </th>
                            <th>
                                <center>Nilai Huruf</center>
                            </th>
                            <th>
                                <center>Draft</center>
                            </th>
                            <th>
                                <center>Laporan</center>
                            </th>
                            <th>
                                <center>Plagiarisme</center>
                            </th>
                            <th>
                                <center>Penilaian</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_1, 2) }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_2, 2) }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_3, 2) }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_huruf }}</center>
                                </td>
                                <td>
                                    @if ($key->file_draft_laporan == null)
                                        belum
                                    @else
                                        <a href="/File Draft Laporan/{{ $key->id_student }}/{{ $key->file_draft_laporan }}"
                                            target="_blank"> File</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($key->file_laporan_revisi == null)
                                        belum
                                    @else
                                        <a href="/File Laporan Revisi/{{ $key->id_student }}/{{ $key->file_laporan_revisi }}"
                                            target="_blank"> File</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($key->file_plagiarisme == null)
                                        belum
                                    @else
                                        <a href="/File Plagiarisme/{{ $key->id_student }}/{{ $key->file_plagiarisme }}"
                                            target="_blank"> File</a>
                                    @endif
                                </td>
                                <td>
                                    <center>

                                        @if ($key->acc_seminar_sidang == null)
                                            <span class="badge bg-grey">Belum ada pengajuan </span>
                                        @elseif($key->acc_seminar_sidang == 'PENGAJUAN')
                                            <span class="badge bg-yelloe">Belum di Acc. sidang/seminar</span>
                                        @elseif($key->acc_seminar_sidang == 'TOLAK')
                                            <span class="badge bg-danger">Pengajuan sidang/seminar ditolak</span>
                                        @elseif($key->acc_seminar_sidang == 'TERIMA')
                                            @if ($key->id_dosen_pembimbing == $id && $key->nilai_1 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_ta_dospem_dsnlr/{{ $key->id_settingrelasi_prausta }}">Isi
                                                    Form
                                                    Penilaian TA</a>
                                            @elseif($key->id_dosen_penguji_1 == $id && $key->nilai_2 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_ta_dosji1_dsnlr/{{ $key->id_settingrelasi_prausta }}">Isi
                                                    Form
                                                    Penilaian TA</a>
                                            @elseif($key->id_dosen_penguji_2 == $id && $key->nilai_3 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_ta_dosji2_dsnlr/{{ $key->id_settingrelasi_prausta }}">Isi
                                                    Form
                                                    Penilaian TA</a>
                                            @elseif ($key->id_dosen_pembimbing == $id && $key->nilai_1 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_ta_by_dospem_luar/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_1 == $id && $key->nilai_2 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_ta_by_dospeng1_luar/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_2 == $id && $key->nilai_3 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_ta_by_dospeng2_luar/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @endif
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
