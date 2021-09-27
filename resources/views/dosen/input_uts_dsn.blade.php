@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
        Input Nilai UTS
        </h1>
        <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
        <li><a href="/cekmhs_dsn/{{$id}}"> Data List Mahasiswa</a></li>
        <li class="active">Data List Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Data List Mahasiswa</h3>
        </div>
        <form action="{{url('save_nilai_UTS_dsn')}}" method="post">
          {{ csrf_field() }}
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
                        <th><center>Nilai UTS</center></th>

                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($ck as $item)
                        <tr>
                            <td><center>{{$no++}}</center></td>
                            <td><center>{{$item->nim}}</center></td>
                            <td>{{$item->nama}}</td>
                            <td>
                                @foreach ($prd as $itemprd)
                                    @if ($item->kodeprodi == $itemprd->kodeprodi)
                                        {{$itemprd->prodi}}
                                    @endif
                                @endforeach
                            </td>
                            <td><center>
                                @foreach ($kls as $itemkls)
                                    @if ($item->idstatus == $itemkls->idkelas)
                                        {{$itemkls->kelas}}
                                    @endif
                                @endforeach
                            </center></td>
                            <td><center>
                                @foreach ($angk as $itemangk)
                                    @if ($item->idangkatan == $itemangk->idangkatan)
                                        {{$itemangk->angkatan}}
                                    @endif
                                @endforeach
                            </center></td>
                            <td><center>
                              @if ($item->nilai_UTS == 0)
                              <input type="hidden" name="id_student[]" value="{{$item->id_student}},{{$item->id_kurtrans}}">
                                <input type="hidden" name="id_studentrecord[]" value="{{$item->id_studentrecord}}">
                                <input type="text" name="nilai_UTS[]">
                              @elseif ($item->nilai_UTS != 0)
                              <input type="hidden" name="id_student[]" value="{{$item->id_student}},{{$item->id_kurtrans}}">
                                <input type="hidden" name="id_studentrecord[]" value="{{$item->id_studentrecord}}">
                                <input type="text" name="nilai_UTS[]" value="{{$item->nilai_UTS}}">
                              @endif
                            </center></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <input type="hidden" name="id_makul" value="{{$mkl}}">
            <input type="hidden" name="id_prodi" value="{{$kprd}}">
            <input type="hidden" name="id_kelas" value="{{$kkls}}">
            <input type="hidden" name="id_kurperiode" value="{{$kuri}}">
            {{-- <input class="btn btn-info" type="submit" name="submit" value="Simpan" onclick="unbind()" > --}}
            <button class="btn btn-info" type="submit" id="Btn" onclick="myFunction()">Simpan</button>
        </div>
        </form>
    </div>
</section>
{{-- <script type="text/javascript">
  $('# ').unbind().click(function(){})
</script> --}}
<script>
function myFunction() {
    var x = document.getElementById("Btn");
    x.disabled = true;
}
</script>
@endsection
