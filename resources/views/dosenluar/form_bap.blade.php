@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Form Berita Acara Perkuliahan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
        <li><a href="/entri_bap_dsn/{{$id}}"> BAP</a></li>
        <li class="active">Form BAP</li>
      </ol>
    </section>
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
    <div class="box box-info">
        <form class="form-horizontal" method="POST" action="{{ url('save_bap_dsn') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id_kurperiode" value="{{$id}}">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label><font color ="red-text">*</font>Pertemuan</label>
                            <select class="form-control" name="pertemuan" required>
                                <option></option>
                                <option value="1">Pertemuan Ke-1</option>
                                <option value="2">Pertemuan Ke-2</option>
                                <option value="3">Pertemuan Ke-3</option>
                                <option value="4">Pertemuan Ke-4</option>
                                <option value="5">Pertemuan Ke-5</option>
                                <option value="6">Pertemuan Ke-6</option>
                                <option value="7">Pertemuan Ke-7</option>
                                <option value="8">Pertemuan Ke-8</option>
                                <option value="9">Pertemuan Ke-9</option>
                                <option value="10">Pertemuan Ke-10</option>
                                <option value="11">Pertemuan Ke-11</option>
                                <option value="12">Pertemuan Ke-12</option>
                                <option value="13">Pertemuan Ke-13</option>
                                <option value="14">Pertemuan Ke-14</option>
                                <option value="15">Pertemuan Ke-15</option>
                                <option value="16">Pertemuan Ke-16</option>
                            </select>
                            @if ($errors->has('pertemuan'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pertemuan') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label><font color = "red-text">*</font>Tanggal</label>

                            <input type="date" class="form-control " name="tanggal" placeholder="Masukan " required>
                            @if ($errors->has('tanggal'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tanggal') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label><font color = "red-text">*</font>Jam Mulai</label>
                            <input type="text" class="form-control" name="jam_mulai" placeholder="Contoh 12:00" required>
                            @if ($errors->has('jam_mulai'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jam_mulai') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label><font color = "red-text">*</font>Jam Selesai</label>
                            <input type="text" class="form-control" name="jam_selsai" placeholder="Contoh 16:00" required>
                            @if ($errors->has('jam_selsai'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jam_selsai') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label><font color ="red-text">*</font>Jenis Kuliah/Ujian</label>
                            <select class="form-control" name="jenis_kuliah" required>
                                <option></option>
                                <option value="Kuliah">Kuliah</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label><font color ="red-text">*</font>Tipe Kuliah/Ujian</label>
                            <select class="form-control" name="id_tipekuliah" required>
                                <option></option>
                                <option value="1">Teori</option>
                                <option value="2">Praktikum</option>
                                <option value="3">Teori + Praktikum</option>
                            </select>
                        </div>
                         <div class="col-md-3">
                            <label><font color ="red-text">*</font>Metode Kuliah/Ujian</label>
                            <select class="form-control" name="metode_kuliah" required>
                                <option></option>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label><font color ="red-text">*</font>Materi Kuliah/Ujian</label>
                            <textarea class="form-control" rows="3" name="materi_kuliah" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label><font color ="red-text">*</font>Media Pembelajaran/Ujian</label>
                            <textarea class="form-control" rows="3" name="media_pembelajaran" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label>Upload File Kuliah Tatap Muka</label>
                            <input type="file" name="file_kuliah_tatapmuka">
                            @if ($errors->has('file_kuliah_tatapmuka'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file_kuliah_tatapmuka') }}</strong>
                                </span>
                            @endif
                            <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg .png</p>
                        </div>
                        <div class="col-md-3">
                            <label>Upload File Materi Kuliah/Ujian</label>
                            <input type="file" name="file_materi_kuliah">
                            @if ($errors->has('file_materi_kuliah'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file_materi_kuliah') }}</strong>
                                </span>
                            @endif
                            <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg .pdf .png</p>
                        </div>
                        <div class="col-md-3">
                            <label>Upload File Materi Tugas/Ujian</label>
                            <input type="file" name="file_materi_tugas">
                            @if ($errors->has('file_materi_tugas'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file_materi_tugas') }}</strong>
                                </span>
                            @endif
                            <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg .png</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
