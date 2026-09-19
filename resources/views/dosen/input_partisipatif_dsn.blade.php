@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Input Nilai Aktivitas Partisipatif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/cekmhs_dsn/{{ $id }}"> Data List Mahasiswa</a></li>
            <li class="active">Input Nilai Aktivitas Partisipatif</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Form Input Nilai Aktivitas Partisipatif</h3>
                <div class="box-tools pull-right">
                    <a href="/cekmhs_dsn/{{ $id }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <form action="{{ url('save_nilai_partisipatif_dsn') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $kuri }}">
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-gray-light">
                                <th width="4%" class="text-center">No</th>
                                <th width="12%" class="text-center">NIM</th>
                                <th width="25%">Nama Mahasiswa</th>
                                <th width="15%">Program Studi</th>
                                <th width="8%" class="text-center">Kelas</th>
                                <th width="8%" class="text-center">Angkatan</th>
                                <th width="15%" class="text-center">Nilai Aktivitas Partisipatif (0 - 100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ck as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $item->nim }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td class="text-center">{{ $item->kelas }}</td>
                                    <td class="text-center">{{ $item->angkatan }}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="id_student[]" value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                        <input type="hidden" name="id_studentrecord[]" value="{{ $item->id_studentrecord }}">
                                        <input type="number" step="any" min="0" max="100" class="form-control text-center input-sm" style="max-width: 110px; margin: 0 auto;" name="nilai_partisipatif[]" value="{{ $item->nilai_partisipatif ?? '' }}" placeholder="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan Nilai</button>
                    <a href="/cekmhs_dsn/{{ $id }}" class="btn btn-default btn-flat"><i class="fa fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </section>
@endsection
