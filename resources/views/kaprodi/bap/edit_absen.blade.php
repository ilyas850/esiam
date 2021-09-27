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
        <li><a href="{{ url('makul_diampu_kprd') }}"> Data Matakuliah yang diampu</a></li>
        <li><a href="/entri_bap/{{$idk}}"> BAP</a></li>
        <li class="active">Edit Absensi Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Data Absensi Mahasiswa</h3>
        </div>
        <form action="{{url('save_edit_absensi_kprd')}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id_bap" value="{{$id}}">
            <div class="box-body">
                <div class="form-group">
                <div class="callout callout-warning">
                    <p>Remark : Centang untuk mahasiswa yang hadir</p>
                </div>
              </div>
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
                        @foreach ($abs as $item)
                            <tr>
                                <td><center>{{$no++}}</center></td>
                                <td><center>{{$item->nim}}</center></td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->prodi}}</td>
                                <td><center>{{$item->kelas}}</center></td>
                                <td><center>{{$item->angkatan}}</center></td>
                                <td><center>
                                    <input type="hidden" name="id_studentrecord[]" value="{{$item->id_studentrecord}}">
                                    @if ($item->absensi == 'HADIR')

                                        <input type="checkbox" name="absensi[]" value="{{$item->id_absensi}},ABSEN" >
                                    @elseif ($item->absensi == 'ABSEN')
                                        {{-- <input type="hidden" name="id_studentrecord[]" value="{{$item->id_studentrecord}}"> --}}
                                        <input type="checkbox" name="absensi[]" value="{{$item->id_absensi}},ABSEN" checked>
                                    @endif

                                </center></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button" class="btn btn-warning">
                <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button" class="btn btn-warning">
                <input class="btn btn-info full-right" type="submit" name="submit" value="Simpan">
            </div>
        </form>
    </div>
</section>
<script language="javascript">
    function check_all()
    {
        var chk = document.getElementsByName('absensi[]');
        for (i = 0; i < chk.length; i++)
        chk[i].checked = true ;
    }

    function uncheck_all()
    {
        var chk = document.getElementsByName('absensi[]');
        for (i = 0; i < chk.length; i++)
        chk[i].checked = false ;
    }
</script>
@endsection
