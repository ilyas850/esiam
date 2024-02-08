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
                        <li class="active"><a href="#tab_1" data-toggle="tab">Data Magang 2</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Data Bimbingan Magang 2</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Pengajuan Seminar Magang 2</a></li>
                        <li><a href="#tab_4" data-toggle="tab">Upload Draft Laporan Magang 2</a></li>
                        <li><a href="#tab_5" data-toggle="tab">Honor Magang 2</a></li>
                        <li><a href="#tab_6" data-toggle="tab">Nilai Magang 2</a></li>
                    </ul>
                    <div class="tab-content">
                        @if ($data->judul_prausta == null)
                            <a class="btn btn-danger"
                                href="input_data_magang2/{{ $data->id_settingrelasi_prausta }}">Masukan
                                Data
                                Magang 2</a>
                        @elseif ($data != null)
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" value="{{ $data->nama }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NIM</label>
                                            <input type="text" class="form-control" value="{{ $data->nim }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Program Studi</label>
                                            <input type="text" class="form-control" value="{{ $data->prodi }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jenis PraUSTA</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->kode_prausta }} - {{ $data->nama_prausta }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kategori PraUSTA</label>
                                            <input type="text" class="form-control" value="{{ $data->kategori }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Mulai Magang</label>
                                            <input type="text" class="form-control" value="{{ $data->tanggal_mulai }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Judul Magang</label>
                                            <textarea class="form-control" rows="4" cols="60" name="judul_prausta" readonly>{{ $data->judul_prausta }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tempat Magang 1</label>
                                            <input type="text" class="form-control" value="{{ $data->tempat_prausta }}"
                                                name="tempat_prausta" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tempat Magang 2</label>
                                            <input type="text" class="form-control" value="{{ $data->tempat_prausta2 }}"
                                                name="tempat_prausta2" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Validasi Keuangan</label>
                                            <input type="text" class="form-control" value="{{ $validasi }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dosen Pembimbing</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->dosen_pembimbing }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dosen Penguji</label>
                                            <input type="text" class="form-control" value="{{ $data->dosen_penguji_1 }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Seminar Magang</label>
                                            <input type="text" class="form-control" value="{{ $data->tanggal_selesai }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ruangan Seminar Magang</label>
                                            <input type="text" class="form-control" value="{{ $data->ruangan }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jam Mulai Seminar Magang</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->jam_mulai_sidang }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jam Selesai Seminar Magang</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->jam_selesai_sidang }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-success" data-toggle="modal"
                                            data-target="#modalUpdatePrakerin{{ $data->id_settingrelasi_prausta }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i> Edit Data</button>
                                    </div>
                                </div>
                                <div class="modal fade" id="modalUpdatePrakerin{{ $data->id_settingrelasi_prausta }}"
                                    tabindex="-1" aria-labelledby="modalUpdatePrakerin" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Data Magang</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/put_data_magang/{{ $data->id_settingrelasi_prausta }}"
                                                    method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <label>Judul Magang</label>
                                                        <textarea class="form-control" name="judul_prausta" rows="3" cols="60" required> {{ $data->judul_prausta }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tempat Magang 1</label>
                                                        <input type="text" class="form-control" name="tempat_prausta"
                                                            value="{{ $data->tempat_prausta }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tempat Magang 2</label>
                                                        <input type="text" class="form-control" name="tempat_prausta2"
                                                            value="{{ $data->tempat_prausta2 }}">
                                                    </div>
                                                    <input type="hidden" name="updated_by"
                                                        value="{{ Auth::user()->name }}">
                                                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                @if ($data->judul_prausta == null)
                                    <span class="badge bg-red">Data Magang belum ada</span>
                                @else
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            Validasi Upload File Error<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            Form Bimbingan Magang
                                        </div>
                                        <div class="box-body">
                                            <form class="" action="{{ url('simpan_bimbingan_magang') }}"
                                                method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Tanggal Bimbingan</label>
                                                            <input type="date" class="form-control"
                                                                name="tanggal_bimbingan" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Uraian Bimbingan</label>
                                                            <textarea class="form-control" name="remark_bimbingan" cols="30" rows="2" required></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>File Bimbingan</label>
                                                            <input type="file" name="file_bimbingan"
                                                                class="form-control" required>
                                                            <span>max. size file 4mb format .pdf, .doc, .docx</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($databimb == null)
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                                value="{{ $data->id_settingrelasi_prausta }}">
                                                            <button type="submit" class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </div>
                                                @elseif($databimb != null)
                                                    @if ($databimb->validasi == 'BELUM')
                                                        <span class="badge bg-yellow">Bimbingan sebelumnya belum
                                                            divalidasi</span>
                                                    @elseif ($databimb->validasi == 'SUDAH')
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <input type="hidden" name="id_settingrelasi_prausta"
                                                                    value="{{ $data->id_settingrelasi_prausta }}">
                                                                <button type="submit"
                                                                    class="btn btn-info">Simpan</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                    <div class="box box-info">
                                        <div class="box-header with-border">
                                            Tabel Bimbingan Magang
                                        </div>
                                        <div class="box-body">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <a href="/download_bimbingan_magang_mhs/{{ $data->id_settingrelasi_prausta }}"
                                                    class="btn btn-danger">Download PDF</a>
                                                <br><br>
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <center>No</center>
                                                        </th>
                                                        <th>
                                                            <center>Tanggal Bimbingan</center>
                                                        </th>
                                                        <th>
                                                            <center>Uraian Bimbingan</center>
                                                        </th>
                                                        <th>
                                                            <center>Komentar Bimbingan</center>
                                                        </th>
                                                        <th>
                                                            <center>Validasi</center>
                                                        </th>
                                                        <th>
                                                            <center>File</center>
                                                        </th>
                                                        <th>
                                                            <center>Aksi</center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    @foreach ($bim as $key)
                                                        <tr>
                                                            <td align="center">{{ $no++ }}</td>
                                                            <td align="center">{{ $key->tanggal_bimbingan }}</td>
                                                            <td>{{ $key->remark_bimbingan }}</td>
                                                            <td>
                                                                <center>
                                                                    @if ($key->komentar_bimbingan == null)
                                                                        <span class="badge bg-yellow">BELUM</span>
                                                                    @else
                                                                        <a class="btn btn-success btn-xs"
                                                                            data-toggle="modal"
                                                                            data-target="#modalTambahKomentar{{ $key->id_transbimb_prausta }}">
                                                                            <i class="fa fa-eye "></i> Lihat</a>
                                                                    @endif
                                                                </center>
                                                            </td>
                                                            <td align="center">
                                                                @if ($key->validasi == 'BELUM')
                                                                    <span class="badge bg-yellow">BELUM</span>
                                                                @elseif($key->validasi == 'SUDAH')
                                                                    <span class="badge bg-blue">Sudah</span>
                                                                @endif
                                                            </td>
                                                            <td align="center">
                                                                @if ($key->file_bimbingan == null)
                                                                @elseif ($key->file_bimbingan != null)
                                                                    <a href="/File Bimbingan Magang/{{ Auth::user()->id_user }}/{{ $key->file_bimbingan }}"
                                                                        target="_blank"> File bimbingan</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <button class="btn btn-primary btn-xs"
                                                                        data-toggle="modal"
                                                                        data-target="#modalUpdatebimbingan{{ $key->id_transbimb_prausta }}">Update</button>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade"
                                                            id="modalTambahKomentar{{ $key->id_transbimb_prausta }}"
                                                            tabindex="-1" aria-labelledby="modalTambahKomentar"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Komentar Bimbingan</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <textarea class="form-control" cols="20" rows="10" readonly> {{ $key->komentar_bimbingan }} </textarea>
                                                                        </div>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Tutup</button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade"
                                                            id="modalUpdatebimbingan{{ $key->id_transbimb_prausta }}"
                                                            tabindex="-1" aria-labelledby="modalUpdatebimbingan"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Update Bimbingan</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form
                                                                            action="/edit_bimbingan_magang/{{ $key->id_transbimb_prausta }}"
                                                                            method="post" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('put')
                                                                            <div class="form-group">
                                                                                <label>Tanggal Bimbingan</label>
                                                                                <input type="date" class="form-control"
                                                                                    name="tanggal_bimbingan"
                                                                                    value="{{ $key->tanggal_bimbingan }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Isi Bimbingan</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="remark_bimbingan"
                                                                                    value="{{ $key->remark_bimbingan }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>File Bimbingan</label>
                                                                                <input type="file"
                                                                                    name="file_bimbingan"
                                                                                    class="form-control"><a
                                                                                    href="/File Bimbingan Magang/{{ Auth::user()->id_user }}/{{ $key->file_bimbingan }}"
                                                                                    target="_blank">
                                                                                    {{ $key->file_bimbingan }}</a>
                                                                            </div>
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
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
                                @if ($angkatan == 18 or $angkatan == 19)
                                    @if ($hasil_spp >= 0)
                                        @if ($data->acc_seminar_sidang == null)
                                            <form class="" action="{{ url('ajukan_seminar_magang2') }}"
                                                method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>File Draft Laporan Seminar Magang</label>
                                                            <input type="file" name="file_draft_laporan"
                                                                class="form-control" required>
                                                            <span>max. size file 4mb format .pdf, .doc</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="id_settingrelasi_prausta"
                                                            value="{{ $data->id_settingrelasi_prausta }}">
                                                        <button type="submit" class="btn btn-info">Ajukan Seminar
                                                            Magang</button>
                                                    </div>
                                                </div>
                                            </form>
                                        @elseif ($data->acc_seminar_sidang == 'PENGAJUAN')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-red"><i
                                                                class="fa fa-files-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Draft Laporan Magang</span>
                                                            <span class="info-box-number">
                                                                @if ($data->file_draft_laporan == null)
                                                                    Belum ada
                                                                @elseif ($data->file_draft_laporan != null)
                                                                    <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                        target="_blank"> File Laporan</a>
                                                                @endif
                                                            </span>
                                                            <br>
                                                            <span class="badge bg-yellow">Menunggu Acc. Dosen
                                                                Pembimbing</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <form action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                                            enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                                value="{{ $data->id_settingrelasi_prausta }}">
                                                            <div class="form-group">
                                                                <input type="file" name="file_draft_laporan"
                                                                    class="form-control">
                                                                <span>Format file pdf max. size 5mb</span> <br>
                                                                <button type="submit"
                                                                    class="btn btn-info">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($data->acc_seminar_sidang == 'TERIMA')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-red"><i
                                                                class="fa fa-files-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Draft Laporan Magang</span>
                                                            <span class="info-box-number">
                                                                @if ($data->file_draft_laporan == null)
                                                                    Belum ada
                                                                @elseif ($data->file_draft_laporan != null)
                                                                    <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                        target="_blank"> File Laporan</a>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($data->validasi_baak == 'BELUM')
                                                    <div class="col-md-4">
                                                        <div class="info-box">
                                                            <form action="{{ url('ajukan_seminar_magang2') }}"
                                                                method="post" enctype="multipart/form-data">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="id_settingrelasi_prausta"
                                                                    value="{{ $data->id_settingrelasi_prausta }}">
                                                                <div class="form-group">
                                                                    <input type="file" name="file_draft_laporan"
                                                                        class="form-control">
                                                                    <span>Format file pdf max. size 5mb</span> <br>
                                                                    <button type="submit"
                                                                        class="btn btn-info">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-4">
                                                        <span class="badge bg-yellow">Sudah divalidasi BAAK</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="badge bg-green">Sudah di Acc. Dosen Pembimbing</span>
                                                </div>
                                            </div>
                                        @elseif ($data->acc_seminar_sidang == 'TOLAK')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-red"><i
                                                                class="fa fa-files-o"></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Draft Laporan Magang</span>
                                                            <span class="info-box-number">
                                                                @if ($data->file_draft_laporan == null)
                                                                    Belum ada
                                                                @elseif ($data->file_draft_laporan != null)
                                                                    <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                        target="_blank"> File Laporan</a>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <form action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                                            enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                                value="{{ $data->id_settingrelasi_prausta }}">
                                                            <div class="form-group">
                                                                <input type="file" name="file_draft_laporan"
                                                                    class="form-control">
                                                                <span>Format file pdf max. size 5mb</span> <br>
                                                                <button type="submit"
                                                                    class="btn btn-info">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span class="badge bg-red">Pengajuan di Tolak Dosen Pembimbing</span>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-red">Maaf syarat keuangan anda belum memenuhi untuk
                                            pengajuan seminar</span>
                                    @endif
                                @else
                                    {{-- @if ($jml_bim < 12) 
                                        <span class="badge bg-red">Maaf jumlah bimbingan anda kurang dari 12</span> --}}
                                    {{-- @elseif ($jml_bim >= 12)  --}}
                                    {{-- @if ($hasil_spp >= 0) --}}
                                    @if ($data->acc_seminar_sidang == null)
                                        <form class="" action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>File Draft Laporan Seminar</label>
                                                        <input type="file" name="file_draft_laporan"
                                                            class="form-control" required>
                                                        <span>max. size file 4mb format pdf</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <input type="hidden" name="id_settingrelasi_prausta"
                                                        value="{{ $data->id_settingrelasi_prausta }}">
                                                    <button type="submit" class="btn btn-info">Ajukan Seminar
                                                        Magang</button>
                                                </div>
                                            </div>
                                        </form>
                                    @elseif ($data->acc_seminar_sidang == 'PENGAJUAN')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red"><i
                                                            class="fa fa-files-o"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Draft Laporan Magang</span>
                                                        <span class="info-box-number">
                                                            @if ($data->file_draft_laporan == null)
                                                                Belum ada
                                                            @elseif ($data->file_draft_laporan != null)
                                                                <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                    target="_blank"> File Laporan</a>
                                                            @endif
                                                        </span>
                                                        <br>
                                                        <span class="badge bg-yellow">Menunggu Acc. Dosen
                                                            Pembimbing</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-box">
                                                    <form action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id_settingrelasi_prausta"
                                                            value="{{ $data->id_settingrelasi_prausta }}">
                                                        <div class="form-group">
                                                            <input type="file" name="file_draft_laporan"
                                                                class="form-control">
                                                            <span>Format file pdf max. size 5mb</span> <br>
                                                            <button type="submit" class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($data->acc_seminar_sidang == 'TERIMA')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red"><i
                                                            class="fa fa-files-o"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Draft Laporan Magang</span>
                                                        <span class="info-box-number">
                                                            @if ($data->file_draft_laporan == null)
                                                                Belum ada
                                                            @elseif ($data->file_draft_laporan != null)
                                                                <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                    target="_blank"> File Laporan</a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($data->validasi_baak == 'BELUM')
                                                <div class="col-md-4">
                                                    <div class="info-box">
                                                        <form action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                                            enctype="multipart/form-data">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                                value="{{ $data->id_settingrelasi_prausta }}">
                                                            <div class="form-group">
                                                                <input type="file" name="file_draft_laporan"
                                                                    class="form-control">
                                                                <span>Format file pdf max. size 5mb</span> <br>
                                                                <button type="submit"
                                                                    class="btn btn-info">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-4">
                                                    <span class="badge bg-yellow">Sudah divalidasi BAAK</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="badge bg-green">Sudah di Acc. Dosen Pembimbing</span>
                                            </div>
                                        </div>
                                    @elseif ($data->acc_seminar_sidang == 'TOLAK')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red"><i
                                                            class="fa fa-files-o"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Draft Laporan Magang</span>
                                                        <span class="info-box-number">
                                                            @if ($data->file_draft_laporan == null)
                                                                Belum ada
                                                            @elseif ($data->file_draft_laporan != null)
                                                                <a href="/File Draft Laporan 2/{{ Auth::user()->id_user }}/{{ $data->file_draft_laporan }}"
                                                                    target="_blank"> File Laporan</a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-box">
                                                    <form action="{{ url('ajukan_seminar_magang2') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id_settingrelasi_prausta"
                                                            value="{{ $data->id_settingrelasi_prausta }}">
                                                        <div class="form-group">
                                                            <input type="file" name="file_draft_laporan"
                                                                class="form-control">
                                                            <span>Format file pdf max. size 5mb</span> <br>
                                                            <button type="submit" class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="badge bg-red">Pengajuan di Tolak Dosen
                                                    Pembimbing</span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- @else
                                            <span class="badge bg-red">Maaf syarat keuangan anda belum memenuhi untuk
                                                pengajuan seminar</span>
                                        @endif --}}
                                    {{-- @endif --}}
                                @endif
                            </div>
                            <div class="tab-pane" id="tab_4">
                                @if ($data->acc_seminar_sidang == null)
                                    <span class="badge bg-red">Belum Mengajukan Seminar Magang</span>
                                @elseif ($data->acc_seminar_sidang == 'PENGAJUAN')
                                    <span class="badge bg-red">Menunggu Acc. Dosen Pembimbing</span>
                                @elseif ($data->acc_seminar_sidang == 'TERIMA')
                                    @if ($data->tanggal_selesai == null)
                                        <span class="badge bg-red">Menunggu Jadwal Seminar</span>
                                    @else
                                        @if ($cekdata_nilai == null)
                                            <span class="badge bg-red">Maaf nilai anda belum ada</span>
                                        @else
                                            @if ($data->file_laporan_revisi == null)
                                                <div class="info-box">
                                                    <form action="{{ url('simpan_draft_magang2') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id_settingrelasi_prausta"
                                                            value="{{ $data->id_settingrelasi_prausta }}">
                                                        <div class="form-group">

                                                            <input type="file" name="file_laporan_revisi"
                                                                class="form-control">
                                                            <span>Format file pdf max. size 5mb</span> <br>
                                                            <button type="submit" class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @elseif ($data->file_laporan_revisi != null)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-red"><i
                                                                    class="fa fa-files-o"></i></span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">Laporan Seminar
                                                                    Magang</span>
                                                                <span class="info-box-number">
                                                                    @if ($data->file_laporan_revisi == null)
                                                                        Belum ada
                                                                    @elseif ($data->file_laporan_revisi != null)
                                                                        <a href="/File Laporan Revisi 2/{{ Auth::user()->id_user }}/{{ $data->file_laporan_revisi }}"
                                                                            target="_blank"> File Laporan</a>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($data->validasi_baak == 'BELUM')
                                                        <div class="col-md-4">
                                                            <div class="info-box">
                                                                <form action="{{ url('simpan_draft_magang2') }}"
                                                                    method="post" enctype="multipart/form-data">
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="id_settingrelasi_prausta"
                                                                        value="{{ $data->id_settingrelasi_prausta }}">
                                                                    <div class="form-group">

                                                                        <input type="file" name="file_laporan_revisi"
                                                                            class="form-control">
                                                                        <span>Format file pdf max. size 5mb</span> <br>
                                                                        <button type="submit"
                                                                            class="btn btn-info">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-4">
                                                            <span class="badge bg-yellow">Sudah divalidasi BAAK</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @elseif ($data->acc_seminar_sidang == 'TOLAK')
                                    <span class="badge bg-yellow">Pengajuan di Tolak Dosen Pembimbing</span>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab_5">
                                <form class="" action="{{ url('simpan_honor_magang') }}" method="post"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Total Uang Saku Selama Magang</label>
                                                <input type="number" name="total_uang_saku" class="form-control"
                                                    value="{{ $data->total_uang_saku }}" required>
                                                <span>Isi dengan angka tanpa titik ( . ) <br> Contoh :
                                                    1000000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                value="{{ $data->id_settingrelasi_prausta }}">
                                            <button type="submit" class="btn btn-info">Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="tab_6">
                                @if ($data->validasi_baak == 'BELUM')
                                    <span class="badge bg-yellow">Nilai Belum Ada</span>
                                @else
                                    <div class="row">
                                        <div class="col-lg-3 col-xs-6">
                                            @if ($data->kodeprodi == 25)
                                                <div class="small-box bg-aqua">
                                                @elseif($data->kodeprodi == 23)
                                                    <div class="small-box bg-red">
                                                    @elseif($data->kodeprodi == 24)
                                                        <div class="small-box bg-green">
                                            @endif

                                            <div class="inner">
                                                <h3>
                                                    {{ $cekdata_nilai->nilai_huruf }}</h3>
                                                <p>Nilai Akhir Magang 2</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-list"></i>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
