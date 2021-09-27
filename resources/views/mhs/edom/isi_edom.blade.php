@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content_header')
  <section class="content-header">
      <h1>
        Input EDOM
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">Input EDOM</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Isi EDOM Mahasiswa</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-striped">
                  <tr>
                    <th width="5%"><center>No</center></th>
                    <th width="7%"><center>Kode</center></th>
                    <th width="25%">Matakuliah</th>
                    <th width="20%">Dosen</th>
                    <th width="7%" colspan="2"><center>Aksi</center></th>

                  </tr>
                  <?php $no=1; ?>
                  @foreach ($edom as $item)
                  <tr>
                    <td><center>{{$no++}}</center></td>
                    <td><center>
                      @foreach ($mk as $makul)
                        @if ($item->id_makul == $makul->idmakul)
                          {{$makul->kode}}
                        @endif
                      @endforeach
                    </center></td>
                    <td>
                      @foreach ($mk as $makul)
                        @if ($item->id_makul == $makul->idmakul)
                          {{$makul->makul}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      @foreach ($dsn as $dosen)
                        @if ($item->id_dosen == $dosen->iddosen)
                          {{$dosen->nama}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      <form action="{{('form_edom')}}" method="post">
                        <input type="hidden" name="id_student" value="{{$item->id_student}}">
                        <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                        <input type="hidden" name="id_kurtrans" value="{{$item->id_kurtrans}}">
                        <input type="hidden" name="id_makul" value="{{$item->id_makul}}">
                        <input type="hidden" name="id_dosen" value="{{$item->id_dosen}}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right">Isi Form</button>
                      </form>
                    </td>
                    <td>
                      <form action="{{('edom_kom')}}" method="post">
                        <input type="hidden" name="id_student" value="{{$item->id_student}}">
                        <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                        <input type="hidden" name="id_kurtrans" value="{{$item->id_kurtrans}}">
                        <input type="hidden" name="id_makul" value="{{$item->id_makul}}">
                        <input type="hidden" name="id_dosen" value="{{$item->id_dosen}}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right">Isi Komentar</button>
                      </form>
                    </td>
                  </tr>
                  @endforeach

              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
  </section>
@endsection
