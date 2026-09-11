@extends('layouts.master')

@section('side')
    @include('layouts.side_yayasan')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>Data Mahasiswa Aktif</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('yayasan_home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Mahasiswa Aktif</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <!-- Summary by Prodi -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Ringkasan Per Program Studi</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @foreach($countByProdi as $prodi)
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $prodi->nama_prodi ?? 'Tidak Diketahui' }}</span>
                                            <span class="info-box-number">{{ $prodi->total }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Daftar Mahasiswa Aktif</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Prodi</th>
                                        <th>Angkatan</th>
                                        <th>Email</th>
                                        <th>HP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswa as $index => $mhs)
                                        <tr>
                                            <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $index + 1 }}</td>
                                            <td>{{ $mhs->nim }}</td>
                                            <td>{{ $mhs->nama }}</td>
                                            <td>{{ $mhs->nama_prodi }}{{ $mhs->konsentrasi ? ' - ' . $mhs->konsentrasi : '' }}</td>
                                            <td>{{ $mhs->tahun_angkatan }}</td>
                                            <td>{{ $mhs->email }}</td>
                                            <td>{{ $mhs->hp }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            {{ $mahasiswa->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection