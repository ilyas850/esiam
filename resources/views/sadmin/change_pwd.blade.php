@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>Ubah Password</b></h3>
                    </div>

                    <form class="form-horizontal" role="form" method="POST" action="/pwd_adm/{{ $adm }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
                                <label class="col-sm-4 control-label">Password lama</label>

                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="oldpassword"
                                        value="{{ old('oldpassword') }}" placeholder="Masukan password lama anda" required
                                        autofocus>
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
                                    <input id="password" type="password" class="form-control" name="password"
                                        placeholder="Password Min. 7 karakter" required>

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
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Konfirmasi password" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-info pull-right" type="submit">Simpan</button>
                            <input type="hidden" name="_method" value="PUT">
                            <a href="{{ url('home') }}" class="btn btn-default">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
