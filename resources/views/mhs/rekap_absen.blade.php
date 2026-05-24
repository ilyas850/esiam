@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <style>
            .rekapabsen-page .box-header {
                border-bottom: 1px solid #f0f2f5;
                padding-bottom: 18px;
            }

            .rekapabsen-page .page-subtitle {
                color: #6b7785;
                margin: 6px 0 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .rekapabsen-page .header-actions {
                margin-top: 15px;
            }

            .rekapabsen-page .header-actions .btn {
                margin-right: 8px;
                margin-bottom: 8px;
            }

            .rekapabsen-page .course-meta-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .rekapabsen-page .course-meta-card {
                background: #f8fafc;
                border: 1px solid #e7eaee;
                border-radius: 10px;
                padding: 14px 16px;
            }

            .rekapabsen-page .course-meta-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .rekapabsen-page .course-meta-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.55;
            }

            .rekapabsen-page .info-box {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .rekapabsen-page .info-box-content {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .rekapabsen-page .info-box-text {
                text-transform: uppercase;
                letter-spacing: .4px;
                font-size: 11px;
                color: #7b8794;
            }

            .rekapabsen-page .info-box-number {
                font-size: 22px;
                margin-top: 4px;
            }

            .rekapabsen-page .attendance-table > tbody > tr > td,
            .rekapabsen-page .attendance-table > thead > tr > th {
                vertical-align: middle;
            }

            .rekapabsen-page .mobile-attendance-list {
                display: none;
            }

            .rekapabsen-page .attendance-card {
                border: 1px solid #e7eaee;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .rekapabsen-page .attendance-card-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 12px;
            }

            .rekapabsen-page .attendance-card-title {
                font-size: 15px;
                font-weight: 600;
                color: #2c3b41;
            }

            .rekapabsen-page .attendance-card-subtitle {
                margin-top: 3px;
                color: #8b97a3;
                font-size: 12px;
            }

            .rekapabsen-page .attendance-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .rekapabsen-page .attendance-card-item {
                background: #f8fafc;
                border-radius: 8px;
                padding: 10px 12px;
            }

            .rekapabsen-page .attendance-card-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .rekapabsen-page .attendance-card-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.45;
            }

            .rekapabsen-page .status-badge {
                display: inline-block;
                min-width: 72px;
                text-align: center;
            }

            .rekapabsen-page .empty-state {
                padding: 35px 20px;
                text-align: center;
                color: #7b8794;
            }

            .rekapabsen-page .empty-state .fa {
                font-size: 30px;
                margin-bottom: 10px;
                color: #b7c0cb;
            }

            @media (max-width: 767px) {
                .rekapabsen-page .course-meta-grid,
                .rekapabsen-page .attendance-card-grid {
                    grid-template-columns: 1fr;
                }

                .rekapabsen-page .desktop-attendance-table {
                    display: none;
                }

                .rekapabsen-page .mobile-attendance-list {
                    display: block;
                }

                .rekapabsen-page .header-actions .btn {
                    width: 100%;
                    margin-right: 0;
                }
            }
        </style>

        <div class="row rekapabsen-page">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div>
                            <h3 class="box-title">Rekap Absensi Mahasiswa</h3>
                            <p class="page-subtitle">
                                Ringkasan ini menampilkan status kehadiran Anda pada setiap pertemuan untuk matakuliah yang dipilih.
                            </p>
                        </div>
                        <div class="header-actions">
                            <a href="/lihatabsen/{{ $course->id_kurperiode }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Kembali ke Detail Pertemuan
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="course-meta-grid">
                            <div class="course-meta-card">
                                <span class="course-meta-label">Matakuliah</span>
                                <span class="course-meta-value">{{ $course->makul }} ({{ $course->kode }})</span>
                            </div>
                            <div class="course-meta-card">
                                <span class="course-meta-label">Tahun Akademik</span>
                                <span class="course-meta-value">{{ $course->periode_tahun }} {{ $course->periode_tipe }}</span>
                            </div>
                            <div class="course-meta-card">
                                <span class="course-meta-label">Waktu dan Ruangan</span>
                                <span class="course-meta-value">
                                    {{ $course->hari }}, {{ $course->jam }} - {{ $course->jam_selesai ?: '-' }} / {{ $course->nama_ruangan }}
                                </span>
                            </div>
                            <div class="course-meta-card">
                                <span class="course-meta-label">Dosen Pengampu</span>
                                <span class="course-meta-value">{{ $course->dosen_label ?: '-' }}</span>
                            </div>
                            <div class="course-meta-card">
                                <span class="course-meta-label">Program Studi</span>
                                <span class="course-meta-value">{{ $course->prodi }}</span>
                            </div>
                            <div class="course-meta-card">
                                <span class="course-meta-label">Kelas dan SKS</span>
                                <span class="course-meta-value">{{ $course->kelas }} · {{ $course->akt_sks }} SKS</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Pertemuan</span>
                                        <span class="info-box-number">{{ $summary['total_pertemuan'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Hadir</span>
                                        <span class="info-box-number">{{ $summary['hadir'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-medkit"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Izin / Sakit</span>
                                        <span class="info-box-number">{{ $summary['izin'] + $summary['sakit'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Alfa</span>
                                        <span class="info-box-number">{{ $summary['alfa'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="desktop-attendance-table table-responsive">
                            <table class="table table-bordered table-striped table-hover attendance-table">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">
                                            <center>Pertemuan</center>
                                        </th>
                                        <th style="width: 120px;">
                                            <center>Tanggal</center>
                                        </th>
                                        <th style="width: 120px;">
                                            <center>Jam</center>
                                        </th>
                                        <th style="width: 150px;">
                                            <center>Status</center>
                                        </th>
                                        <th style="width: 90px;">
                                            <center>Kode</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($abs as $item)
                                        <tr>
                                            <td class="text-center"><strong>Ke-{{ $item->pertemuan }}</strong></td>
                                            <td class="text-center">{{ $item->tanggal_label }}</td>
                                            <td class="text-center">{{ $item->jam_label }}</td>
                                            <td class="text-center">
                                                <span class="label label-{{ $item->status_class }} status-badge">
                                                    <i class="fa fa-{{ $item->status_icon }}"></i> {{ $item->status_label }}
                                                </span>
                                            </td>
                                            <td class="text-center"><strong>{{ $item->status_short }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="empty-state">
                                                    <i class="fa fa-calendar-times-o"></i>
                                                    <div>Belum ada data absensi mahasiswa yang dapat ditampilkan.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-attendance-list">
                            @forelse ($abs as $item)
                                <div class="attendance-card">
                                    <div class="attendance-card-top">
                                        <div>
                                            <div class="attendance-card-title">Pertemuan Ke-{{ $item->pertemuan }}</div>
                                            <div class="attendance-card-subtitle">{{ $item->tanggal_label }} · {{ $item->jam_label }}</div>
                                        </div>
                                        <span class="label label-{{ $item->status_class }} status-badge">
                                            <i class="fa fa-{{ $item->status_icon }}"></i> {{ $item->status_label }}
                                        </span>
                                    </div>

                                    <div class="attendance-card-grid">
                                        <div class="attendance-card-item">
                                            <span class="attendance-card-label">Status Kehadiran</span>
                                            <span class="attendance-card-value">{{ $item->status_label }}</span>
                                        </div>
                                        <div class="attendance-card-item">
                                            <span class="attendance-card-label">Kode Singkat</span>
                                            <span class="attendance-card-value">{{ $item->status_short }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-calendar-times-o"></i>
                                    <div>Belum ada data absensi mahasiswa yang dapat ditampilkan.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
