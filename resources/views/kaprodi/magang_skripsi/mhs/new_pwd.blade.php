@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
       <br>
        <div class="box box-info">

          <div class="box-header with-border">
            <span class="fa fa-graduation-cap"></span>
            <h3 class="box-title">Selamat Datang Mahasiswa Politeknik META Industri Cikarang</h3>
          </div>

          <form class="form-horizontal" role="form" method="POST" action="/pwd/{{$id}}/store">
            {{ csrf_field() }}
            <input id="role" type="hidden" class="form-control" name="role" value="3">
            <div class="box-body">
              <div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
                  <label class="col-sm-4 control-label">Password lama</label>

                  <div class="col-sm-7">
                      <input type="number" class="form-control" name="oldpassword" value="{{ old('oldpassword') }}" placeholder="Masukan NIM anda" required autofocus>

                      @if ($errors->has('oldpassword'))
                          <span class="help-block">
                              <strong>{{ $errors->first('oldpassword') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password" class="col-sm-4 control-label">Password Baru</label>

                  <div class="col-sm-7">
                      <input id="password" type="password" class="form-control" name="password" placeholder="Password Min. 7 karakter" required>

                      @if ($errors->has('password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group">
                  <label for="password-confirm" class="col-sm-4 control-label">Konfirmasi Password</label>

                  <div class="col-sm-7">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password" required>
                  </div>
              </div>
            </div>
            <div class="box-footer">
              <button class="btn btn-info pull-right" type="submit">Simpan</button>
              <input type="hidden" name="_method" value="PUT">
              <a class="btn btn-default" href="{{url('home')}}">Batal</a>
              {{-- <a href="{{ route('logout') }}" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"
                       class="btn btn-default btn-flat">Keluar</a>
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           {{ csrf_field() }}
                       </form> --}}
            </div>
          </form>
        </div>
    </div>
  </div>
@endsection
