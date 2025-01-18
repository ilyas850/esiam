@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="box box-primary">
            <form class="" action="{{ url('simpan_ajuan_ta') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_settingrelasi_prausta" value="{{ $data->id_settingrelasi_prausta }}">
                <div class="box-header with-border">
                    Form Tugas Akhir
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="text" class="form-control" value="{{ $data->nim }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $data->nama }}" readonly>
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
                                <label>Kategori PraUSTA<font color="red-text">*</font></label>
                                <select name="id_kategori_prausta" class="form-control" required>
                                    <option></option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Mulai Tugas Akhir<font color="red-text">*</font></label>
                            <input type="date" class="form-control" name="tanggal_mulai" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dosen Pembimbing</label>
                                <input type="text" class="form-control" value="{{ $data->dosen_pembimbing }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Judul Tugas Akhir<font color="red-text">*</font></label>
                                <textarea class="form-control" name="judul_prausta" rows="3" cols="60"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Tempat<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="tempat_prausta" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>
@endsection
