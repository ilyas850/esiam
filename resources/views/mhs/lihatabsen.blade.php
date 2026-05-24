@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <style>
            .lihatabsen-page .box-header {
                border-bottom: 1px solid #f0f2f5;
                padding-bottom: 18px;
            }

            .lihatabsen-page .page-subtitle {
                color: #6b7785;
                margin: 6px 0 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .lihatabsen-page .header-actions {
                margin-top: 15px;
            }

            .lihatabsen-page .course-meta-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .lihatabsen-page .course-meta-card {
                background: #f8fafc;
                border: 1px solid #e7eaee;
                border-radius: 10px;
                padding: 14px 16px;
            }

            .lihatabsen-page .course-meta-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .lihatabsen-page .course-meta-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.55;
            }

            .lihatabsen-page .info-box {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .lihatabsen-page .info-box-content {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .lihatabsen-page .info-box-text {
                text-transform: uppercase;
                letter-spacing: .4px;
                font-size: 11px;
                color: #7b8794;
            }

            .lihatabsen-page .info-box-number {
                font-size: 22px;
                margin-top: 4px;
            }

            .lihatabsen-page .session-table > tbody > tr > td,
            .lihatabsen-page .session-table > thead > tr > th {
                vertical-align: middle;
            }

            .lihatabsen-page .materi-cell {
                min-width: 260px;
            }

            .lihatabsen-page .materi-title {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.5;
            }

            .lihatabsen-page .materi-subtitle {
                display: block;
                margin-top: 4px;
                color: #8b97a3;
                font-size: 12px;
                line-height: 1.5;
            }

            .lihatabsen-page .mobile-session-list {
                display: none;
            }

            .lihatabsen-page .session-card {
                border: 1px solid #e7eaee;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .lihatabsen-page .session-card-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 12px;
            }

            .lihatabsen-page .session-card-title {
                font-size: 15px;
                font-weight: 600;
                color: #2c3b41;
            }

            .lihatabsen-page .session-card-subtitle {
                margin-top: 3px;
                color: #8b97a3;
                font-size: 12px;
            }

            .lihatabsen-page .session-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 14px;
            }

            .lihatabsen-page .session-card-item {
                background: #f8fafc;
                border-radius: 8px;
                padding: 10px 12px;
            }

            .lihatabsen-page .session-card-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .lihatabsen-page .session-card-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.45;
            }

            .lihatabsen-page .session-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .lihatabsen-page .empty-state {
                padding: 35px 20px;
                text-align: center;
                color: #7b8794;
            }

            .lihatabsen-page .empty-state .fa {
                font-size: 30px;
                margin-bottom: 10px;
                color: #b7c0cb;
            }

            @media (max-width: 767px) {
                .lihatabsen-page .course-meta-grid,
                .lihatabsen-page .session-card-grid {
                    grid-template-columns: 1fr;
                }

                .lihatabsen-page .desktop-session-table {
                    display: none;
                }

                .lihatabsen-page .mobile-session-list {
                    display: block;
                }

                .lihatabsen-page .header-actions .btn {
                    width: 100%;
                }
            }
        </style>

        <div class="row lihatabsen-page">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div>
                            <h3 class="box-title">Detail Absensi Perkuliahan</h3>
                            <p class="page-subtitle">
                                Pantau rangkaian pertemuan, materi yang disampaikan, dan rekap kehadiran kelas pada setiap
                                sesi matakuliah ini.
                            </p>
                        </div>
                        <div class="header-actions">
                            <a href="/view_abs/{{ $course->id_kurperiode }}" class="btn btn-warning">
                                <i class="fa fa-list-alt"></i> Rekap Absensi
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
                                    <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Hadir Kelas</span>
                                        <span class="info-box-number">{{ $summary['total_hadir'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-user-times"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Tidak Hadir Kelas</span>
                                        <span class="info-box-number">{{ $summary['total_tidak_hadir'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-flag-checkered"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pertemuan Terakhir</span>
                                        <span class="info-box-number">Ke-{{ $summary['pertemuan_terakhir'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="desktop-session-table table-responsive">
                            <table class="table table-bordered table-striped table-hover session-table">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">
                                            <center>Pertemuan</center>
                                        </th>
                                        <th style="width: 110px;">
                                            <center>Tanggal</center>
                                        </th>
                                        <th style="width: 110px;">
                                            <center>Jam</center>
                                        </th>
                                        <th>Materi Pembelajaran</th>
                                        <th>Praktikum</th>
                                        <th style="width: 110px;">
                                            <center>Tipe</center>
                                        </th>
                                        <th style="width: 120px;">
                                            <center>Hadir / Tidak</center>
                                        </th>
                                        <th style="width: 90px;">
                                            <center>Aksi</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bap as $item)
                                        <tr>
                                            <td class="text-center"><strong>Ke-{{ $item->pertemuan }}</strong></td>
                                            <td class="text-center">{{ $item->tanggal_label }}</td>
                                            <td class="text-center">{{ $item->jam_label }}</td>
                                            <td class="materi-cell">
                                                <span class="materi-title">{{ $item->materi_kuliah }}</span>
                                            </td>
                                            <td>{{ $item->praktikum }}</td>
                                            <td class="text-center">
                                                <span class="label label-primary">{{ $item->tipe_kuliah }}</span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $item->hadir }} / {{ $item->tidak_hadir }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <a href="/view_bap_mhs/{{ $item->id_bap }}" class="btn btn-info btn-xs" title="Lihat BAP">
                                                    <i class="fa fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="empty-state">
                                                    <i class="fa fa-calendar-times-o"></i>
                                                    <div>Belum ada data pertemuan yang dapat ditampilkan.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-session-list">
                            @forelse ($bap as $item)
                                <div class="session-card">
                                    <div class="session-card-top">
                                        <div>
                                            <div class="session-card-title">Pertemuan Ke-{{ $item->pertemuan }}</div>
                                            <div class="session-card-subtitle">{{ $item->tanggal_label }} · {{ $item->jam_label }}</div>
                                        </div>
                                        <span class="label label-primary">{{ $item->tipe_kuliah }}</span>
                                    </div>

                                    <div class="session-card-grid">
                                        <div class="session-card-item">
                                            <span class="session-card-label">Materi Pembelajaran</span>
                                            <span class="session-card-value">{{ $item->materi_kuliah }}</span>
                                        </div>
                                        <div class="session-card-item">
                                            <span class="session-card-label">Praktikum</span>
                                            <span class="session-card-value">{{ $item->praktikum }}</span>
                                        </div>
                                        <div class="session-card-item">
                                            <span class="session-card-label">Kehadiran Kelas</span>
                                            <span class="session-card-value">{{ $item->hadir }} hadir / {{ $item->tidak_hadir }} tidak</span>
                                        </div>
                                        <div class="session-card-item">
                                            <span class="session-card-label">Tipe Kuliah</span>
                                            <span class="session-card-value">{{ $item->tipe_kuliah }}</span>
                                        </div>
                                    </div>

                                    <div class="session-card-footer">
                                        <span class="session-card-subtitle">Detail materi dan pelaksanaan pertemuan</span>
                                        <a href="/view_bap_mhs/{{ $item->id_bap }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-calendar-times-o"></i>
                                    <div>Belum ada data pertemuan yang dapat ditampilkan.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
