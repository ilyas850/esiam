@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <style>
            .viewbap-page .box-header {
                border-bottom: 1px solid #f0f2f5;
                padding-bottom: 18px;
            }

            .viewbap-page .page-subtitle {
                color: #6b7785;
                margin: 6px 0 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .viewbap-page .header-actions {
                margin-top: 15px;
            }

            .viewbap-page .header-actions .btn {
                margin-right: 8px;
                margin-bottom: 8px;
            }

            .viewbap-page .section-title {
                color: #3c8dbc;
                border-bottom: 2px solid #e7eaee;
                padding-bottom: 6px;
                margin: 0 0 15px;
                font-size: 18px;
            }

            .viewbap-page .meta-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .viewbap-page .meta-card {
                background: #f8fafc;
                border: 1px solid #e7eaee;
                border-radius: 10px;
                padding: 14px 16px;
            }

            .viewbap-page .meta-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .viewbap-page .meta-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.55;
            }

            .viewbap-page .well-soft {
                min-height: 120px;
                background-color: #f9f9f9;
                border: 1px solid #e7eaee;
                border-radius: 10px;
                padding: 15px;
                color: #2c3b41;
                line-height: 1.7;
                margin-bottom: 15px;
                white-space: pre-line;
            }

            .viewbap-page .info-box {
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                margin-bottom: 15px;
            }

            .viewbap-page .info-box-content {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .viewbap-page .attachment-link {
                color: #fff;
                text-decoration: underline;
                font-size: 14px;
                word-break: break-word;
                display: inline-block;
                line-height: 1.5;
            }

            .viewbap-page .attachment-empty {
                font-size: 14px;
                opacity: .95;
                display: inline-block;
                line-height: 1.5;
            }

            @media (max-width: 767px) {
                .viewbap-page .meta-grid {
                    grid-template-columns: 1fr;
                }

                .viewbap-page .header-actions .btn {
                    width: 100%;
                    margin-right: 0;
                }

                .viewbap-page .info-box {
                    min-height: 96px;
                }

                .viewbap-page .well-soft {
                    min-height: 0;
                }
            }
        </style>

        <div class="row viewbap-page">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div>
                            <h3 class="box-title"><i class="fa fa-eye"></i> Detail Laporan BAP</h3>
                            <p class="page-subtitle">
                                Detail ini menampilkan ringkasan pelaksanaan perkuliahan, materi yang disampaikan, dan lampiran bukti untuk satu pertemuan.
                            </p>
                        </div>
                        <div class="header-actions">
                            <a class="btn btn-default" href="/lihatabsen/{{ $dtbp->id_kurperiode }}">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <h4 class="section-title"><i class="fa fa-info-circle"></i> Informasi Umum</h4>

                        <div class="meta-grid">
                            <div class="meta-card">
                                <span class="meta-label">Program Studi</span>
                                <span class="meta-value">{{ $course->prodi }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Semester Akademik</span>
                                <span class="meta-value">{{ $course->periode_tipe }} - {{ $course->periode_tahun }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Matakuliah</span>
                                <span class="meta-value">{{ $course->makul }} ({{ $course->kode }})</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Dosen Pengampu</span>
                                <span class="meta-value">{{ $course->dosen_label ?: '-' }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Kelas / Semester</span>
                                <span class="meta-value">{{ $course->kelas }} / {{ $course->semester }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Pertemuan</span>
                                <span class="meta-value">Ke-{{ $dtbp->pertemuan }}</span>
                            </div>
                        </div>

                        <h4 class="section-title"><i class="fa fa-calendar"></i> Waktu Pelaksanaan</h4>

                        <div class="meta-grid">
                            <div class="meta-card">
                                <span class="meta-label">Tanggal Perkuliahan</span>
                                <span class="meta-value">{{ $dtbp->tanggal_label }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Pukul</span>
                                <span class="meta-value">{{ $dtbp->jam_label }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Media Pembelajaran</span>
                                <span class="meta-value">{{ $dtbp->media_pembelajaran }}</span>
                            </div>
                            <div class="meta-card">
                                <span class="meta-label">Mahasiswa Hadir / Tidak Hadir</span>
                                <span class="meta-value">{{ $dtbp->hadir }} / {{ $dtbp->tidak_hadir }}</span>
                            </div>
                        </div>

                        <h4 class="section-title"><i class="fa fa-file-text-o"></i> Konten Perkuliahan</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Materi Perkuliahan</label>
                                <div class="well-soft">{{ $dtbp->materi_kuliah }}</div>
                            </div>
                            <div class="col-md-6">
                                <label>Metode Kuliah</label>
                                <div class="well-soft">{{ $dtbp->metode_kuliah }}</div>
                            </div>
                        </div>

                        <h4 class="section-title"><i class="fa fa-paperclip"></i> Bukti dan Lampiran</h4>

                        <div class="row">
                            @foreach ($attachments as $attachment)
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="info-box bg-{{ $attachment['class'] }}">
                                        <span class="info-box-icon"><i class="fa fa-{{ $attachment['icon'] }}"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $attachment['title'] }}</span>
                                            <span class="info-box-number" style="font-weight: normal; margin-top: 6px;">
                                                @if ($attachment['url'])
                                                    <a href="{{ $attachment['url'] }}" target="_blank" class="attachment-link">
                                                        <i class="fa fa-external-link"></i> {{ $attachment['label'] }}
                                                    </a>
                                                @else
                                                    <span class="attachment-empty">{{ $attachment['label'] }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
