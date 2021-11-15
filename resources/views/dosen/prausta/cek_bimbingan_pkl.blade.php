@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Cek Mahasiswa Bimbingan PKL
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('pembimbing_pkl')}}">Data Mahasiswa PKL</a></li>
        <li class="active">Cek Mahasiswa Bimbingan PKL</li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">
  <div class="box box-info">
    <div class="box-header with-border">
        <table width="100%">
            <tr>
                <td>NIM</td><td>:</td>
                <td>{{$jdl->nim}}</td>
                <td>Program Studi</td><td>:</td>
                <td>{{$jdl->prodi}}</td>
            </tr>
            <tr>
                <td>Nama</td><td>:</td>
                <td>{{$jdl->nama}}</td>
                <td>Kelas</td><td>:</td>
                <td>{{$jdl->kelas}}</td>
            </tr>
        </table>
    </div>
    <div class="box-body">
      <div class="row">
        <form class="" action="{{url('status_judul')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="id_settingrelasi_prausta" value="{{$jdl->id_settingrelasi_prausta}}">
          <div class="col-md-12">
            <div class="form-group">
              <label>Judul Prakerin</label>
              <textarea class="form-control" rows="1" cols="60" readonly>{{$jdl->judul_prausta}}</textarea>
            </div>
            <div class="form-group">
              <label>Tempat Prakerin</label>
              <input type="text" class="form-control" value="{{$jdl->tempat_prausta}}" readonly>
            </div>
            <div class="form-group">
              @if ($jdl->acc_judul == 'BELUM')
                <button type="submit" class="btn btn-info" name="acc_judul" value="SUDAH">Terima/Acc</button>
                <button type="submit" class="btn btn-warning" name="acc_judul" value="REVISI">Revisi</button>
              @elseif ($jdl->acc_judul == 'REVISI')
                <button type="submit" class="btn btn-info" name="acc_judul" value="SUDAH">Terima/Acc</button>
                <button type="submit" class="btn btn-warning" name="acc_judul" value="REVISI">Revisi Lagi</button>
              @elseif ($jdl->acc_judul == 'SUDAH')
                <span class="badge bg-blue">Judul telah di Acc.</span>
              @endif
            </div>
          </div>
        </form>
      </div>
      <div class="row">
      </div>
    </div>
  </div>
</section>
@endsection
