@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Detail Pengalaman Kerja Mahasiswa Politeknik META Industri</b></h3>
                <table width="100%">
                    <tr>
                        <td width="10%">Nama</td>
                        <td width="1%">:</td>
                        <td>{{ $mhs->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td> : </td>
                        <td>{{ $mhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>
                            <center> No </center>
                        </th>
                        <th>
                            <center>Nama Perusahaan/Instansi</center>
                        </th>
                        <th>
                            <center>Posisi/Jabatan</center>
                        </th>
                        <th>
                            <center>Tahun Masuk</center>
                        </th>
                        <th>
                            <center>Tahun Keluar</center>
                        </th>
                    </tr>
                    <?php $no = 1; ?>
                    @foreach ($data as $item)
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            <td>{{ $item->nama_pt }}</td>
                            <td align="center">{{ $item->posisi }}</td>
                            <td align="center">{{ $item->tahun_masuk }}</td>
                            <td align="center">{{ $item->tahun_keluar }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
