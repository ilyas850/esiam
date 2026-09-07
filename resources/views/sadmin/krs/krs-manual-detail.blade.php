@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header hidden-print">
        <h1>
            <i class="fa fa-file-text-o"></i> Detail Kartu Rencana Studi (KRS)
            <small>{{ $tahunActive->periode_tahun ?? '-' }} ({{ $tipeActive->periode_tipe ?? '-' }})</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="{{ url('krs-manual') }}">KRS Manual</a></li>
            <li class="active">Detail KRS</li>
        </ol>
    </section>

    <section class="content">
        {{-- Print Header (Only visible when printing) --}}
        <div class="visible-print-block print-header" style="margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
            <div class="row">
                <div class="col-xs-2 text-center">
                    <img src="{{ asset('images/Logo Meta.png') }}" style="max-height: 70px;" alt="Logo">
                </div>
                <div class="col-xs-10">
                    <h3 style="margin: 0; font-weight: bold; text-transform: uppercase;">Politeknik META Industri Cikarang</h3>
                    <p style="margin: 3px 0 0 0; font-size: 13px;">Jl. Raya Cikarang Cibarusah No. 88, Cikarang, Bekasi, Jawa Barat</p>
                    <h4 style="margin: 6px 0 0 0; font-weight: bold; letter-spacing: 1px;">KARTU RENCANA STUDI (KRS)</h4>
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="row hidden-print" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <div class="pull-left">
                    <a href="{{ url('krs-manual') }}" class="btn btn-default btn-flat">
                        <i class="fa fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-success btn-flat" onclick="window.print()">
                        <i class="fa fa-print"></i> Cetak KRS
                    </button>
                    <a href="{{ url('krs-manual/create/' . $dataMhs->idstudent) }}" class="btn btn-primary btn-flat">
                        <i class="fa fa-pencil"></i> Kelola / Tambah KRS Manual
                    </a>
                </div>
            </div>
        </div>

        {{-- Profil Mahasiswa & Statistik SKS --}}
        <div class="row">
            {{-- Biodata Mahasiswa --}}
            <div class="col-md-7 col-sm-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Data Mahasiswa</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 32%;">NIM</th>
                                <td style="width: 3%;">:</td>
                                <td><span class="label label-default" style="font-size: 13px; font-weight: 600;">{{ $dataMhs->nim }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>:</td>
                                <td><strong>{{ $dataMhs->nama }}</strong></td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td>:</td>
                                <td>
                                    {{ $dataMhs->prodi }}
                                    @if (!empty($dataMhs->konsentrasi) && $dataMhs->konsentrasi != '-')
                                        <br><small class="text-muted"><i class="fa fa-tag"></i> {{ $dataMhs->konsentrasi }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Kelas / Angkatan</th>
                                <td>:</td>
                                <td>
                                    <span class="label label-info">{{ optional($dataMhs->kelas)->kelas ?? '-' }}</span>
                                    <span class="label label-primary">{{ optional($dataMhs->angkatan)->angkatan ?? '-' }} ({{ $dataMhs->intake == '1' ? 'Ganjil' : 'Genap' }})</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dosen Pembimbing</th>
                                <td>:</td>
                                <td>
                                    @if (!empty(optional(optional($dataMhs->dosenPembimbing)->dosen)->nama))
                                        <i class="fa fa-user-circle text-muted"></i> {{ $dataMhs->dosenPembimbing->dosen->nama }}
                                        @if(!empty($dataMhs->dosenPembimbing->dosen->akademik))
                                            , {{ $dataMhs->dosenPembimbing->dosen->akademik }}
                                        @endif
                                    @else
                                        <span class="text-muted">Belum Ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Mahasiswa</th>
                                <td>:</td>
                                <td>
                                    @if($dataMhs->active == 1)
                                        <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                                    @elseif($dataMhs->active == 5)
                                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Lulus / Pending</span>
                                    @else
                                        <span class="label label-danger">Non-Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Ringkasan SKS & Periode --}}
            <div class="col-md-5 col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Ringkasan KRS Periode Ini</h3>
                    </div>
                    <div class="box-body">
                        <div class="callout callout-info" style="margin-bottom: 15px; padding: 10px 15px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 15px;">
                                <i class="fa fa-calendar-check-o"></i> Periode Akademik Aktif
                            </h4>
                            <span style="font-size: 14px; font-weight: 600;">
                                {{ $tahunActive->periode_tahun ?? 'Tidak Aktif' }} - Semester {{ $tipeActive->periode_tipe ?? '-' }}
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="info-box bg-aqua" style="min-height: 75px; margin-bottom: 10px;">
                                    <span class="info-box-icon" style="height: 75px; line-height: 75px;"><i class="fa fa-book"></i></span>
                                    <div class="info-box-content" style="margin-left: 75px;">
                                        <span class="info-box-text">Total Matakuliah</span>
                                        <span class="info-box-number" style="font-size: 22px;">{{ $dataKrsMhs->count() }} <small style="color:#fff;">MK</small></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="info-box bg-green" style="min-height: 75px; margin-bottom: 10px;">
                                    <span class="info-box-icon" style="height: 75px; line-height: 75px;"><i class="fa fa-check-square-o"></i></span>
                                    <div class="info-box-content" style="margin-left: 75px;">
                                        <span class="info-box-text">Total SKS Diambil</span>
                                        <span class="info-box-number" style="font-size: 22px;">{{ $totalSks }} <small style="color:#fff;">SKS</small></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="well well-sm" style="margin-bottom: 0; background-color: #fcfcfc;">
                            <div class="row text-center">
                                <div class="col-xs-6" style="border-right: 1px solid #ddd;">
                                    <small class="text-muted text-uppercase">SKS Teori</small>
                                    <div style="font-size: 16px; font-weight: bold; color: #0073b7;">{{ $totalSksTeori }} SKS</div>
                                </div>
                                <div class="col-xs-6">
                                    <small class="text-muted text-uppercase">SKS Praktek</small>
                                    <div style="font-size: 16px; font-weight: bold; color: #00a65a;">{{ $totalSksPraktek }} SKS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Matakuliah yang Diambil --}}
        <div class="box box-solid box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt"></i> Daftar Mata Kuliah yang Diambil</h3>
                <div class="box-tools pull-right">
                    <span class="label label-primary" style="font-size: 12px;">{{ $dataKrsMhs->count() }} Mata Kuliah Terdaftar</span>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-gray-light">
                            <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                            <th style="width: 90px; text-align: center; vertical-align: middle;">Kode</th>
                            <th style="vertical-align: middle;">Nama Mata Kuliah</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">SKS</th>
                            <th style="width: 75px; text-align: center; vertical-align: middle;">Smt</th>
                            <th style="width: 90px; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="vertical-align: middle;">Jadwal & Ruang</th>
                            <th style="vertical-align: middle;">Dosen Pengampu</th>
                            <th style="width: 100px; text-align: center; vertical-align: middle;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataKrsMhs as $index => $item)
                            @php
                                $kurperiode = $item->kurperiode;
                                $makul = optional($kurperiode)->makul;
                                $sksTeori = $makul->akt_sks_teori ?? 0;
                                $sksPraktek = $makul->akt_sks_praktek ?? 0;
                                $sksTotalItem = $sksTeori + $sksPraktek;
                            @endphp
                            <tr>
                                <td align="center" style="vertical-align: middle;">{{ $index + 1 }}</td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-default" style="font-size: 11px;">{{ $makul->kode ?? '-' }}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $makul->makul ?? '-' }}</strong>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="badge bg-light-blue" style="font-size: 12px;">{{ $sksTotalItem }} SKS</span>
                                    <div style="font-size: 10px; color: #777; margin-top: 2px;">
                                        T:{{ $sksTeori }} | P:{{ $sksPraktek }}
                                    </div>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    {{ optional(optional($kurperiode)->semester)->semester ?? '-' }}
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info">{{ optional(optional($kurperiode)->kelas)->kelas ?? '-' }}</span>
                                </td>
                                <td style="vertical-align: middle;">
                                    @if(optional($kurperiode)->hari || optional($kurperiode)->jam || optional($kurperiode)->ruangan)
                                        <i class="fa fa-calendar text-muted"></i> {{ optional(optional($kurperiode)->hari)->hari ?? '-' }},
                                        <i class="fa fa-clock-o text-muted"></i> {{ optional(optional($kurperiode)->jam)->jam ?? '-' }}<br>
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> {{ optional(optional($kurperiode)->ruangan)->nama_ruangan ?? '-' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="vertical-align: middle;">
                                    @if(!empty(optional(optional($kurperiode)->dosen)->nama))
                                        <i class="fa fa-user text-muted"></i> {{ optional($kurperiode->dosen)->nama }}
                                        @if(!empty($kurperiode->dosen->akademik))
                                            , {{ $kurperiode->dosen->akademik }}
                                        @endif
                                    @else
                                        <span class="text-muted"><i class="fa fa-question-circle"></i> Belum Ditentukan</span>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    @if($item->remark == 1)
                                        <span class="label label-success"><i class="fa fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 35px 20px;">
                                    <i class="fa fa-info-circle fa-3x" style="margin-bottom: 10px; color: #bbb;"></i>
                                    <h4>Belum Ada Mata Kuliah yang Diambil</h4>
                                    <p class="text-muted">Mahasiswa ini belum memiliki mata kuliah yang terdaftar pada semester aktif.</p>
                                    <a href="{{ url('krs-manual/create/' . $dataMhs->idstudent) }}" class="btn btn-primary btn-sm btn-flat hidden-print">
                                        <i class="fa fa-plus"></i> Isi / Tambah KRS Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dataKrsMhs->isNotEmpty())
                        <tfoot>
                            <tr class="bg-gray-light" style="font-weight: bold;">
                                <td colspan="3" class="text-right" style="vertical-align: middle;">TOTAL SKS YANG DIAMBIL:</td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="badge bg-green" style="font-size: 13px;">{{ $totalSks }} SKS</span>
                                </td>
                                <td colspan="5" style="vertical-align: middle; font-size: 12px; color: #555;">
                                    (Teori: {{ $totalSksTeori }} SKS | Praktek: {{ $totalSksPraktek }} SKS)
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Signature Block for Print --}}
        <div class="visible-print-block" style="margin-top: 40px;">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <p>Mahasiswa,</p>
                    <br><br><br>
                    <p style="margin-bottom: 0;"><b><u>{{ $dataMhs->nama }}</u></b></p>
                    <p>NIM. {{ $dataMhs->nim }}</p>
                </div>
                <div class="col-xs-4 text-center">
                    <p>Dosen Pembimbing Akademik,</p>
                    <br><br><br>
                    <p style="margin-bottom: 0;"><b><u>{{ optional(optional($dataMhs->dosenPembimbing)->dosen)->nama ?? '....................................' }}</u></b></p>
                    <p>NIP/NIDN. {{ optional(optional($dataMhs->dosenPembimbing)->dosen)->nidn ?? '....................' }}</p>
                </div>
                <div class="col-xs-4 text-center">
                    <p>Cikarang, {{ date('d F Y') }}<br>Bagian Administrasi Akademik,</p>
                    <br><br><br>
                    <p style="margin-bottom: 0;"><b><u>BAAK Politeknik META</u></b></p>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <style>
        @media print {
            .main-header, .main-sidebar, .content-header, .main-footer, .hidden-print {
                display: none !important;
            }
            .content-wrapper, .right-side, .main-footer {
                margin-left: 0 !important;
                background-color: #fff !important;
            }
            .box {
                border: 1px solid #ccc !important;
                box-shadow: none !important;
            }
            body {
                font-size: 12px;
                background-color: #fff;
            }
            table {
                font-size: 11px;
            }
        }
    </style>
@endsection
