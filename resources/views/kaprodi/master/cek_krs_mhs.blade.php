@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $data_mhs->nama }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $data_mhs->prodi }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> {{ $data_mhs->nim }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $data_mhs->kelas }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box box-danger">
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS (T/P)</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->semester }}</td>
                                <td>{{ $item->kode }}/{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->akt_sks_teori }}/{{ $item->akt_sks_praktek }}</center>
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <center>
                                        @if ($item->remark == 1)
                                            <span class="badge bg-green">sudah</span>
                                        @elseif ($item->remark == 0)
                                            <span class="badge bg-red">belum</span>
                                        @endif
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
