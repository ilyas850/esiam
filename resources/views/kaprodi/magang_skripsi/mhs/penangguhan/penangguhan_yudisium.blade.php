@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Validasi Upload Error<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($data == null)
            <form action="{{ url('save_penangguhan_yudisium') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_student" value="{{ $ids }}">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Form Data Diri</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap"
                                placeholder="Masukan Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" class="form-control" name="tmpt_lahir" placeholder="Masukan Tempat Lahir"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" placeholder="Masukan Tanggal Lahir"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Induk Kependudukan</label>
                            <input type="number" class="form-control" name="nik" placeholder="Masukan NIK" required>
                        </div>
                        <div class="form-group">
                            <label>File Ijazah Terakhir</label>
                            <input type="file" class="form-control" name="file_ijazah" required>
                            <span>File size max. 4mb dan format file .jpg</span>
                        </div>
                        <div class="form-group">
                            <label>File KTP</label>
                            <input type="file" class="form-control" name="file_ktp" required>
                            <span>File size max. 4mb dan format file .jpg</span>
                        </div>
                        <div class="form-group">
                            <label>File Foto</label>
                            <input type="file" class="form-control" name="file_foto" required>
                            <span>File size max. 4mb dan format file .jpg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Data Diri (Yudisium)</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap"
                                    value="{{ $data->nama_lengkap }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" class="form-control" name="tmpt_lahir" value="{{ $data->tmpt_lahir }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" value="{{ $data->tgl_lahir }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nomor Induk Kependudukan</label>
                                <input type="number" class="form-control" name="nik" value="{{ $data->nik }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">File Ijazah</span>
                                    <span class="info-box-number">
                                        @if ($data->file_ijazah == null)
                                            Belum ada
                                        @elseif ($data->file_ijazah != null)
                                            <a href="/File Yudisium/{{ $data->id_student }}/{{ $data->file_ijazah }}"
                                                target="_blank"> File Ijazah</a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">File KTP</span>
                                    <span class="info-box-number">
                                        @if ($data->file_ktp == null)
                                            Belum ada
                                        @elseif ($data->file_ktp != null)
                                            <a href="/File Yudisium/{{ $data->id_student }}/{{ $data->file_ktp }}"
                                                target="_blank"> File KTP</a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">File Foto</span>
                                    <span class="info-box-number">
                                        @if ($data->file_foto == null)
                                            Belum ada
                                        @elseif ($data->file_foto != null)
                                            <a href="/File Yudisium/{{ $data->id_student }}/{{ $data->file_foto }}"
                                                target="_blank"> File Foto</a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if ($data->validasi == 'BELUM')
                                <button class="btn btn-success" data-toggle="modal"
                                    data-target="#modalUpdateYudisium{{ $data->id_yudisium }}" title="klik untuk edit"><i
                                        class="fa fa-edit"></i> Edit</button>
                            @else
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#modal-warning">
                                    Data sudah valid
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-warning fade" id="modal-warning">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Sudah divalidasi</h4>
                        </div>
                        <div class="modal-body">
                            <p>Maaf data tidak bisa diedit lagi&hellip;</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalUpdateYudisium{{ $data->id_yudisium }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Yudisium</h5>
                        </div>
                        <div class="modal-body">
                            <form action="/put_penangguhan_yudisium/{{ $data->id_yudisium }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_lengkap"
                                                value="{{ $data->nama_lengkap }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Nomor Induk Kependudukan</label>
                                            <input type="number" class="form-control" name="nik"
                                                value="{{ $data->nik }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tmpt_lahir"
                                                value="{{ $data->tmpt_lahir }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tgl_lahir"
                                                value="{{ $data->tgl_lahir }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>File Ijazah Terakhir</label>
                                    <input type="file" class="form-control" name="file_ijazah"
                                        value="{{ $data->file_ijazah }}">
                                    {{ $data->file_ijazah }} <br>
                                    <span>File size max. 4mb dan format file .jpg </span>
                                </div>
                                <div class="form-group">
                                    <label>File KTP</label>
                                    <input type="file" class="form-control" name="file_ktp"
                                        value="{{ $data->file_ktp }}">
                                    {{ $data->file_ktp }} <br>
                                    <span>File size max. 4mb dan format file .jpg </span>
                                </div>
                                <div class="form-group">
                                    <label>File Foto</label>
                                    <input type="file" class="form-control" name="file_foto"
                                        value="{{ $data->file_foto }}">{{ $data->file_foto }} <br>
                                    <span>File size max. 4mb dan format file .jpg </span>
                                </div>
                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
