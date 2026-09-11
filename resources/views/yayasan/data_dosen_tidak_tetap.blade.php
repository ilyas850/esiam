@extends('layouts.master')

@section('side')
    @include('layouts.side_yayasan')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>Data Dosen Tidak Tetap</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('yayasan_home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dosen Tidak Tetap</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Daftar Dosen Tidak Tetap
                            ({{ count($dosenWithMakul) }} orang)</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>NIK</th>
                                        <th>Nama Dosen</th>
                                        <th>Gelar</th>
                                        <th>Matakuliah {{ optional($ta)->periode_tahun ?? '' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dosenWithMakul as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['dosen']->nik }}</td>
                                            <td>{{ $item['dosen']->nama }}</td>
                                            <td>{{ $item['dosen']->akademik }}</td>
                                            <td>
                                                @if(count($item['matakuliah']) > 0)
                                                    <ul style="margin: 0; padding-left: 20px;">
                                                        @foreach($item['matakuliah'] as $mk)
                                                            <li>{{ $mk['namakul'] }} ({{ $mk['sks_teori'] + $mk['sks_praktek'] }} SKS) -
                                                                Kelas: {{ $mk['kelas'] }} | Prodi: {{ $mk['prodi'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="label label-default">Tidak ada matakuliah</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection