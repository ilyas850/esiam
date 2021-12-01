@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form action="/simpanedit/{{ $info->id_informasi }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Form Edit Informasi</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" class="form-control" name="judul" value="{{ $info->judul }}">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="8"
                                    cols="80">{{ $info->deskripsi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Upload File</label>
                                <input type="file" name="file">{{ $info->file }}

                                <p class="help-block">Max. size 1mb dengan format .jpg .jpeg .png</p>
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
            </div>
        </div>
    </section>
@endsection
