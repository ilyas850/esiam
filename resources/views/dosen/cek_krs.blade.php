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
            <td>{{$key->prodi}}</td>
          </tr>
          <tr>
            <td>NIM</td><td>:</td>
            <td> {{$key->nim}}</td>
            <td>Kelas</td><td>:</td>
            <td>{{$key->kelas}}  </td>
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
                          {{$key->id_kurperiode}} - {{$key->semester}} - {{$key->kode}} - {{$key->makul}} - {{$key->nama}}
                        </option>
                    @endforeach
                    </select>
                </form>
                <br>
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
                  <td>{{$item->semester}}</td>
                  <td>{{$item->kode}}</td>
                  <td>{{$item->makul}}</td>
                  <td>{{$item->hari}}</td>
                  <td>{{$item->jam}}</td>
                  <td>{{$item->nama_ruangan}}</td>
                  <td><center>{{$item->akt_sks_teori}}</center></td>
                  <td><center>{{$item->akt_sks_praktek}}</center></td>
                  <td>{{$item->nama}}</td>
                  <td><center>
                    @if ($item->remark == 1)
                      <span class="badge bg-green">sudah</span>
                    @elseif ($item->remark == 0)
                        <form method="POST" action="{{url('hapuskrsmhs')}}">
                           <input type="hidden" name="status" value="DROPPED">
                           <input type="hidden" name="id_studentrecord" value="{{$item->id_studentrecord}}">
                           <input type="hidden" name="id_student" value="{{$item->idstudent}}">
                           {{ csrf_field() }}
                           <button type="submit" class="btn btn-danger btn-xs" title="klik untuk batal" data-toggle="tooltip" data-placement="right" onclick="return confirm('apakah anda yakin akan membatalkan matakuliah ini?')">Batal</button>
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
