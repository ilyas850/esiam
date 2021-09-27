@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Record Nilai Mahasiswa
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('val_krs') }}"> Data validasi krs</a></li>
        <li class="active">Data record nilai mahasiswa</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header with-border">
        <table width="100%">
          <tr>
            <td>Nama</td><td>:</td>
            <td>{{$key->nama}}</td>
            <td>Program Studi</td><td>:</td>
            <td>@if ($key->kodeprodi ==23)
                T. Industri
                  @elseif ($key->kodeprodi ==22)
                      T. Komputer
                    @elseif ($key->kodeprodi ==24)
                        Farmasi
                @endif
            </td>
          </tr>
          <tr>
            <td>NIM</td><td>:</td>
            <td> {{$key->nim}}</td>

            <td>Kelas</td><td>:</td>
            <td>@if ($key->idstatus ==1)
                    Reguler A
                  @elseif ($key->idstatus ==2)
                      Reguler C
                    @elseif ($key->idstatus ==3)
                        Reguler B
                @endif
              </td>
          </tr>
        </table>
      </div>

        <div class="box-body">
          <div class="row">
            @if ($b==1)

            @elseif ($b==0)
              <div class="col-md-12">
                <label><span class="badge bg-green">Silahkan masukan matakuliah yang akan ditambahkan</span></label>

                <form class="form-horizontal" role="form" action="{{ url('savekrs_new') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_student" value="{{$mhss}}"/>
                  <select class="form-control" name="id_kurperiode[]" onchange="this.form.submit();">
                    <option value=""><b>-pilih matakuliah-</b></option>
                    @foreach ($add as $key)
                        <option value="{{$key->id_kurperiode}}, {{$key->idkurtrans}}">
                          {{$key->id_kurperiode}} -
                          @foreach ($smt as $semester)
                              @if ($key->id_semester == $semester->idsemester)
                                {{$semester->semester}}
                              @endif
                          @endforeach -
                          @foreach ($mk as $makul)
                            @if ($key->id_makul == $makul->idmakul)
                              {{$makul->kode}}
                            @endif
                          @endforeach -
                          @foreach ($mk as $makul)
                            @if ($key->id_makul == $makul->idmakul)
                              {{$makul->makul}}
                            @endif
                          @endforeach -
                          @foreach ($dsn as $dosen)
                          @if ($key->id_dosen == $dosen->iddosen)
                            {{$dosen->nama}}
                          @endif
                          @endforeach
                    </option>
                    @endforeach
                    </select>
                </form>

                <hr>
              </div>
            @endif

          </div>

          <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th width="3%"><center>No</center></th>
              <th width="5%">Semester</th>
              <th width="5%">Kode</th>
              <th width="16%">Matakuliah</th>
              <th width="4%">Hari</th>
              <th width="3%">Jam</th>
              <th width="8%">Ruangan</th>
              <th width="2%">SKST</th>
              <th width="2%">SKSP</th>
              <th width="18%">Dosen</th>
              <th width="4%">Aksi </th>
            </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              @foreach ($val as $item)
                <tr>
                  <td>{{$no++}}</td>

                  <td>
                      @foreach ($smt as $semester)
                          @if ($item->id_semester == $semester->idsemester)
                            {{$semester->semester}}
                          @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->kode}}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach ($mk as $makul)
                      @if ($item->id_makul == $makul->idmakul)
                        {{$makul->makul}}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach ($hr as $hari)
                      @if ($item->id_hari == $hari->id_hari)
                        {{$hari->hari}}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach ($jm as $jam)
                      @if ($item->id_jam == $jam->id_jam)
                        {{$jam->jam}}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach ($rng as $ruang)
                      @if ($item->id_ruangan == $ruang->id_ruangan)
                        {{$ruang->nama_ruangan}}
                      @endif
                    @endforeach
                  </td>
                  <td><center>
                    {{$item->akt_sks_teori}}
                  </center></td>
                  <td><center>
                    {{$item->akt_sks_praktek}}
                  </center></td>
                  <td>
                    @foreach ($dsn as $dosen)
                      @if ($item->id_dosen == $dosen->iddosen)
                        {{$dosen->nama}}
                      @endif
                    @endforeach
                  </td>
                  <td><center>
                    @if ($item->remark == 1)
                      <form method="POST" action="{{url('batalkrsmhs')}}">
                           <input type="hidden" name="id_studentrecord" value="{{$item->id_studentrecord}}">
                           <input type="hidden" name="id_student" value="{{$item->idstudent}}">
                           <input type="hidden" name="remark" value="0">
                           {{ csrf_field() }}
                           <button type="submit" class="btn btn-danger btn-xs" title="klik untuk batal" data-toggle="tooltip" data-placement="right" onclick="return confirm('apakah anda yakin akan membatalkan matakuliah ini?')">Batal</button>
                         </form>
                      
                    @elseif ($item->remark == 0)
                        <form method="POST" action="{{url('batalkrsmhs')}}">
                           <input type="hidden" name="id_studentrecord" value="{{$item->id_studentrecord}}">
                           <input type="hidden" name="id_student" value="{{$item->idstudent}}">
                           <input type="hidden" name="remark" value="1">
                           {{ csrf_field() }}
                           <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="right">Validasi</button>
                         </form>
                    @endif

                  </center></td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>

    </div>
  </section>
@endsection
