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
      <form action="{{url('save_krs')}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id_student" value="{{$mhs}}"/>
      <div class="box-body">
        <table class="table table-condensed">
          <thead>
          <tr>
            <th width="3%">Daftar</th>
            <th width="6%">Id Absen</th>
            <th width="5%">Semester</th>
            <th width="5%">Kode</th>
            <th width="20%">Matakuliah</th>
            <th width="4%">Hari</th>
            <th width="4%">Jam</th>
            <th width="10%">Ruangan</th> 
            <th width="2%">SKST</th>
            <th width="2%">SKSP</th>
            <th width="19%">Dosen</th>
          </tr>
          </thead>
          <tbody>
            @foreach ($add as $key)
            <tr>
              <td>
                <center><input type="checkbox" name="id_kurperiod[]" value="{{$key->id_kurperiode}}, {{$key->idkurtrans}}"></center>
                {{-- <input type="hidden" name="id_kurtrans" value="{{$key->idkurtrans}}"> --}}
              </td>

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
            </tr>
            @endforeach
          </tbody>
        </table>
        <hr>
        <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button" class="btn btn-warning">
        <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button" class="btn btn-warning">
				<input class="btn btn-info" type="submit" name="submit" value="Tambahkan">
        <a class="btn btn-success" href="{{ url('krs') }}">Kembali</a>
      </div>
      </form>
    </div>
  </section>

  <script language="javascript">

      function check_all()
      {
          var chk = document.getElementsByName('id_kurperiod[]');
          for (i = 0; i < chk.length; i++)
          chk[i].checked = true ;
      }

      function uncheck_all()
      {
          var chk = document.getElementsByName('id_kurperiod[]');
          for (i = 0; i < chk.length; i++)
          chk[i].checked = false ;
      }
  </script>
@endsection
