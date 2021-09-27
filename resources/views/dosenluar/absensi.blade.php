@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
        Data Absensi Mahasiswa
        </h1>
        <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
        <li><a href="/entri_bap_dsn/{{$idk}}"> BAP</a></li>
        <li class="active">Entri Absensi Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Data Absensi Mahasiswa</h3>
        </div>
        <form action="{{url('save_absensi_dsn')}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id_kurperiode" value="{{$idk}}">
        <input type="hidden" name="id_bap" value="{{$id}}">
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%"><center>No</center></th>
                            <th width="8%"><center>NIM </center></th>
                            <th width="20%"><center>Nama</center></th>
                            <th width="15%"><center>Program Studi</center></th>
                            <th width="8%"><center>Kelas</center></th>
                            <th width="8%"><center>Angkatan</center></th>
                            <th width="8%"><center>Pilih</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; ?>
                        @foreach ($absen as $item)
                            <tr>
                                <td><center>{{$no++}}</center></td>
                                <td><center>{{$item->nim}}</center></td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->prodi}}</td>
                                <td><center>{{$item->kelas}}</center></td>
                                <td><center>{{$item->angkatan}}</center></td>
                                <td><center>
                                    <input type="hidden" name="id_studentrecord[]" value="{{$item->id_studentrecord}}">
                                    <input type="checkbox" name="absensi[]" value="{{$item->id_studentrecord}},ABSEN" >    
                                </center></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <input class="btn btn-info full-right" type="submit" name="submit" value="Simpan">
            </div>
        </form>
    </div>
</section>
@endsection
