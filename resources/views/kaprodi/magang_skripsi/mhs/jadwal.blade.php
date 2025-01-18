@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Jadwal Kuliah</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                    placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Matakuliah</th>
                                <th>Ruangan</th>
                                <th>Dosen</th>
                                <th>Kehadiran</th>
                                <th>Persentase Absen</th>
                                <th>Cek Absen</th>
                            </tr>

                            <?php $no = 1; ?>
                            @foreach ($jadwal as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->hari }}</td>
                                    <td>{{ $item->jam }}</td>
                                    <td>{{ $item->makul }}</td>
                                    <td>{{ $item->nama_ruangan }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td align="center">{{ $item->jml }} / {{ $item->total }}</td>
                                    <td align="center">
                                        @if ($item->persentase <= 60)
                                            <span class="label label-danger">
                                                {{ $item->persentase }} %
                                            </span>
                                        @elseif ($item->persentase <= 84)
                                            <span class="label label-warning">
                                                {{ $item->persentase }} %
                                            </span>
                                        @elseif ($item->persentase >= 85 && $item->persentase < 100)
                                            <span class="label label-success">
                                                {{ $item->persentase }} %
                                            </span>
                                        @elseif ($item->persentase == 100)
                                            <span class="label label-info">
                                                {{ $item->persentase }} %
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <center>
                                            <a href="/lihatabsen/{{ $item->id_kurperiode }}"
                                                class="btn btn-info btn-xs">Lihat</a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
