@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Generate User Mahasiswa</h3>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('save_usermhs') }}">
              {{ csrf_field() }}
              <input id="role" type="hidden" class="form-control" name="role" value="4">
              <input id="role" type="hidden" class="form-control" name="id_user" value="{{ $mhss->idstudent}}">
              <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Nama</label>

                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="name" value="{{ $mhss->nama }}" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">NIM</label>

                    <div class="col-sm-7">
                        <input type="number" class="form-control" name="username" value="{{ $mhss->nim }}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password</label>
                    <div class="col-sm-7">
                        <input id="password" type="text" class="form-control" name="password" value="{{ $pwds }}" required>
                    </div>
                </div>
              </div>
              <div class="box-footer">
                <a href="{{ url('show_user') }}" class="btn btn-default">Kembali</a>
                <button type="submit" class="btn btn-info pull-right">Simpan</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        </div>
    </div>
  </section>
@endsection
