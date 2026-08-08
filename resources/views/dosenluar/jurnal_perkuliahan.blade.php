@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Jurnal Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/entri_bap_dsn/{{ $bap->id_kurperiode }}"> BAP</a></li>
            <li class="active">Jurnal Perkuliahan</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <!-- Info Matakuliah Card (Native AdminLTE box-primary) -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle text-primary"></i> Informasi Matakuliah</h3>
                <div class="box-tools pull-right">
                    <a href="/entri_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali ke BAP
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <table class="table table-condensed table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 35%;"><i class="fa fa-book text-muted"></i> Matakuliah</th>
                                <td style="width: 5%;">:</td>
                                <td><strong>{{ $bap->makul }}</strong> &nbsp; <span class="label label-primary">{{ $bap->akt_sks }} SKS</span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-graduation-cap text-muted"></i> Program Studi</th>
                                <td>:</td>
                                <td>{{ $bap->prodi }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-users text-muted"></i> Kelas</th>
                                <td>:</td>
                                <td><span class="label label-default">{{ $bap->kelas }}</span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-user-md text-muted"></i> Dosen Pengampu</th>
                                <td>:</td>
                                <td>{{ $bap->nama }}, {{ $bap->akademik }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <table class="table table-condensed table-borderless" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 35%;"><i class="fa fa-calendar text-muted"></i> Tahun Akademik</th>
                                <td style="width: 5%;">:</td>
                                <td><span class="label label-info">{{ $bap->periode_tahun }} {{ $bap->periode_tipe }}</span></td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-clock-o text-muted"></i> Waktu Kuliah</th>
                                <td>:</td>
                                <td>
                                    {{ $bap->hari }},
                                    @if ($bap->id_kelas == 1)
                                        {{ date('H:i', strtotime($bap->jam)) }} - {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120) }}
                                    @elseif ($bap->id_kelas == 2 || $bap->id_kelas == 3)
                                        {{ date('H:i', strtotime($bap->jam)) }} - {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fa fa-building text-muted"></i> Ruangan</th>
                                <td>:</td>
                                <td>{{ $bap->nama_ruangan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Jurnal Perkuliahan (Native AdminLTE box-info) -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt text-info"></i> Daftar Jurnal Perkuliahan</h3>
                <div class="box-tools pull-right">
                    <a href="/print_jurnal_dsn/{{ $bap->id_kurperiode }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a href="/download_jurnal_dsn/{{ $bap->id_kurperiode }}" class="btn btn-info btn-sm">
                        <i class="fa fa-download"></i> Download
                    </a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-gray" style="font-weight: 600;">
                            <th class="text-center" style="width: 50px;">No</th>
                            <th class="text-center" style="width: 130px;">Tanggal</th>
                            <th class="text-center" style="width: 140px;">Jam</th>
                            <th>Materi</th>
                            <th class="text-center" style="width: 130px;">Paraf Dosen</th>
                            <th class="text-center" style="width: 120px;">Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    {{ $item->tanggal ? date('Y-m-d', strtotime($item->tanggal)) : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : '' }} - {{ $item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : '' }}
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td class="text-center">By System</td>
                                <td class="text-center">
                                    @if ($item->tanggal_validasi == '2001-01-01')
                                        <span class="label label-danger">BELUM</span>
                                    @else
                                        <span class="label label-primary">SUDAH</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <em>Belum ada data perkuliahan.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
