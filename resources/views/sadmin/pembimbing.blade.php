@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Dosen Pembimbing
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

        <li class="active">Data dosen pembimbing</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Data pembimbing</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th width="4px"><center>No</center></th>
            <th><center>Nama Mahasiswa</center></th>
            <th><center>NIM</center></th>
            <th><center>Program Studi</center></th>
            <th width="10%"><center>Kelas</center></th>
            <th width="25%"><center>Dosen Pembimbing</center></th>
          </tr>
          </thead>
          <tbody>
          <?php $no=1;?>
          @foreach ($dosbing as $dsnbim)
            <tr>
              <td><center>{{$no++}}</center></td>
              <td>{{$dsnbim->nama}}</td>
              <td><center>{{$dsnbim->nim}}</center></td>
              <td><center>
                @if ($dsnbim->kodeprodi ==23)
                    Teknik Industri
                      @elseif ($dsnbim->kodeprodi ==22)
                          Teknik Komputer
                        @elseif ($dsnbim->kodeprodi ==24)
                            Farmasi
                    @endif
              </center></td>
              <td><center>
                @if ($dsnbim->idstatus ==1)
                        Reguler A
                      @elseif ($dsnbim->idstatus ==2)
                          Reguler C
                        @elseif ($dsnbim->idstatus ==3)
                            Reguler B
                    @endif
              </center></td>
              <td>
                @foreach ($dsn as $keydsn)
                  @if ($dsnbim->id_dosen==$keydsn->iddosen)
                    {{$keydsn->nama}}
                  @endif
                @endforeach
              </td>
            </tr>
          @endforeach
          </tbody>
      </table>

    </div>
    </div>
  </section>
@endsection
