@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Persentase Kehadiran Mahasiswa</b></h3>
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $mk->kode }} - {{ $mk->makul }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mk->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mk->kelas }}</td>
                    </tr>
                </table>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Kelas</th>
                                <th>
                                    Angkatan
                                </th>
                                <th>
                                    <center>Pertemuan</center>
                                </th>
                                <th>
                                    <center>Persentase</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>{{ $item->angkatan }}</td>
                                    <td align="center">{{ $item->jml }} / {{ $item->total }}</td>
                                    <td align="center">
                                        @if ($item->persentase_mhs <= 60)
                                            <span class="label label-danger">
                                                {{ $item->persentase_mhs }} %
                                            </span>
                                        @elseif ($item->persentase_mhs <= 84)
                                            <span class="label label-warning">
                                                {{ $item->persentase_mhs }} %
                                            </span>
                                        @elseif ($item->persentase_mhs >= 85 && $item->persentase_mhs < 100)
                                            <span class="label label-success">
                                                {{ $item->persentase_mhs }} %
                                            </span>
                                        @elseif ($item->persentase_mhs == 100)
                                            <span class="label label-info">
                                                {{ $item->persentase_mhs }} %
                                            </span>
                                        @endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
