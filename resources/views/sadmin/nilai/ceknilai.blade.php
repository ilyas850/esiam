@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Nilai Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('data_nilai') }}"> Data Nilai Mahasiswa</a></li>
            <li class="active">Cek Nilai</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mhs->prodi }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> {{ $mhs->nim }}</td>

                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}</td>
                    </tr>
                </table>
            </div>
            <form action="{{ url('save_nilai_angka') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" width="4px">
                                    <center>No</center>
                                </th>

                                <th rowspan="2">
                                    <center>Matakuliah</center>
                                </th>
                                <th colspan="2">
                                    <center>SKS</center>
                                </th>
                                <th rowspan="2">
                                    <center>Nilai Huruf</center>
                                </th>
                                <th rowspan="2">
                                    <center>Nilai Angka</center>
                                </th>
                                <th rowspan="2">
                                    <center>Pilih</center>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <center>Teori</center>
                                </th>
                                <th>
                                    <center>Praktek</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($cek as $key)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $key->makul }}</td>
                                    <td>
                                        <center>{{ $key->akt_sks_teori }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->akt_sks_praktek }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->nilai_AKHIR }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->nilai_ANGKA }}</center>
                                    </td>
                                    <td>
                                        @if ($key->nilai_ANGKA == null)
                                            <center><input type="checkbox" name="nilai_ANGKA[]"
                                                    value="{{ $key->id_studentrecord }},
                            @if ($key->nilai_AKHIR == 'A') 4
                            @elseif ($key->nilai_AKHIR == 'B+')
                              3.5
                            @elseif ($key->nilai_AKHIR == 'B')
                              3
                            @elseif ($key->nilai_AKHIR == 'C+')
                              2.5
                            @elseif ($key->nilai_AKHIR == 'C')
                              2
                            @elseif ($key->nilai_AKHIR == 'D')
                              1
                            @elseif ($key->nilai_AKHIR == 'E')
                              0 @endif">
                                            </center>
                                        @else
                                            <center>
                                                sudah
                                            </center>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <input type="hidden" name="id_student" value="{{ $key->id_student }}">
                    <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                        class="btn btn-warning">
                    <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                        class="btn btn-warning">
                    <input class="btn btn-info" type="submit" name="submit" value="Konversi">
                    <a class="btn btn-success" href="{{ url('data_nilai') }}">Kembali</a>
                </div>
            </form>
        </div>
    </section>
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('nilai_ANGKA[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('nilai_ANGKA[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
