@extends('layouts.master')

@section('side')
    @include('layouts.side')
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
            <div class="box-header">
                <h3 class="box-title">Rekapan Nilai Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS</center>
                            </th>
                            <th>
                                <center>Nilai Akhir</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->makul }}</td>
                                <td align="center">{{ $item->sks }}</td>
                                <td align="center">{{ $item->nilai_AKHIR }}</td>
                                <td align="center">{{ $item->semester }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
