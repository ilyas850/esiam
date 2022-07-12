@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
        Absensi Perkuliahan
        </h1>
        <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('history_makul_dsn') }}"> History Matakuliah yang diampu</a></li>
        <li><a href="/view_bap_his/{{$bap->id_kurperiode}}">History BAP</a></li>
        <li class="active">Absensi Perkuliahan </li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header with-border">
            <table width="100%">
                <tr>
                    <td>Matakuliah</td><td>:</td>
                    <td>{{$bap->makul}} - {{$bap->akt_sks}} SKS</td>
                    <td>Tahun Akademik</td><td>:</td>
                    <td>{{$bap->periode_tahun}} {{$bap->periode_tipe}}</td>
                </tr>
                <tr>
                    <td>Waktu / Ruangan</td><td>:</td>
                    <td>{{$bap->hari}}, 
                        @if ($bap->id_kelas == 1)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 50) + (60*$bap->akt_sks_praktek * 120))}}
                        @elseif ($bap->id_kelas == 2)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))}}
                        @elseif ($bap->id_kelas == 3)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))}}
                        @endif
                    / {{$bap->nama_ruangan}}</td>
                    <td>Program Studi</td><td>:</td>
                    <td>{{$bap->prodi}}</td>
                </tr>
                <tr>
                    <td>Dosen</td><td>:</td>
                    <td>{{$bap->nama}}, {{$bap->akademik}}</td>
                    <td>Kelas</td><td>:</td>
                    <td>{{$bap->kelas}}</td>
                </tr>  
            </table>
        </div>
            <div class="box-body">
                <a href="/print_absensi/{{$bap->id_kurperiode}}" class="btn btn-success" target="_blank">Print</a>
                <br><br>
                <table class="table table-bordered">
                     <thead>
                        <tr>
                            <th ><center>No</center></th>
                            <th ><center>NIM </center></th>
                            <th ><center>Nama</center></th>
                            <th ><center>1</center></th>
                            <th ><center>2</center></th>
                            <th ><center>3</center></th>
                            <th ><center>4</center></th>
                            <th ><center>5</center></th>
                            <th ><center>6</center></th>
                            <th ><center>7</center></th>
                            <th ><center>8</center></th>
                            <th ><center>9</center></th>
                            <th ><center>10</center></th>
                            <th ><center>11</center></th>
                            <th ><center>12</center></th>
                            <th ><center>13</center></th>
                            <th ><center>14</center></th>
                            <th ><center>15</center></th>
                            <th ><center>16</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; ?>
                        
                        @foreach ($abs as $itembs)
                        
                            <tr>
                                <td><center>{{$no++}}</center></td>
                                <td><center>{{$itembs->nim}}</center></td>
                                <td>{{$itembs->nama}}</td>
                                <td><center>
                                    @foreach ($abs1 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs2 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs3 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs4 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs5 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs6 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs7 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs8 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs9 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs10 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs11 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs12 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs13 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs14 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs15 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                                <td><center>
                                    @foreach ($abs16 as $item1)
                                        @if ($itembs->id_studentrecord == $item1->id_studentrecord)
                                            @if ($item1->absensi == 'ABSEN')
                                                (&#10003;)
                                            @elseif ($item1->absensi == 'HADIR')
                                                (x)
                                            @endif
                                        @endif
                                    @endforeach        
                                </center></td>
                            </tr>
                             
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</section>
@endsection