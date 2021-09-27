@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Kartu Rencana Studi Mahasiswa
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">Input KRS</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <center><h2>Kartu Rencana Studi</h2></center>
          <table width="100%">
            <tr>
                <td>TA Semester</td><td> : </td>
                <td><u>
                  @foreach ($thn as $TA)
                    {{ $TA->periode_tahun }}
                  @endforeach
                  @foreach ($tp as $key)
                    ({{ $key->periode_tipe }})
                  @endforeach
                </u></td>
                <td align=right>Jumlah SKS Maksimal</td>
                <td>:</td>
                <td>24</td>
            </tr>
            <tr>
                <td>Nama</td><td> : </td>
                <td><u>{{ $mhs->nama }}</u></td>
                <td align=right>SKS Tempuh&ensp;</td>
                <td>:</td>
                <td>{{$sks}}</td>
            </tr>
            <tr>
                <td>NIM</td><td> : </td>
                <td><u>{{ $mhs->nim }}</u></td>
            </tr>
            <tr>
                <td>Jurusan</td><td> : </td>
                <td><u> {{$mhs->prodi}}</u></td>
            </tr>
            <tr>
                <td>Kelas</td><td>:</td>
                <td><u>{{$mhs->kelas}}  </u></td>
            </tr>
          </table>
      </div>

      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <label for="">Silahkan masukan matakuliah yang akan diambil</label>
              <form class="form-horizontal" role="form" action="{{ url('simpan_krs') }}" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="id_student" value="{{$mhss}}"/>
                    <select class="form-control" name="id_kurperiode[]" onchange="this.form.submit();">
                      <option value="">-pilih matakuliah-</option>
                      @foreach ($add as $key)
                          <option value="{{$key->id_kurperiode}}, {{$key->idkurtrans}}">
                            {{$key->semester}} - {{$key->kode}} - {{$key->makul}} - {{$key->nama}}
                          </option>
                      @endforeach
                    </select>
              </form>
            </div>
        </div>
        <br>
        <a class="btn btn-warning" href="{{url('unduh_krs')}}">Unduh KRS</a>

        <div class="row">
          <div class="col-md-12">
            <h3 class="box-title">Matakuliah yang diambil</h3>
            <table class="table table-condensed">
              <thead>
              <tr>
                <th width="6%">Tanggal KRS</th>
                <th width="5%">Semester</th>
                <th width="5%">Kode</th>
                <th width="16%">Matakuliah</th>
                <th width="4%">Hari</th>
                <th width="3%">Jam</th>
                <th width="8%">Ruangan</th>
                <th width="2%">SKST</th>
                <th width="2%">SKSP</th>
                <th width="18%">Dosen</th>
                <th width="4%"> </th>
              </tr>
              </thead>
              <tbody>
                @foreach ($krs as $item)
                <tr>
                  <td><center>
                    {{$item->tanggal_krs}}
                  </center></td>
                  <td>{{$item->semester}} </td>
                  <td>{{$item->kode}}</td>
                  <td>{{$item->makul}}</td>
                  <td>{{$item->hari}}</td>
                  <td>{{$item->jam}}</td>
                  <td>{{$item->nama_ruangan}}</td>
                  <td><center>{{$item->akt_sks_teori}}</center></td>
                  <td><center>{{$item->akt_sks_praktek}}</center></td>
                  <td>{{$item->nama}}</td>
                  <td>
                    @if ($item->remark == 0)
                      <form method="POST" action="{{url('batalkrs')}}">
                         <input type="hidden" name="status" value="DROPPED">
                         <input type="hidden" name="id_studentrecord" value="{{$item->id_studentrecord}}">
                         {{ csrf_field() }}
                         <button type="submit" class="btn btn-danger btn-xs" title="klik untuk batal" data-toggle="tooltip" data-placement="right" onclick="return confirm('apakah anda yakin akan membatalkan matakuliah ini?')">Batal</button>
                       </form>
                     @elseif ($item->remark == 1)
                       <span class="badge bg-green">valid</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
