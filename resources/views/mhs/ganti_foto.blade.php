@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content_header')
    <section class="content-header">
        <h1>
            Ganti Foto
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li class="active">Ganti foto</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <form action="/simpanfoto/{{ $mhs->idstudent }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <center>
                                <img src="/images/KTM 2019 19.jpg" alt="User profile picture" width="150px" height="150px">
                                <img src="/images/KTM 2019 141.jpg" alt="User profile picture" width="150px"
                                    height="150px">
                            </center>
                            <h3 class="profile-username text-center">Contoh foto</h3>
                            <p class="text-center">(Wajib menggunakan almamater)</p>
                            <div class="form-group">
                                <label>Pilih foto</label>
                                <input type="file" name="foto" required>
                                @if ($errors->has('foto'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('foto') }}</strong>
                                    </span>
                                @endif
                                <p class="help-block">Max. size 500kb dengan format .jpg .jpeg </p>
                            </div>
                            <button type="submit" class="btn btn-info btn-block" name="button">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
