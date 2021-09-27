@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Upload Foto Mahasiswa
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">Data Foto</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Mahasiswa Teknik Industri</h3>

            <div class="box-tools pull-right">
              <span class="label label-danger">{{$jmlti}} orang </span>
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <div class="box-body no-padding">
            <ul class="users-list clearfix">
              @foreach ($fototi as $img)
                <li>
                  @if ($img->foto == null)
                    <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
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
              @endforeach
            </ul>
          </div>
          <div class="box-footer text-center">
            <a href="/lihat_foto_ti" class="uppercase">Lihat semua</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
          <!-- USERS LIST -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Mahasiswa Teknik Komputer</h3>

              <div class="box-tools pull-right">
                <span class="label label-info">{{$jmltk}} orang</span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="users-list clearfix">
                @foreach ($fototk as $img)
                  <li>
                    @if ($img->foto == null)
                      <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
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
                @endforeach
              </ul>
            </div>
            <div class="box-footer text-center">
              <a href="/lihat_foto_tk" class="uppercase">Lihat semua</a>
            </div>
          </div>
        </div>
      <div class="col-md-4">
            <!-- USERS LIST -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Mahasiswa Farmasi</h3>

                <div class="box-tools pull-right">
                  <span class="label label-success">{{$jmlfm}} orang</span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="box-body no-padding">
                <ul class="users-list clearfix">
                  @foreach ($fotofm as $img)
                    <li>
                      @if ($img->foto == null)
                        <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
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
                  @endforeach
                </ul>
              </div>
              <div class="box-footer text-center">
                <a href="/lihat_foto_fm" class="uppercase">Lihat semua</a>
              </div>
            </div>
          </div>
    </div>
  </section>
@endsection
