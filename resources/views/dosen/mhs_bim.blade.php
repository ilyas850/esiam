@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Mahasiswa Bimbingan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data mahasiswa bimbingan</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa bimbingan</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="10%">
                                <center>NIM</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th width="10%">
                                <center>Angkatan</center>
                            </th>
                            <th width="15%">
                                <center>KRS Terakhir</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($mhs as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->prodi }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->angkatan }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ substr($key->tanggal_krs, 0, 4) }} - {{ $key->periode_tipe }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a class="btn btn-info btn-xs" href="/record_nilai/{{ $key->idstudent }}">Cek
                                            Nilai</a>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
