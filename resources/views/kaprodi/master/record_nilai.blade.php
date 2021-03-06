@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Record Nilai Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('mhs_bim_kprd') }}"> Data mahasiswa bimbingan</a></li>
            <li class="active">Data record nilai mahasiswa</li>
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
                        <td>{{ $key->nama }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $key->prodi }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> {{ $key->nim }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $key->kelas }}</td>

                    </tr>
                </table>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Nilai Huruf</center>
                            </th>
                            <th>
                                <center>Nilai Angka</center>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($cek as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <center>{{ $key->kode }}</center>
                                </td>
                                <td>{{ $key->makul }}</td>
                                <td>
                                    <center>{{ $key->nilai_AKHIR }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_ANGKA }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </section>
@endsection
