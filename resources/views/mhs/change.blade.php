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
            <h3 class="box-title"><b>Edit Data Diri</b></h3>
          </div>

          <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="/save_change/{{$mhs->id}}">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" class="form-control" name="id_mhs" value="{{$mhs->id_mhs}}">
            <div class="box-body">
              <div class="form-group">
                  <label class="col-sm-4 control-label">No HP baru</label>

                  <div class="col-sm-7">
                      <input type="text" class="form-control" name="hp_baru" value="{{$mhs->hp_baru}}"required>

                      @if ($errors->has('hp_baru'))
                          <span class="help-block">
                              <strong>{{ $errors->first('hp_baru') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group">
                  <label for="password" class="col-sm-4 control-label">E-mail baru</label>

                  <div class="col-sm-7">
                      <input type="email" class="form-control" name="email_baru" value="{{$mhs->email_baru}}" required>

                      @if ($errors->has('email_baru'))
                          <span class="help-block">
                              <strong>{{ $errors->first('email_baru') }}</strong>
                          </span>
                      @endif
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
