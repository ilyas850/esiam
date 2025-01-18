@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">History Perkuliahan Mahasiswa</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped" id="example5">
                <thead>
                    <tr>
                        <th>Tahun Akademik</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Matakuliah</th>
                        <th>Dosen</th>
                        <th><center>Kehadiran</center></th>
                        <th><center>Persentase</center></th>
                        <th><center>Action</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @foreach ($data as $item)
                    <tr>
                        
                        <td>{{$item->periode_tahun}}-{{$item->periode_tipe}}</td>
                        <td>{{ $item->hari }}</td>
                        <td>{{ $item->jam }}</td>
                        <td>{{ $item->makul }}</td>
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
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection