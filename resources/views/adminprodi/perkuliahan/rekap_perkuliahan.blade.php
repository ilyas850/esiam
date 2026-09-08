@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <style>
        .info-box {
            min-height: 80px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .info-box-icon {
            height: 80px;
            line-height: 80px;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .info-box-content {
            padding: 10px;
            margin-left: 80px;
        }

        .info-box-number {
            font-size: 22px;
            font-weight: 700;
        }

        .table-hover>tbody>tr:hover {
            background-color: #f5f9fc !important;
        }

        .select2-container .select2-selection--single {
            height: 34px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }

        .badge-sks {
            font-size: 11px;
            padding: 4px 7px;
            border-radius: 3px;
        }

        .bg-purple {
            background-color: #605ca8 !important;
            color: #fff !important;
        }

        .btn-action-group .btn {
            margin-bottom: 5px;
        }

        /* Print styling */
        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }

            .main-header,
            .main-sidebar,
            .content-header,
            .box-filter-panel,
            .btn,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_paginate,
            .dataTables_info,
            .box-tools,
            .main-footer {
                display: none !important;
            }

            .content-wrapper,
            .content {
                margin: 0 !important;
                padding: 0 !important;
                background-color: #fff !important;
            }

            .box {
                border: none !important;
                box-shadow: none !important;
            }

            .print-header {
                display: block !important;
                margin-bottom: 20px;
                text-align: center;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 9pt !important;
            }

            table th,
            table td {
                border: 1px solid #333 !important;
                padding: 4px 6px !important;
            }
        }

        .print-header {
            display: none;
        }
    </style>

    {{-- Content Header --}}
    <section class="content-header">
        <h1>
            Rekapitulasi Perkuliahan & BAP
            <small>{{ $namaperiodetahun }} - {{ $namaperiodetipe }} ({{ $namaprodi }})</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li class="active"><i class="fa fa-file-text-o"></i> Rekap Perkuliahan Prodi</li>
        </ol>
    </section>

    {{-- Main Content --}}
    <section class="content">

        {{-- Info Box Ringkasan Statistik --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Kelas Kuliah</span>
                        <span class="info-box-number">{{ number_format($stats['total_kelas'] ?? count($data)) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Target Tercapai (≥16)</span>
                        <span class="info-box-number">{{ number_format($stats['tercapai'] ?? 0) }} <small style="font-size: 13px; font-weight: normal; color: #555;">Kelas</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Belum Tercapai (&lt;16)</span>
                        <span class="info-box-number">{{ number_format($stats['belum_tercapai'] ?? 0) }} <small style="font-size: 13px; font-weight: normal; color: #555;">Kelas</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" title="Online: {{ $stats['total_online'] ?? 0 }} sesi | Offline: {{ $stats['total_offline'] ?? 0 }} sesi">
                    <span class="info-box-icon bg-purple"><i class="fa fa-calendar-check-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sesi Terlaksana</span>
                        <span class="info-box-number">{{ number_format($stats['total_sesi'] ?? 0) }} <small style="font-size: 13px; font-weight: normal; color: #555;">Pertemuan</small></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Filter & Aksi --}}
        <div class="box box-primary box-filter-panel">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode Tahun Akademik & Semester</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ url('rekap_perkuliahan_prodi') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-calendar"></i> Periode Tahun</label>
                                <select class="form-control select2" name="id_periodetahun" style="width: 100%;" required>
                                    @foreach ($tahun as $thn)
                                        <option value="{{ $thn->id_periodetahun }}" {{ ($id_periodetahun == $thn->id_periodetahun || (isset($idtahun) && $idtahun == $thn->id_periodetahun)) ? 'selected' : '' }}>
                                            {{ $thn->periode_tahun }} {{ $thn->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o"></i> Semester</label>
                                <select class="form-control select2" name="id_periodetipe" style="width: 100%;" required>
                                    @foreach ($tipe as $tipee)
                                        <option value="{{ $tipee->id_periodetipe }}" {{ ($id_periodetipe == $tipee->id_periodetipe || (isset($idtipe) && $idtipe == $tipee->id_periodetipe)) ? 'selected' : '' }}>
                                            {{ $tipee->periode_tipe }} {{ $tipee->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label class="hidden-xs" style="display: block;">&nbsp;</label>
                            <div class="btn-action-group">
                                <button type="submit" class="btn btn-primary" title="Terapkan Filter">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                                <a href="{{ url('rekap_perkuliahan_prodi') }}" class="btn btn-default" title="Reset ke Periode Aktif">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                                <a href="{{ url('export_rekap_perkuliahan_prodi') }}?id_periodetahun={{ $id_periodetahun ?? $idtahun }}&id_periodetipe={{ $id_periodetipe ?? $idtipe }}"
                                    class="btn btn-success" title="Ekspor ke Excel">
                                    <i class="fa fa-file-excel-o"></i> Excel
                                </a>
                                <button type="button" onclick="window.print()" class="btn btn-info" title="Cetak Lembar Rekap Perkuliahan">
                                    <i class="fa fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Print Header (Hanya tampil saat dicetak) --}}
        <div class="print-header">
            <h3 style="margin: 0; font-weight: bold;">REKAPITULASI PERKULIAHAN & BAP</h3>
            <h4 style="margin: 5px 0;">TAHUN AKADEMIK {{ strtoupper($namaperiodetahun) }} - {{ strtoupper($namaperiodetipe) }}</h4>
            <p style="margin: 0; font-size: 11pt;">Program Studi: <strong>{{ $namaprodi }}</strong></p>
            <hr style="margin: 10px 0; border-top: 1px solid #000;">
        </div>

        {{-- Data Table Section --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-table"></i> Rekap Perkuliahan 
                    <span class="label label-info" style="font-size: 13px; margin-left: 5px;">{{ $namaperiodetahun }} - {{ $namaperiodetipe }}</span>
                    <span class="label label-primary" style="font-size: 13px; margin-left: 3px;">{{ $namaprodi }}</span>
                </h3>
                <div class="box-tools pull-right">
                    <span class="badge bg-green" style="font-size: 12px; padding: 5px 10px;">{{ count($data) }} Kelas Terdaftar</span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="example8" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr style="background-color: #f7f9fa;">
                            <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                            <th style="min-width: 220px; vertical-align: middle;">Kode / Mata Kuliah</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">SKS</th>
                            <th style="min-width: 140px; vertical-align: middle;">Program Studi</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="min-width: 180px; vertical-align: middle;">Dosen</th>
                            <th style="min-width: 140px; vertical-align: middle;">Jumlah Pertemuan</th>
                            <th style="width: 120px; text-align: center; vertical-align: middle;">Online / Offline</th>
                            <th style="width: 90px; text-align: center; vertical-align: middle;">Status</th>
                            <th style="width: 70px; text-align: center; vertical-align: middle;">BAP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse ($data as $key)
                            @php
                                $jmlPer = $key->jml_per ?? 0;
                                $jmlOnline = $key->jml_online ?? 0;
                                $jmlOffline = $key->jml_offline ?? 0;
                                $percentage = min(($jmlPer / 16) * 100, 100);
                                $tercapai = $jmlPer >= 16;

                                if ($percentage >= 100) {
                                    $progressColor = 'progress-bar-success';
                                    $badgeProgress = 'badge bg-green';
                                } elseif ($percentage >= 75) {
                                    $progressColor = 'progress-bar-info';
                                    $badgeProgress = 'badge bg-aqua';
                                } elseif ($percentage >= 50) {
                                    $progressColor = 'progress-bar-warning';
                                    $badgeProgress = 'badge bg-yellow';
                                } else {
                                    $progressColor = 'progress-bar-danger';
                                    $badgeProgress = 'badge bg-red';
                                }

                                $kode = $key->kode ?? '';
                                $namaMk = $key->nama_makul ?? $key->makul ?? '-';
                                $konsentrasi = $key->daftar_konsentrasi ?? '';
                            @endphp
                            <tr>
                                <td align="center" style="vertical-align: middle;">{{ $no++ }}</td>
                                <td style="vertical-align: middle;">
                                    @if (!empty($kode))
                                        <span class="label label-default" style="font-size: 10px; letter-spacing: 0.5px;">{{ $kode }}</span>
                                    @endif
                                    <div style="font-weight: 600; margin-top: 3px; font-size: 13px; color: #2c3e50;">
                                        {{ $namaMk }}
                                    </div>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="badge bg-light-blue badge-sks">
                                        {{ $key->sks ?? '-' }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $key->prodi ?? '-' }}</strong>
                                    @if (!empty($konsentrasi))
                                        <br><small class="text-muted"><i class="fa fa-tags"></i> {{ $konsentrasi }}</small>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info" style="font-size: 11px;">
                                        {{ $key->kelas ?? '-' }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    @if (!empty($key->nama) && $key->nama !== 'Belum Ditentukan')
                                        <i class="fa fa-user-circle text-primary" style="margin-right: 4px;"></i>
                                        <strong>{{ $key->nama }}</strong>
                                    @else
                                        <span class="text-muted" style="font-style: italic;">
                                            <i class="fa fa-question-circle text-muted"></i> Belum Ditentukan
                                        </span>
                                    @endif
                                </td>
                                <td style="vertical-align: middle;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                                        <small style="font-weight: 600;">{{ $jmlPer }} / 16 Sesi</small>
                                        <span class="{{ $badgeProgress }}" style="font-size: 10px; padding: 2px 5px;">{{ round($percentage) }}%</span>
                                    </div>
                                    <div class="progress progress-xs" style="margin-bottom: 0; background-color: #e9ecef; border-radius: 3px;">
                                        <div class="progress-bar {{ $progressColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info" style="padding: 3px 6px; font-size: 11px;" title="Online">
                                        <i class="fa fa-wifi"></i> {{ $jmlOnline }}
                                    </span>
                                    <span class="label label-success" style="padding: 3px 6px; font-size: 11px;" title="Offline / Tatap Muka">
                                        <i class="fa fa-users"></i> {{ $jmlOffline }}
                                    </span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    @if ($tercapai)
                                        <span class="label label-success" style="padding: 4px 7px; font-size: 11px;">
                                            <i class="fa fa-check"></i> Tercapai
                                        </span>
                                    @else
                                        <span class="label label-danger" style="padding: 4px 7px; font-size: 11px;">
                                            <i class="fa fa-clock-o"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <a href="{{ url('cek_rekapan_prodi/' . $key->id_kurperiode) }}" class="btn btn-primary btn-xs" title="Lihat Detail BAP Pertemuan">
                                        <i class="fa fa-eye"></i> Cek
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-file-text-o fa-2x" style="margin-bottom: 10px; color: #bbb;"></i><br>
                                    <span style="font-size: 14px;">Belum ada data rekap perkuliahan pada periode ini.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
