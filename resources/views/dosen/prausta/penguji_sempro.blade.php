@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Mahasiswa Seminar Proposal
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data mahasiswa Seminar Proposal</li>
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="3%">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="5%">
                                <center>NIM</center>
                            </th>
                            <th width="11%">
                                <center>Program Studi</center>
                            </th>
                            <th width="6%">
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
                                <center>Penilaian</center>
                            </th>
                            <th>
                                <center>Validasi</center>
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
                                    <center>{{ $key->nilai_1 }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_2 }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_3 }}</center>
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
                                    <center>
                                        @if ($key->acc_seminar_sidang == null)
                                            <span class="badge bg-grey">Belum </span>
                                        @elseif($key->acc_seminar_sidang == 'PENGAJUAN')
                                            <span class="badge bg-yelloe">Belum di Acc. sidang/seminar</span>
                                        @elseif($key->acc_seminar_sidang == 'TOLAK')
                                            <span class="badge bg-danger">Pengajuan sidang/seminar ditolak</span>
                                        @elseif($key->acc_seminar_sidang == 'TERIMA')
                                            @if ($key->id_dosen_pembimbing == $id && $key->nilai_1 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_proposal_dospem/{{ $key->id_settingrelasi_prausta }}">
                                                    Form SEMPRO</a>
                                            @elseif($key->id_dosen_penguji_1 == $id && $key->nilai_2 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_proposal_dosji1/{{ $key->id_settingrelasi_prausta }}">
                                                    Form SEMPRO</a>
                                            @elseif($key->id_dosen_penguji_2 == $id && $key->nilai_3 == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_proposal_dosji2/{{ $key->id_settingrelasi_prausta }}">
                                                    Form SEMPRO</a>
                                            @elseif ($key->id_dosen_pembimbing == $id && $key->nilai_1 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_sempro_by_dospem_dlm/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_1 == $id && $key->nilai_2 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_sempro_by_dospeng1_dlm/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_2 == $id && $key->nilai_3 != null)
                                                @if ($key->validasi == 0)
                                                    <a class="btn btn-success btn-xs"
                                                        href="/edit_nilai_sempro_by_dospeng2_dlm/{{ $key->id_settingrelasi_prausta }}">Edit
                                                        nilai</a>
                                                @elseif ($key->validasi == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi </span>
                                                @endif
                                            @endif
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->acc_seminar_sidang == null)
                                            <span class="badge bg-grey">Belum </span>
                                        @elseif($key->acc_seminar_sidang == 'PENGAJUAN')
                                            <span class="badge bg-yelloe">Belum di Acc. sidang/seminar</span>
                                        @elseif($key->acc_seminar_sidang == 'TOLAK')
                                            <span class="badge bg-danger">Pengajuan sidang/seminar ditolak</span>
                                        @elseif($key->acc_seminar_sidang == 'TERIMA')
                                            @if ($key->id_dosen_pembimbing == $id && $key->file_laporan_revisi != null)
                                                @if ($key->validasi_pembimbing == 'BELUM')
                                                    <a class="btn btn-success btn-xs"
                                                        href="/validasi_dospem/{{ $key->id_settingrelasi_prausta }}">
                                                        Validasi</a>
                                                @elseif($key->validasi_pembimbing == 'SUDAH')
                                                    <span class="badge bg-blue">Sudah</span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_1 == $id && $key->file_laporan_revisi != null)
                                                @if ($key->validasi_penguji_1 == 'BELUM')
                                                    <a class="btn btn-success btn-xs"
                                                        href="/validasi_dosji1/{{ $key->id_settingrelasi_prausta }}">
                                                        Validasi</a>
                                                @elseif($key->validasi_penguji_1 == 'SUDAH')
                                                    <span class="badge bg-blue">Sudah</span>
                                                @endif
                                            @elseif($key->id_dosen_penguji_2 == $id && $key->file_laporan_revisi != null)
                                                @if ($key->validasi_penguji_2 == 'BELUM')
                                                    <a class="btn btn-success btn-xs"
                                                        href="/validasi_dosji2/{{ $key->id_settingrelasi_prausta }}">
                                                        Validasi</a>
                                                @elseif($key->validasi_penguji_2 == 'SUDAH')
                                                    <span class="badge bg-blue">Sudah</span>
                                                @endif
                                            @else
                                                <span class="badge bg-blue">Belum</span>
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
