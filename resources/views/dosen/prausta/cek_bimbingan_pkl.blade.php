@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Cek Mahasiswa Bimbingan PKL
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('pembimbing_pkl') }}">Data Mahasiswa PKL</a></li>
            <li class="active">Cek Mahasiswa Bimbingan PKL</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $jdl->nim }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $jdl->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $jdl->nama }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $jdl->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="" action="{{ url('status_judul') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_settingrelasi_prausta" value="{{ $jdl->id_settingrelasi_prausta }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Judul Prakerin</label>
                                <textarea class="form-control" rows="1" cols="60"
                                    readonly>{{ $jdl->judul_prausta }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Tempat Prakerin</label>
                                <input type="text" class="form-control" value="{{ $jdl->tempat_prausta }}" readonly>
                            </div>
                            <div class="form-group">
                                @if ($jdl->acc_judul == 'BELUM')
                                    <button type="submit" class="btn btn-info" name="acc_judul"
                                        value="SUDAH">Terima/Acc</button>
                                    {{-- <button type="submit" class="btn btn-warning" name="acc_judul"
                                        value="REVISI">Revisi</button> --}}
                                @elseif ($jdl->acc_judul == 'REVISI')
                                    <button type="submit" class="btn btn-info" name="acc_judul"
                                        value="SUDAH">Terima/Acc</button>
                                    <button type="submit" class="btn btn-warning" name="acc_judul" value="REVISI">Revisi
                                        Lagi</button>
                                @elseif ($jdl->acc_judul == 'SUDAH')
                                    <span class="badge bg-blue">Judul telah di Acc.</span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    Tabel Bimbingan
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Bimbingan</th>
                                <th>Remark Bimbingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($pkl as $key)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $key->tanggal_bimbingan }}</td>
                                    <td>{{ $key->remark_bimbingan }}</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    Pengajuan Seminar Prakerin
                </div>
                <div class="box-body">
                    @if ($jdl->acc_seminar_sidang == null)
                        <span class="badge bg-red">Belum ada pengajuan</span>
                    @elseif ($jdl->acc_seminar_sidang == 'PENGAJUAN')
                        <a href="/acc_seminar_pkl/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-info">Acc.
                            Seminar Prakerin</a>
                        <a href="/tolak_seminar_pkl/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-danger">Tolak
                            Seminar Prakerin</a>
                    @elseif ($jdl->acc_seminar_sidang == 'TERIMA')
                        <span class="badge bg-blue">Sudah di Acc.</span>
                    @elseif ($jdl->acc_seminar_sidang == 'TOLAK')
                        <a href="/acc_seminar_pkl/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-info">Acc.
                            Seminar Prakerin</a>
                        <a href="/tolak_seminar_pkl/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-danger">Tolak
                            Seminar Prakerin</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
