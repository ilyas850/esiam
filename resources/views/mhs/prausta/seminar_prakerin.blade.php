@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Data Prakerin</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Data Bimbingan Prakerin</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Pengajuan Seminar Prakerin</a></li>
                        <li><a href="#tab_4" data-toggle="tab">Upload Draft Prakerin</a></li>
                    </ul>
                    <div class="tab-content">
                        @if ($usta->judul_prausta == null)
                            <a class="btn btn-danger" href="{{ url('pengajuan_seminar_prakerin') }}">Masukan Data
                                Prakerin</a>
                        @elseif ($cekdata != null)
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" value="{{ $usta->nama }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NIM</label>
                                            <input type="text" class="form-control" value="{{ $usta->nim }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Program Studi</label>
                                            <input type="text" class="form-control" value="{{ $usta->prodi }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jenis PraUSTA</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->kode_prausta }} - {{ $usta->nama_prausta }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kategori PraUSTA</label>
                                            <input type="text" class="form-control" value="{{ $usta->kategori }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Mulai Bimbingan</label>
                                            <input type="text" class="form-control" value="{{ $usta->tanggal_mulai }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul Prakerin</label>
                                            <textarea class="form-control" rows="2" cols="60" name="judul_prausta"
                                                readonly>{{ $usta->judul_prausta }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tempat Prakerin</label>
                                            <input type="text" class="form-control" value="{{ $usta->tempat_prausta }}"
                                                name="tempat_prausta" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Acc. Judul Prakerin</label>
                                            <input type="text" class="form-control" value="{{ $usta->acc_judul }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Validasi Keuangan</label>
                                            <input type="text" class="form-control" value="{{ $validasi }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dosen Pembimbing</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->dosen_pembimbing }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dosen Penguji</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->dosen_penguji1_1 }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Seminar Prakerin</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->tanggal_selesai }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ruangan Seminar Prakerin</label>
                                            <input type="text" class="form-control" value="{{ $usta->ruangan }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jam Mulai Seminar</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->jam_mulai_sidang }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jam Selesai Seminar</label>
                                            <input type="text" class="form-control"
                                                value="{{ $usta->jam_selesai_sidang }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                @if ($usta->acc_judul == 'BELUM')
                                    <span class="badge bg-red">Judul Belum di ACC</span>
                                @else
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            Form Bimbingan Prakerin
                                        </div>
                                        <div class="box-body">
                                            <form class="" action="{{ url('simpan_bimbingan') }}"
                                                method="post">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Tanggal Bimbingan</label>
                                                            <input type="date" class="form-control"
                                                                name="tanggal_bimbingan" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Isi Bimbingan</label>
                                                            <input type="text" class="form-control"
                                                                name="remark_bimbingan" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="id_settingrelasi_prausta"
                                                            value="{{ $usta->id_settingrelasi_prausta }}">
                                                        <button type="submit" class="btn btn-info">Simpan</button>
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
                                                        <th>
                                                            <center>Aksi</center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    @foreach ($bim as $key)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $key->tanggal_bimbingan }}</td>
                                                            <td>{{ $key->remark_bimbingan }}</td>
                                                            <td>
                                                                <center>
                                                                    <button class="btn btn-primary btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#modalUpdatebimbingan{{ $key->id_transbimb_prausta }}">Update</button>

                                                                </center>
                                                            </td>
                                                        </tr>

                                                        <div class="modal fade"
                                                            id="modalUpdatebimbingan{{ $key->id_transbimb_prausta }}"
                                                            tabindex="-1" aria-labelledby="modalUpdatebimbingan"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Update Bimbingan</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form
                                                                            action="/edit_bimbingan/{{ $key->id_transbimb_prausta }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('put')
                                                                            <div class="form-group">
                                                                                <label>Tingkat</label>
                                                                                <input type="date" class="form-control"
                                                                                    name="tanggal_bimbingan"
                                                                                    value="{{ $key->tanggal_bimbingan }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Isi Bimbingan</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="remark_bimbingan"
                                                                                    value="{{ $key->remark_bimbingan }}">
                                                                            </div>
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Perbarui
                                                                                Data</button>
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
                                @endif

                            </div>
                            <div class="tab-pane" id="tab_3">
                                @if ($jml_bim < 6)
                                    <span class="badge bg-red">Maaf jumlah bimbingan anda kurang dari 6</span>

                                @elseif ($jml_bim >= 6)
                                    @if ($usta->acc_seminar_sidang == null)
                                        <a href="/ajukan_seminar_pkl/{{ $usta->id_settingrelasi_prausta }}"
                                            class="btn btn-info">Ajukan Seminar Prakerin</a>
                                    @elseif ($usta->acc_seminar_sidang == 'PENGAJUAN')
                                        <span class="badge bg-red">Menunggu Acc. Dosen Pembimbing</span>
                                    @elseif ($usta->acc_seminar_sidang == 'TERIMA')
                                        <span class="badge bg-green">Sudah di Acc. Dosen Pembimbing</span>
                                    @elseif ($usta->acc_seminar_sidang == 'TOLAK')
                                        <span class="badge bg-yellow">Pengajuan di Tolak Dosen Pembimbing</span>
                                    @endif
                                @endif
                            </div>
                            <div class="tab-pane" id="tab_4">
                                @if ($usta->acc_seminar_sidang == null)
                                    <a href="/ajukan_seminar_pkl/{{ $usta->id_settingrelasi_prausta }}"
                                        class="btn btn-info">Belum Mengajukan Seminar Prakerin</a>
                                @elseif ($usta->acc_seminar_sidang == 'PENGAJUAN')
                                    <span class="badge bg-red">Menunggu Acc. Dosen Pembimbing</span>
                                @elseif ($usta->acc_seminar_sidang == 'TERIMA')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <form action="{{ url('simpan_draft_prakerin') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_settingrelasi_prausta"
                                                    value="{{ $usta->id_settingrelasi_prausta }}">
                                                <div class="form-group">
                                                    <label>Draft Laporan Prakerin</label><br>
                                                    @if ($usta->file_draft_laporan == null)
                                                        <input type="file" name="file_draft_laporan"
                                                            class="form-control">
                                                        <span>Format file pdf max. size 5mb</span>
                                                        <button type="submit" class="btn btn-info">Simpan</button>
                                                    @elseif ($usta->file_draft_laporan != NULL)
                                                        <a href="/File Draft Laporan/{{ Auth::user()->id_user }}/{{ $usta->file_draft_laporan }}"
                                                            target="_blank"> File Draf Laporan</a>
                                                    @endif
                                                </div>


                                            </form>
                                        </div>
                                    </div>
                                @elseif ($usta->acc_seminar_sidang == 'TOLAK')
                                    <span class="badge bg-yellow">Pengajuan di Tolak Dosen Pembimbing</span>
                                @endif

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
