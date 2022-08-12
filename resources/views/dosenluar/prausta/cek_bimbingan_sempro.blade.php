@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Cek Mahasiswa Bimbingan Seminar Proposal
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('pembimbing_sempro_dsnlr') }}">Data Mahasiswa SEMPRO</a></li>
            <li class="active">Cek Mahasiswa Bimbingan SEMPRO</li>
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
                    <form class="" action="{{ url('status_judul_dsnlr') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_settingrelasi_prausta" value="{{ $jdl->id_settingrelasi_prausta }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Judul Seminar Proposal</label>
                                <textarea class="form-control" rows="1" cols="60" readonly>{{ $jdl->judul_prausta }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Tempat</label>
                                <input type="text" class="form-control" value="{{ $jdl->tempat_prausta }}" readonly>
                            </div>
                            {{-- <div class="form-group">
                                @if ($jdl->acc_judul == 'BELUM')
                                    <button type="submit" class="btn btn-info" name="acc_judul"
                                        value="SUDAH">Terima/Acc</button>
                                @elseif ($jdl->acc_judul == 'REVISI')
                                    <button type="submit" class="btn btn-info" name="acc_judul"
                                        value="SUDAH">Terima/Acc</button>
                                    <button type="submit" class="btn btn-warning" name="acc_judul" value="REVISI">Revisi
                                        Lagi</button>
                                @elseif ($jdl->acc_judul == 'SUDAH')
                                    <span class="badge bg-blue">Judul telah di Acc.</span>
                                @endif
                            </div> --}}
                        </div>
                    </form>
                </div>
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
                            <th>Uraian Bimbingan</th>
                            <th>Komentar</th>
                            <th>Validasi</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($pkl as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->remark_bimbingan }}</td>
                                <td>
                                    @if ($key->komentar_bimbingan == null)
                                        <button class="btn btn-info btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentar{{ $key->id_transbimb_prausta }}">Tambah</button>
                                    @else
                                        <a class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentar{{ $key->id_transbimb_prausta }}"> <i
                                                class="fa fa-eye "></i> Lihat</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($key->validasi == 'BELUM')
                                        <a href="/val_bim_pkl_dsnlr/{{ $key->id_transbimb_prausta }}"
                                            class="btn btn-info btn-xs">Validasi</a>
                                    @elseif ($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
                                    @endif

                                </td>
                                <td>
                                    @if ($key->file_bimbingan == null)
                                    @elseif ($key->file_bimbingan != null)
                                        <a href="/File Bimbingan SEMPRO/{{ $key->id_student }}/{{ $key->file_bimbingan }}"
                                            target="_blank"> File bimbingan</a>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar{{ $key->id_transbimb_prausta }}"
                                tabindex="-1" aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar Bimbingan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/komentar_bimbingan_dsnlr/{{ $key->id_transbimb_prausta }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <textarea class="form-control" name="komentar_bimbingan" cols="20"
                                                        rows="10"> {{ $key->komentar_bimbingan }} </textarea>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                Pengajuan Seminar Proposal
            </div>
            <div class="box-body">
                <div class="form">
                    @if ($jdl->acc_seminar_sidang == null)
                        <span class="badge bg-red">Belum ada pengajuan</span>
                    @elseif ($jdl->acc_seminar_sidang == 'PENGAJUAN')
                        <a href="/acc_seminar_pkl_dsnlr/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-info">Acc.
                            Seminar Proposal</a>
                        <a href="/tolak_seminar_pkl_dsnlr/{{ $jdl->id_settingrelasi_prausta }}"
                            class="btn btn-danger">Tolak
                            Seminar Proposal</a>
                    @elseif ($jdl->acc_seminar_sidang == 'TERIMA')
                        <span class="badge bg-blue">Sudah di Acc.</span>
                    @elseif ($jdl->acc_seminar_sidang == 'TOLAK')
                        <span class="badge bg-red">Pengajuan ditolak</span>
                        {{-- <a href="/acc_seminar_pkl_dsnlr/{{ $jdl->id_settingrelasi_prausta }}" class="btn btn-info">Acc.
                            Seminar Prakerin</a>
                        <a href="/tolak_seminar_pkl_dsnlr/{{ $jdl->id_settingrelasi_prausta }}"
                            class="btn btn-danger">Tolak
                            Seminar Prakerin</a> --}}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-fw fa-file-pdf-o"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Draft Laporan Sempro</span>
                        <span class="info-box-number">
                            @if ($jdl->file_draft_laporan == null)
                                Belum ada
                            @elseif ($jdl->file_draft_laporan != null)
                                <a href="/File Draft Laporan/{{ $jdl->idstudent }}/{{ $jdl->file_draft_laporan }}"
                                    target="_blank" style="font: white"> File Draft Laporan</a>
                            @endif

                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-fw fa-file-pdf-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Laporan Akhir Sempro</span>
                        <span class="info-box-number">
                            @if ($jdl->file_laporan_revisi == null)
                                Belum ada
                            @elseif ($jdl->file_laporan_revisi != null)
                                <a href="/File Laporan Revisi/{{ $jdl->idstudent }}/{{ $jdl->file_laporan_revisi }}"
                                    target="_blank" style="font: white"> File Laporan Akhir</a>
                            @endif
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
