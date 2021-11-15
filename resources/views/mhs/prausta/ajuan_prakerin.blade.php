@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-primary">
      <form class="" action="{{url('simpan_ajuan_prakerin')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id_settingrelasi_prausta" value="{{$data->id_settingrelasi_prausta}}">
        <div class="box-header with-border">
          Form Seminar Prakerin
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis PraUSTA</label>
                <input type="text" class="form-control" value="{{$data->kode_prausta}} - {{$data->nama_prausta}}" readonly>
              </div>
              <div class="form-group">
                <label>Nama Lengkap</label>

                <input type="text" class="form-control" value="{{$data->nama}}" readonly>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>NIM</label>
                <input type="text" class="form-control" value="{{$data->nim}}" readonly>
              </div>
              <div class="form-group">
                <label>Program Studi</label>
                <input type="text" class="form-control" value="{{$data->prodi}}" readonly>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Dosen Pembimbing</label>
                <input type="text" class="form-control" value="{{$data->dosen_pembimbing}}" readonly>
              </div>

              <div class="form-group">
                <label>Judul Seminar Prakerin<font color ="red-text">*</font></label>
                <textarea class="form-control" name="judul_prausta" rows="3" cols="60" required></textarea>
              </div>

              <div class="form-group">
                <label>Tempat Prakerin<font color ="red-text">*</font></label>
                <input type="text" class="form-control" name="tempat_prausta" required>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </section>
@endsection
