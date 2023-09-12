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
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>No. HP</center>
                            </th>
                            <th>
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
                                <td align="center">
                                    {{ $key->hp }}
                                </td>
                                <td>
                                    <center>
                                        {{-- @if ($key->id_krs == 0)
                                            BELUM ADA
                                        @else
                                            {{ $key->thn_krs }} - {{ $key->periode_tipe }}
                                        @endif --}}

                                        {{-- {{ substr($key->tanggal_krs, 0, 4) }} - {{ $key->periode_tipe }} --}}

                                        {{ $key->periode_tahun }} - {{ $key->periode_tipe }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a class="btn btn-info btn-xs" href="/record_nilai/{{ $key->idstudent }}"> <i
                                                class="fa fa-list" title="Klik untuk cek nilai"></i></a>
                                        <a class="btn btn-warning btn-xs"
                                            href="/record_pembayaran_mhs/{{ $key->idstudent }}"><i class="fa fa-money"
                                                title="Klik untuk cek pembayaran"></i></a>
                                        <a class="btn btn-danger btn-xs"
                                            href="/cek_makul_mengulang/{{ $key->idstudent }}"><i class="fa fa-repeat"
                                                title="Klik untuk cek matakuliah mengulang"></i></a>
                                        <a class="btn btn-success btn-xs"
                                            href="/cek_bim_perwalian/{{ $key->idstudent }}"><i class="fa fa-wechat"
                                                title="Klik untuk cek bimbingan perwalian"></i></a>
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
