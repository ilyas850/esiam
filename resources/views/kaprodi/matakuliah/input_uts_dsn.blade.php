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
            <li><a href="{{ url('makul_diampu_kprd') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/cekmhs_dsn_kprd/{{ $id }}"> Data List Mahasiswa</a></li>
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
            <form action="{{ url('save_nilai_UTS_kprd') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="4%">
                                    <center>No</center>
                                </th>
                                <th width="8%">
                                    <center>NIM </center>
                                </th>
                                <th width="20%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="8%">
                                    <center>Kelas</center>
                                </th>
                                <th width="8%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai UTS</center>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ck as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center> {{ $item->angkatan }}</center>
                                    </td>
                                    <td>
                                            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="text" name="nilai_UTS[]"
                                                    value="{{ $item->nilai_UTS != 0 ? $item->nilai_UTS : '' }}"
                                                    style="width: 60px; text-align: center;">
                                                @if ($item->absen_uts == null)
                                                    <span class="text-warning" style="position: absolute; left: calc(50% + 35px); font-size: 11px; white-space: nowrap; font-weight: bold;">⚠️ Belum Absen</span>
                                                @endif
                                            </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="id_makul" value="{{ $mkl }}">
                    <input type="hidden" name="id_prodi" value="{{ $kprd }}">
                    <input type="hidden" name="id_kelas" value="{{ $kkls }}">
                    <input type="hidden" name="id_kurperiode" value="{{ $kuri }}">
                    {{-- <input class="btn btn-info" type="submit" name="submit" value="Simpan" onclick="unbind()" > --}}
                    <button class="btn btn-info" type="submit">Simpan</button>
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
