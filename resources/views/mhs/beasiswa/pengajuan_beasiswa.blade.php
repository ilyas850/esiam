@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Pengajuan Beasiswa</h3>
            </div>
            <div class="box-body">
                @if ($status_pengajuan->status == 0 or $status_pengajuan->status == null)
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Waktu Pengajuan Beasiswa Belum dibuka</p>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <div class="callout callout-info">
                            <p>{{ Carbon\Carbon::parse($status_pengajuan->waktu_awal)->formatLocalized('%d %B %Y') }} s/d
                                {{ Carbon\Carbon::parse($status_pengajuan->waktu_akhir)->formatLocalized('%d %B %Y') }}</p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2">

                            <a href="{{ url('pengajuan_beasiswa') }}" class="btn btn-success">Pengajuan Beasiswa</a>
                        </div>
                    </div>
                    <br>
                @endif

                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px" rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tahun Akademik</center>
                            </th>
                            <th rowspan="2">
                                <center>Semester</center>
                            </th>
                            <th rowspan="2">
                                <center>IPK</center>
                            </th>

                            <th colspan="2">
                                <center>Validasi</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>BAUK</center>
                            </th>
                            <th>
                                <center>Wadir 3</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
