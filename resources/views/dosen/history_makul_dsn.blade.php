@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
        History Matakuliah yang diampu
        </h1>
        <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">History Matakuliah yang diampu</li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">History Matakuliah</h3>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan= "2">No</th>
                        <th rowspan= "2" width="5%"><center>Kode </center></th>
                        <th rowspan= "2" width="20%"><center>Matakuliah</center></th>
                        <th colspan="2"><center>SKS</center></th>
                        <th rowspan= "2" width="13%"><center>Program Studi</center></th>
                        <th rowspan= "2" width="8%"><center>Kelas</center></th>
                        <th rowspan= "2" width="8%"><center>Semester</center></th>
                        <th rowspan= "2" width="13%"><center>Tahun Akademik</center></th>
                        <th rowspan= "2"></th>
                        <th rowspan= "2"></th>
                        <th rowspan= "2"></th>
                        <th rowspan= "2"></th>
                    </tr>
                    <tr>
                        <th >Teori</th>
                        <th >Praktikum</th>
                      </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($makul as $item)
                        <tr>
                            <td><center>{{$no++}}</center></td>
                            <td><center>{{$item->kode}}</center></td>
                            <td>{{$item->makul}}</td>
                            <td><center>{{$item->akt_sks_teori}}</center></td>
                            <td><center>{{$item->akt_sks_praktek}}</center></td>
                            <td><center>{{$item->prodi}}</center></td>
                            <td><center>{{$item->kelas}}</center></td>
                            <td><center>{{$item->semester}}</center></td>
                            <td>{{$item->periode_tahun}} {{$item->periode_tipe}}</td>
                            <td><center>
                            <a href="cekmhs_dsn_his/{{$item->id_kurperiode}}" class="btn btn-info btn-xs" title="klik untuk cek mahasiswa"><i class="fa fa-users"></i></a>
                            </center></td>
                            <td><center>
                                <form action="{{url('export_xlsnilai')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                                    
                                    <button type="submit" class="btn btn-success btn-xs" title="klik untuk export excel"><i class="fa fa-file-excel-o"></i></button>
                                </form>
                            </center></td>
                            <td><center>
                                <form class="" action="{{url('unduh_pdf_nilai')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                                    <button type="submit" class="btn btn-danger btn-xs" title="klik untuk unduh ke pdf"><i class="fa fa-file-pdf-o"></i></button>
                                </form>
                            </center></td>
                            <td><center>
                                <a href="view_bap_his/{{$item->id_kurperiode}}" class="btn btn-warning btn-xs" title="klik untuk cek BAP"> <i class="fa fa-file-text"></i></a>
                               
                            </center></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
