@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Mahasiswa Aktif
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

        <li class="active">Data mahasiswa aktif</li>
      </ol>
    </section>
@endsection

@section('content')
  <section class="content">
      <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Silahkan Filter</h3>
        </div>
            <form class="form" role="form" action="{{url('cari_mhs_aktif')}}" method="POST">
            {{ csrf_field() }}
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="">Periode Tahun</label>
                        <select class="form-control" name="id_periodetahun">
                            <option></option>
                            @foreach ($thn as $thn)
                                <option value="{{$thn->id_periodetahun}}">{{$thn->periode_tahun}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <label for="">Periode Tipe</label>
                        <select class="form-control" name="id_periodetipe" required>
                            <option></option>
                            @foreach ($tp as $tipee)
                                <option value="{{$tipee->id_periodetipe}}">{{$tipee->periode_tipe}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <label for="">Program Studi</label>
                        <select class="form-control" name="kodeprodi" required>
                            <option></option>
                            @foreach ($prd as $prd)
                                <option value="{{$prd->kodeprodi}}">{{$prd->prodi}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info" >Cari Mahasiswa</button>
            </div>
        </form>
    </div>
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Data Mahasiswa Aktif {{$prod}} {{$ta}} {{$tpe}}</h3>
        </div>
        <div class="box-body">
            <form action="{{url('export_data')}}" method="post">
                 {{ csrf_field() }}
                <input type="hidden" name="id_periodetahun" value="{{$idthn}}">
                <input type="hidden" name="id_periodetipe" value="{{$idtp}}">
                <input type="hidden" name="kodeprodi" value="{{$idkd}}">
                <button type="submit" class="btn btn-success" >Export Excel</button>
            </form>
            
            <br>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><center>No</center></th>
                        <th><center>NIM</center></th>
                        <th><center>Nama Mahasiswa</center></th>
                        <th><center>Program Studi</center></th>
                        <th><center>Kelas</center></th>
                        <th><center>Angkatan</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($data as $key)
                    <tr>
                        <td><center>{{$no++}}</center></td>
                        <td><center>{{$key->nim}}</center></td>
                        <td>{{$key->nama}}</td>
                        <td><center>{{$key->prodi}}</center></td>
                        <td><center>{{$key->kelas}}</center></td>
                        <td><center>{{$key->angkatan}}</center></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div>
  </section>
@endsection