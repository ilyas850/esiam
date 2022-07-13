@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data List Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
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
            <div class="box-body">
                {{-- <a href="/input_kat_dsn/{{$ids}}" class="btn btn-success btn-sm">Input Nilai KAT</a>
            <a href="/input_uts_dsn/{{$ids}}" class="btn btn-info btn-sm">Input Nilai UTS</a>
            <a href="/input_uas_dsn/{{$ids}}" class="btn btn-warning btn-sm">Input Nilai UAS</a>
            <a href="/input_akhir_dsn/{{$ids}}" class="btn btn-danger btn-sm">Input Nilai AKHIR</a> --}}
                {{-- <br><br> --}}
                <table id="example1" class="table table-bordered table-striped">
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
                                <center>Nilai KAT</center>
                            </th>
                            <th>
                                <center>Nilai UTS</center>
                            </th>
                            <th>
                                <center>Nilai UAS</center>
                            </th>
                            <th>
                                <center>Nilai AKHIR</center>
                            </th>
                            <th>
                                <center>Nilai HURUF</center>
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
                                <td> {{ $item->prodi }} </td>
                                <td>
                                    <center> {{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center> {{ $item->angkatan }} </center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_KAT }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UTS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UAS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR_angka }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
