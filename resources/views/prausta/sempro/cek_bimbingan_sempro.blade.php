@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Bimbingan Sempro Mahasiswa</h3>
                <br><br>
                <table width="100%">
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mhs->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <form action="{{ url('download_bimbingan_sempro') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_settingrelasi_prausta" value="{{ $mhs->id_settingrelasi_prausta }}">
                    <button class="btn btn-danger">Download PDF</button>
                </form>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Bimbingan</th>
                            <th>Uraian Bimbingan</th>
                            <th>Validasi</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->remark_bimbingan }}</td>
                                <td>
                                    @if ($key->validasi == 'BELUM')
                                        <span class="badge bg-warning">Belum</span>
                                    @elseif ($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
                                    @endif

                                </td>
                                <td>
                                    @if ($key->file_bimbingan == null)
                                    @elseif ($key->file_bimbingan != null)
                                        <a href="/File Bimbingan SEMPRO/{{ $key->idstudent }}/{{ $key->file_bimbingan }}"
                                            target="_blank"> File bimbingan</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Draft & Laporan Akhir Sempro Mahasiswa</h3>
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
                            @if ($mhs->file_draft_laporan == null)
                                Belum ada
                            @elseif ($mhs->file_draft_laporan != null)
                                <a href="/File Draft Laporan/{{ $mhs->idstudent }}/{{ $mhs->file_draft_laporan }}"
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
                            @if ($mhs->file_laporan_revisi == null)
                                Belum ada
                            @elseif ($mhs->file_laporan_revisi != null)
                                <a href="/File Laporan Revisi/{{ $mhs->idstudent }}/{{ $mhs->file_laporan_revisi }}"
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
