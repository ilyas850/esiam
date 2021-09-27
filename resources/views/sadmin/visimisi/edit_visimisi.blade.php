@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <form action="/saveeditvisimisi/{{$vm->id_visimisi}}" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_method" value="PUT">
      {{ csrf_field() }}
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Form Visi Misi</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="form-group">
              <label>Visi</label>
              <input type="text" class="form-control" name="visi" value="{{$vm->visi}}">
            </div>
          <div class="form-group">
            <label>Misi</label>
            <textarea class="form-control" name="misi" rows="10" cols="80" >{{$vm->misi}}</textarea>
          </div>
          <div class="form-group">
            <label>Tujuan</label>
            <textarea class="form-control" name="tujuan" rows="10" cols="80" >{{$vm->tujuan}}</textarea>
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
  </section>
@endsection
