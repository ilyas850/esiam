@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Mahasiswa Seminar Prakerin
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data mahasiswa Seminar Prakerin</li>
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
                                <center>Aksi</center>
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
                                    <center>
                                        @if ($key->acc_seminar_sidang == null)
                                            <span class="badge bg-grey">Belum ada pengajuan </span>
                                        @elseif($key->acc_seminar_sidang == 'PENGAJUAN')
                                            <span class="badge bg-yelloe">Belum di Acc. sidang/seminar</span>
                                        @elseif($key->acc_seminar_sidang == 'TOLAK')
                                            <span class="badge bg-danger">Pengajuan sidang/seminar ditolak</span>
                                        @elseif($key->acc_seminar_sidang == 'TERIMA')
                                            @if ($key->nilai_huruf != null)
                                                <span class="badge bg-green">Sudah ada nilai</span>
                                            @elseif($key->nilai_huruf == null)
                                                <a class="btn btn-success btn-xs"
                                                    href="/isi_form_nilai_pkl_kprd/{{ $key->id_settingrelasi_prausta }}">Isi
                                                    Form
                                                    Penilaian PKL</a>
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
