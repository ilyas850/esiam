@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Foto Mahasiswa Teknik Komputer
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
          <li><a href="{{ url('data_foto') }}"> Data Foto</a></li>
        <li class="active">Data Foto Teknik Komputer</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="content">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Mahasiswa Teknik Komputer</h3>

            <div class="box-tools pull-right">
              <span class="label label-info">{{$jmltk}} orang </span>
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body no-padding">
            <ul class="users-list clearfix">
              @foreach ($fototk as $img)
                <table>
                  <tr>
                    <li>
                      @if ($img->foto == null)
                        <img class="img-circle" height="128px" width="128px" src="/adminlte/img/default.jpg" alt="User Avatar">
                        @else
                          <img class="img-circle" height="100px" width="100px" src="{{ asset('/foto_mhs/'.$img->foto) }}" alt="User Image">
                      @endif
                      <a class="users-list-name" href="#">{{$img->nama}}</a>
                      <span class="users-list-date">
                        @foreach ($angk as $angkatan)
                          @if ($img->idangkatan == $angkatan->idangkatan)
                            {{$angkatan->angkatan}}
                          @endif
                        @endforeach
                      </span>
                    </li>
                  </tr>
                </table>

              @endforeach
            </ul>
          </div>
        </div>
      </section>
@endsection
