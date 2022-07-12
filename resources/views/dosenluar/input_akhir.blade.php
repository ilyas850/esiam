@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Input Nilai AKHIR ANGKA
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/cekmhs/{{ $id }}"> Data List Mahasiswa</a></li>
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
            <form action="{{ url('save_nilai_AKHIR') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $kuri }}">
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
                                    <center>Nilai AKHIR ANGKA</center>
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
                                        <center>
                                            @if ($item->nilai_AKHIR_angka == 0)
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="text" name="nilai_AKHIR_angka[]">
                                            @elseif ($item->nilai_AKHIR_angka != 0)
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="text" name="nilai_AKHIR_angka[]"
                                                    value="{{ $item->nilai_AKHIR_angka }}">
                                            @endif
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>

                    <input class="btn btn-info" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
@endsection
