@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="form-group">
      <div class="callout callout-warning">
          <p>Matakuliah yang muncul adalah matakuliah dengan sistem paket, lapor ke BAAK jika ingin mengambil matakuliah yang diulang diluar paket.</p>
      </div>
    </div>
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Data KRS Mahasiswa</h3>
      </div>
      <div class="box-body">
        <table class="table table-condensed">
          <thead>
          <tr>

            <th width="5%"><center>Id</center></th>
            <th width="5%">Semester</th>
            <th width="5%">Kode</th>
            <th width="20%">Matakuliah</th>
            <th width="4%">Hari</th>
            <th width="4%">Jam</th>
            <th width="10%">Ruangan</th>
            <th width="2%">SKST</th>
            <th width="2%">SKSP</th>
            <th width="19%">Dosen</th>
            <th width="3%">Aksi</th>
          </tr>
          </thead>
          <tbody>
            @foreach ($add as $key)
            <tr>
              <td>
                <center>{{$key->id_kurperiode}}</center>
              </td>
              <td>
                @foreach ($smt as $semester)
                      @if ($key->id_semester == $semester->idsemester)
                        {{$semester->semester}}
                      @endif
                @endforeach
              </td>
              <td>
                @foreach ($mk as $makul)
                  @if ($key->id_makul == $makul->idmakul)
                    {{$makul->kode}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($mk as $makul)
                  @if ($key->id_makul == $makul->idmakul)
                    {{$makul->makul}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($hr as $hari)
                  @if ($key->id_hari == $hari->idhari)
                    {{$hari->hari}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($jm as $jam)
                  @if ($key->id_jam == $jam->idjam)
                    {{$jam->jam}}
                  @endif
                @endforeach
              </td>
              <td>
                @foreach ($rg as $ruang)
                  @if ($key->id_ruangan == $ruang->id_ruangan)
                    {{$ruang->nama_ruangan}}
                  @endif
                @endforeach
              </td>
              <td>{{$key->akt_sks_teori}}</td>
              <td>{{$key->akt_sks_praktek}}</td>
              <td>
                @foreach ($dsn as $dosen)
                  @if ($key->id_dosen == $dosen->iddosen)
                    {{$dosen->nama}}
                  @endif
                @endforeach
              </td>
              <td>
                <form action="{{ url('post_krs') }}" method="POST" name="myform">
  								{{ csrf_field() }}
                  <input type="hidden" name="id_student" value="{{$mhs}}"/>
  								<input type="hidden" name="id_kurperiod" value="{{$key->id_kurperiode}}">
  								<input type="hidden" name="id_kurtrans" value="{{$key->idkurtrans}}">
  	  						<button type="submit" name="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right">Ambil</button>
  	  					</form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <hr>
        <a class="btn btn-success" href="{{ url('krs') }}">Kembali</a>

      </div>
    </div>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Pilih Matakuliah</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" role="form" action="{{ url('simpan_krs') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="id_student" value="{{$mhs}}"/>
              <select class="form-control" name="id_kurperiod[]" onchange="this.form.submit();">
                <option value="">pilih</option>
                @foreach ($add as $key)
                    <option value="{{$key->id_kurperiode}}, {{$key->idkurtrans}}">{{$key->id_kurperiode}} - @foreach ($smt as $semester)
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
                  @endforeach</option>
                @endforeach
                </select>
            </form>
          </div>
        </div>
        <!-- /.row -->
        
      </div>
    </div>
  </section>


@endsection
