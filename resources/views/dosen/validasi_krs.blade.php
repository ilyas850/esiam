@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Validasi KRS
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

        <li class="active">Data validasi krs</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Data validasi KRS mahasiswa</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="4px"><center>No</center></th>
              <th><center>Nama Mahasiswa</center></th>
              <th width="10%"><center>NIM</center></th>
              <th width="15%"><center>Program Studi</center></th>
              <th width="10%"><center>Kelas</center></th>
              <th width="10%"><center>Angkatan</center></th>
              <th><center>Status KRS</center></th>
              <th><center>Aksi</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($val as $key)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td>{{$key->nama}}</td>
                <td><center>{{$key->nim}}</center></td>
                <td><center>
                  @if ($key->kodeprodi ==23)
                    Teknik Industri
                      @elseif ($key->kodeprodi ==22)
                          Teknik Komputer
                        @elseif ($key->kodeprodi ==24)
                            Farmasi
                    @endif
                </center></td>
                <td><center>
                  @if ($key->idstatus ==1)
                          Reguler A
                        @elseif ($key->idstatus ==2)
                            Reguler C
                          @elseif ($key->idstatus ==3)
                              Reguler B
                      @endif
                </center></td>
                <td><center>
                  @foreach ($angk as $akt)
                    @if ($key->idangkatan == $akt->idangkatan)
                      {{$akt->angkatan}}
                    @endif
                  @endforeach
                </center></td>
                <td><center>
                  @if ($key->remark == 1)
                    <span class="badge bg-yellow">Sudah divalidasi</span>
                  @elseif ($key->remark == 0)
                  <form class="" action="{{url('krs_validasi')}}" method="post">
                      <input type="hidden" name="id_student" value="{{$key->id_student}}">
                      <input type="hidden" name="remark" value="1">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="right">Validasi</button>
                    </form>
                    
                    {{-- <form class="" action="{{url('krs_validasi')}}" method="post">
                      <input type="hidden" name="id_student" value="{{$key->id_student}}">
                      <input type="hidden" name="remark" value="0">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="right">Batal</button>
                    </form> --}}
                  @endif
                </center></td>
                <td><center>
                  <a class="btn btn-info btn-xs"href="/cek_krs/{{$key->id_student}}">Cek KRS</a>
                </center></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
