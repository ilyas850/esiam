@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
        Data Matakuliah yang diampu
        </h1>
        <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li class="active">Data Matakuliah yang diampu</li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Data Matakuliah</h3>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="4%"><center>No</center></th>
                        <th width="8%"><center>Kode </center></th>
                        <th width="20%"><center>Matakuliah</center></th>
                        <th width="15%"><center>Program Studi</center></th>
                        <th width="10%"><center>Kelas</center></th>
                        <th width="10%"><center>Semester</center></th>
                        <th width="10%"><center>Jadwal</center></th>
                        <th width="10%"><center>Id Absen</center></th>
                        <th width="8%"></th>
                        <th width="8%"></th>
                        <th width="8%"></th>
                        <th width="8%"></th>
                      </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($makul as $item)
                        <tr>
                            <td><center>{{$no++}}</center></td>
                            <td><center>{{$item->kode}}</center></td>
                            <td>{{$item->makul}}</td>
                            <td><center>{{$item->prodi}}</center></td>
                            <td><center>{{$item->kelas}}</center></td>
                            <td><center>{{$item->semester}}</center></td>
                            <td>{{$item->hari}}, {{$item->jam}}</td>
                            <td>{{$item->id_kurperiode}}</td>
                            <td><center>
                            <a href="cekmhs_dsn/{{$item->id_kurperiode}}" class="btn btn-info btn-xs">Entri Nilai</a>
                            </center></td>
                            <td><center>
                                <a href="entri_bap/{{$item->id_kurperiode}}" class="btn btn-warning btn-xs"> Entri BAP</a>
                            </center></td>
                            <td><center>
                                <form action="{{url('export_xlsnilai')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                                    <input class="btn btn-success btn-xs" type="submit" name="submit" value="Export Excel">
                                </form>
                            </center></td>
                            <td><center>
                                <form class="" action="{{url('unduh_pdf_nilai')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_kurperiode" value="{{$item->id_kurperiode}}">
                                    <button type="submit" class="btn btn-danger btn-xs" >Unduh Nilai</button>
                                </form>
                            </center></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
