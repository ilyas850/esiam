{{-- 
    Halaman Jadwal Perkuliahan (Super Admin)
    Update UI/UX: AdminLTE Info-Box Summary, Grouping Multi-Konsentrasi, Filter Prodi/Tahun/Semester, Export Excel, & Print Layout.
--}}
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

        .badge-hari {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
            letter-spacing: 0.3px;
        }

        .bg-purple {
            background-color: #605ca8 !important;
            color: #fff !important;
        }

        .bg-navy {
            background-color: #001f3f !important;
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
                font-size: 10pt !important;
            }

            table th,
            table td {
                border: 1px solid #333 !important;
                padding: 5px !important;
            }
        }

        .print-header {
            display: none;
        }
    </style>

    {{-- Content Header --}}
    <section class="content-header">
        <h1>
            Jadwal Perkuliahan
            <small>{{ $namaperiodetahun }} - {{ $namaperiodetipe }} ({{ $namaprodi }})</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Jadwal Perkuliahan</li>
        </ol>
    </section>

    {{-- Main Content --}}
    <section class="content">

        {{-- Info Box Ringkasan Statistik --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Jadwal Kelas</span>
                        <span class="info-box-number">{{ number_format($stats['total_jadwal']) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total SKS Terjadwal</span>
                        <span class="info-box-number">{{ number_format($stats['total_sks']) }} <small style="font-size: 14px; font-weight: normal; color: #555;">SKS</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dosen Mengajar</span>
                        <span class="info-box-number">{{ number_format($stats['total_dosen']) }} <small style="font-size: 14px; font-weight: normal; color: #555;">Dosen</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-building-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ruangan Digunakan</span>
                        <span class="info-box-number">{{ number_format($stats['total_ruangan']) }} <small style="font-size: 14px; font-weight: normal; color: #555;">Ruang</small></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Filter & Aksi --}}
        <div class="box box-primary box-filter-panel">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter & Pengaturan Jadwal</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ url('jadwal_perkuliahan') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-calendar"></i> Periode Tahun</label>
                                <select class="form-control select2" name="id_periodetahun" style="width: 100%;" required>
                                    @foreach ($tahun as $thn)
                                        <option value="{{ $thn->id_periodetahun }}" {{ $id_periodetahun == $thn->id_periodetahun ? 'selected' : '' }}>
                                            {{ $thn->periode_tahun }} {{ $thn->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o"></i> Semester</label>
                                <select class="form-control select2" name="id_periodetipe" style="width: 100%;" required>
                                    @foreach ($tipe as $tipee)
                                        <option value="{{ $tipee->id_periodetipe }}" {{ $id_periodetipe == $tipee->id_periodetipe ? 'selected' : '' }}>
                                            {{ $tipee->periode_tipe }} {{ $tipee->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-university"></i> Program Studi</label>
                                <select class="form-control select2" name="id_prodi" style="width: 100%;">
                                    <option value="all" {{ $id_prodi == 'all' || empty($id_prodi) ? 'selected' : '' }}>-- Semua Program Studi --</option>
                                    @foreach ($prodi as $prd)
                                        <option value="{{ $prd->kodeprodi }}" {{ $id_prodi == $prd->kodeprodi || $id_prodi == $prd->id_prodi ? 'selected' : '' }}>
                                            {{ $prd->prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="hidden-xs" style="display: block;">&nbsp;</label>
                            <div class="btn-action-group">
                                <button type="submit" class="btn btn-primary" title="Terapkan Filter">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                                <a href="{{ url('jadwal_perkuliahan') }}" class="btn btn-default" title="Reset ke Periode Aktif">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                                <a href="{{ url('export_jadwal_perkuliahan') }}?id_periodetahun={{ $id_periodetahun }}&id_periodetipe={{ $id_periodetipe }}&id_prodi={{ $id_prodi }}"
                                    class="btn btn-success" title="Ekspor ke Excel">
                                    <i class="fa fa-file-excel-o"></i> Excel
                                </a>
                                <button type="button" onclick="window.print()" class="btn btn-info" title="Cetak Lembar Jadwal">
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
            <h3 style="margin: 0; font-weight: bold;">JADWAL PERKULIAHAN MAHASISWA</h3>
            <h4 style="margin: 5px 0;">TAHUN AKADEMIK {{ strtoupper($namaperiodetahun) }} - {{ strtoupper($namaperiodetipe) }}</h4>
            <p style="margin: 0; font-size: 11pt;">Program Studi: <strong>{{ $namaprodi }}</strong></p>
            <hr style="margin: 10px 0; border-top: 1px solid #000;">
        </div>

        {{-- Tabel Data Jadwal --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-calendar"></i> Rekap Jadwal Perkuliahan 
                    <span class="label label-info" style="font-size: 13px; margin-left: 5px;">{{ $namaperiodetahun }} - {{ $namaperiodetipe }}</span>
                    @if ($id_prodi && $id_prodi !== 'all')
                        <span class="label label-primary" style="font-size: 13px; margin-left: 3px;">{{ $namaprodi }}</span>
                    @endif
                </h3>
                <div class="box-tools pull-right">
                    <span class="badge bg-green" style="font-size: 12px; padding: 5px 10px;">{{ $data->count() }} Jadwal Ditemukan</span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="example8" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr style="background-color: #f7f9fa;">
                            <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                            <th style="min-width: 220px; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 90px; text-align: center; vertical-align: middle;">SKS</th>
                            <th style="min-width: 150px; vertical-align: middle;">Program Studi</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="min-width: 180px; vertical-align: middle;">Dosen Pengampu</th>
                            <th style="width: 100px; text-align: center; vertical-align: middle;">Hari</th>
                            <th style="width: 110px; text-align: center; vertical-align: middle;">Jam</th>
                            <th style="width: 120px; text-align: center; vertical-align: middle;">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                            $hariMap = [
                                'SENIN' => ['nama' => 'Senin', 'badge' => 'label-primary'],
                                'MONDAY' => ['nama' => 'Senin', 'badge' => 'label-primary'],
                                'SELASA' => ['nama' => 'Selasa', 'badge' => 'label-info'],
                                'TUESDAY' => ['nama' => 'Selasa', 'badge' => 'label-info'],
                                'RABU' => ['nama' => 'Rabu', 'badge' => 'label-success'],
                                'WEDNESDAY' => ['nama' => 'Rabu', 'badge' => 'label-success'],
                                'KAMIS' => ['nama' => 'Kamis', 'badge' => 'label-warning'],
                                'THURSDAY' => ['nama' => 'Kamis', 'badge' => 'label-warning'],
                                'JUMAT' => ['nama' => 'Jumat', 'badge' => 'label-danger'],
                                'JUM\'AT' => ['nama' => 'Jumat', 'badge' => 'label-danger'],
                                'FRIDAY' => ['nama' => 'Jumat', 'badge' => 'label-danger'],
                                'SABTU' => ['nama' => 'Sabtu', 'badge' => 'bg-purple'],
                                'SATURDAY' => ['nama' => 'Sabtu', 'badge' => 'bg-purple'],
                                'MINGGU' => ['nama' => 'Minggu', 'badge' => 'bg-navy'],
                                'SUNDAY' => ['nama' => 'Minggu', 'badge' => 'bg-navy'],
                            ];
                        @endphp
                        @forelse ($data as $item)
                            @php
                                $kode = $item->kode ?? ($item->makul->kode ?? '-');
                                $namaMk = is_string($item->makul) ? $item->makul : ($item->makul->makul ?? '-');
                                $sksT = $item->sks_teori ?? ($item->makul ? ($item->makul->set_sks_teori ?? $item->makul->akt_sks_teori ?? 0) : 0);
                                $sksP = $item->sks_praktek ?? ($item->makul ? ($item->makul->set_sks_praktek ?? $item->makul->akt_sks_praktek ?? 0) : 0);
                                $totalSks = $item->total_sks ?? ($sksT + $sksP);
                                $prodiNama = is_string($item->prodi) ? $item->prodi : ($item->prodi->prodi ?? '-');
                                $konsentrasi = $item->daftar_konsentrasi ?? '';
                                $kelasNama = is_string($item->kelas) ? $item->kelas : ($item->kelas->kelas ?? '-');
                                $dosenNama = $item->nama_dosen ?? ($item->dosen->nama ?? null);
                                $rawHari = is_string($item->hari) ? $item->hari : ($item->hari->hari ?? '-');
                                $hariUpper = strtoupper(trim($rawHari));
                                $hariConfig = $hariMap[$hariUpper] ?? ['nama' => $rawHari ?: '-', 'badge' => 'label-default'];
                                $namaHari = $hariConfig['nama'];
                                $badgeHari = $hariConfig['badge'];
                                $jamLabel = is_string($item->jam) ? $item->jam : ($item->jam->jam ?? '-');
                                $ruanganLabel = $item->nama_ruangan ?? ($item->ruangan->nama_ruangan ?? '-');
                            @endphp
                            <tr>
                                <td align="center" style="vertical-align: middle;">{{ $no++ }}</td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-default" style="font-size: 10px; letter-spacing: 0.5px;">{{ $kode }}</span>
                                    <div style="font-weight: 600; margin-top: 3px; font-size: 13px; color: #2c3e50;">
                                        {{ $namaMk }}
                                    </div>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="badge bg-light-blue badge-sks" title="Total {{ $totalSks }} SKS">
                                        {{ $totalSks }} SKS
                                    </span>
                                    <div style="font-size: 10px; color: #777; margin-top: 2px;">
                                        T:{{ $sksT }} | P:{{ $sksP }}
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span style="font-weight: 600;">{{ $prodiNama }}</span>
                                    @if (!empty($konsentrasi))
                                        <br><small class="text-muted"><i class="fa fa-tags"></i> {{ $konsentrasi }}</small>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info" style="font-size: 11px;">
                                        {{ $kelasNama }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    @if (!empty($dosenNama) && $dosenNama !== 'Belum Ditentukan')
                                        <i class="fa fa-user-circle text-primary" style="margin-right: 4px;"></i>
                                        <strong>{{ $dosenNama }}</strong>
                                    @else
                                        <span class="text-muted" style="font-style: italic;">
                                            <i class="fa fa-question-circle text-muted"></i> Belum Ditentukan
                                        </span>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label {{ $badgeHari }} badge-hari" title="{{ $rawHari }}">
                                        {{ $namaHari }}
                                    </span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-default" style="font-size: 11px; padding: 4px 6px;">
                                        <i class="fa fa-clock-o text-muted"></i> {{ $jamLabel }}
                                    </span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-warning" style="font-size: 11px; padding: 4px 7px;">
                                        <i class="fa fa-map-marker"></i> {{ $ruanganLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-calendar-times-o fa-2x" style="margin-bottom: 10px; color: #bbb;"></i><br>
                                    <span style="font-size: 14px;">Belum ada jadwal perkuliahan yang terdaftar pada periode atau kriteria ini.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
