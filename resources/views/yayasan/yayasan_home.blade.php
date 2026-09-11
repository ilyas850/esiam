@extends('layouts.master')

@section('side')
    @include('layouts.side_yayasan')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>Dashboard Yayasan</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-user-md"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dosen Tetap</span>
                        <span class="info-box-number">{{ $dosenTetap }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="{{ url('yayasan/dosen-tetap') }}" style="color: white;">Lihat Detail</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dosen Tidak Tetap</span>
                        <span class="info-box-number">{{ $dosenTidakTetap }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="{{ url('yayasan/dosen-tidak-tetap') }}" style="color: white;">Lihat Detail</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa Aktif</span>
                        <span class="info-box-number">{{ $mhsAktif }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="{{ url('yayasan/mahasiswa-aktif') }}" style="color: white;">Lihat Detail</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-user-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa Tidak Aktif</span>
                        <span class="info-box-number">{{ $mhsTidakAktif }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            <a href="{{ url('yayasan/mahasiswa-tidak-aktif') }}" style="color: white;">Lihat Detail</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahun Akademik Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Tahun Akademik Aktif</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">Tahun Akademik</th>
                                <td>{{ $ta->periode_tahun ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="label label-success">{{ $ta->status ?? '-' }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection