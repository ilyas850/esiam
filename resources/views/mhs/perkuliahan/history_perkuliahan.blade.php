@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <style>
            .history-page .box-header {
                border-bottom: 1px solid #f0f2f5;
                padding-bottom: 18px;
            }

            .history-page .page-subtitle {
                color: #6b7785;
                margin: 6px 0 0;
                font-size: 13px;
                line-height: 1.6;
            }

            .history-page .history-search {
                max-width: 320px;
            }

            .history-page .history-search .input-group {
                width: 100%;
            }

            .history-page .history-search .input-group-addon {
                background: #fff;
                border-color: #d2d6de;
                border-right: 0;
                border-radius: 20px 0 0 20px;
                color: #97a0ab;
                padding-left: 14px;
                padding-right: 10px;
            }

            .history-page .history-search .form-control {
                border-radius: 0 20px 20px 0;
                border-color: #d2d6de;
                padding-left: 0;
            }

            .history-page .info-box {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .history-page .info-box-content {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .history-page .info-box-text {
                text-transform: uppercase;
                letter-spacing: .4px;
                font-size: 11px;
                color: #7b8794;
            }

            .history-page .info-box-number {
                font-size: 22px;
                margin-top: 4px;
            }

            .history-page .history-table > tbody > tr > td,
            .history-page .history-table > thead > tr > th {
                vertical-align: middle;
            }

            .history-page .course-title {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.5;
            }

            .history-page .course-meta {
                display: block;
                color: #8b97a3;
                font-size: 12px;
                margin-top: 3px;
            }

            .history-page .mobile-history-list {
                display: none;
            }

            .history-page .history-card {
                border: 1px solid #e7eaee;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .history-page .history-card-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 12px;
            }

            .history-page .history-card-title {
                font-size: 15px;
                font-weight: 600;
                color: #2c3b41;
            }

            .history-page .history-card-subtitle {
                margin-top: 3px;
                color: #8b97a3;
                font-size: 12px;
            }

            .history-page .history-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 14px;
            }

            .history-page .history-card-item {
                background: #f8fafc;
                border-radius: 8px;
                padding: 10px 12px;
            }

            .history-page .history-card-label {
                display: block;
                color: #8b97a3;
                font-size: 11px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .history-page .history-card-value {
                display: block;
                color: #2c3b41;
                font-weight: 600;
                line-height: 1.45;
            }

            .history-page .history-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .history-page .empty-state {
                padding: 35px 20px;
                text-align: center;
                color: #7b8794;
            }

            .history-page .empty-state .fa {
                font-size: 30px;
                margin-bottom: 10px;
                color: #b7c0cb;
            }

            @media (max-width: 767px) {
                .history-page .box-header .pull-right {
                    float: none !important;
                    margin-top: 12px;
                }

                .history-page .history-search {
                    max-width: 100%;
                }

                .history-page .desktop-history-table {
                    display: none;
                }

                .history-page .mobile-history-list {
                    display: block;
                }

                .history-page .history-card-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="row history-page">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <div class="pull-left">
                            <h3 class="box-title">History Perkuliahan Mahasiswa</h3>
                            <p class="page-subtitle">
                                Riwayat seluruh matakuliah yang pernah diambil beserta ringkasan kehadiran dan akses cepat ke detail absensi.
                            </p>
                        </div>
                        <div class="pull-right">
                            <div class="history-search">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" id="history-search" class="form-control"
                                        placeholder="Cari tahun, matakuliah, hari, atau dosen">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Matakuliah</span>
                                        <span class="info-box-number">{{ $summary['total_makul'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Kehadiran</span>
                                        <span class="info-box-number">{{ $summary['total_hadir'] }} / {{ $summary['total_pertemuan'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-line-chart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Rata-rata Absen</span>
                                        <span class="info-box-number">{{ number_format($summary['rata_persentase'], 2) }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-bell-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Perlu Perhatian</span>
                                        <span class="info-box-number">{{ $summary['warning_count'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="desktop-history-table table-responsive">
                            <table class="table table-bordered table-striped table-hover history-table">
                                <thead>
                                    <tr>
                                        <th style="width: 150px;">Tahun Akademik</th>
                                        <th style="width: 110px;">Hari</th>
                                        <th style="width: 110px;">Jam</th>
                                        <th>Matakuliah</th>
                                        <th style="width: 180px;">Dosen</th>
                                        <th class="text-center" style="width: 120px;">Kehadiran</th>
                                        <th class="text-center" style="width: 120px;">Persentase</th>
                                        <th class="text-center" style="width: 90px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr class="history-item" data-search="{{ $item->search_text }}">
                                            <td>{{ $item->periode_label }}</td>
                                            <td>{{ $item->hari }}</td>
                                            <td>{{ $item->jam }}</td>
                                            <td>
                                                <span class="course-title">{{ $item->makul }}</span>
                                            </td>
                                            <td>{{ $item->nama }}</td>
                                            <td class="text-center"><strong>{{ $item->jml }} / {{ $item->total }}</strong></td>
                                            <td class="text-center">
                                                <span class="label label-{{ $item->persentase_class }}">
                                                    {{ number_format($item->persentase, 2) }}%
                                                </span>
                                                <span class="course-meta">{{ $item->persentase_text }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="/lihatabsen/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="empty-state">
                                                    <i class="fa fa-calendar-times-o"></i>
                                                    <div>Belum ada history perkuliahan yang dapat ditampilkan.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr id="history-not-found" style="display: none;">
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fa fa-search"></i>
                                                <div>Data history yang dicari tidak ditemukan.</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-history-list">
                            @forelse ($data as $item)
                                <div class="history-card history-item" data-search="{{ $item->search_text }}">
                                    <div class="history-card-top">
                                        <div>
                                            <div class="history-card-title">{{ $item->makul }}</div>
                                            <div class="history-card-subtitle">{{ $item->periode_label }}</div>
                                        </div>
                                        <span class="label label-{{ $item->persentase_class }}">
                                            {{ number_format($item->persentase, 2) }}%
                                        </span>
                                    </div>

                                    <div class="history-card-grid">
                                        <div class="history-card-item">
                                            <span class="history-card-label">Hari dan Jam</span>
                                            <span class="history-card-value">{{ $item->hari }} · {{ $item->jam }}</span>
                                        </div>
                                        <div class="history-card-item">
                                            <span class="history-card-label">Dosen</span>
                                            <span class="history-card-value">{{ $item->nama }}</span>
                                        </div>
                                        <div class="history-card-item">
                                            <span class="history-card-label">Kehadiran</span>
                                            <span class="history-card-value">{{ $item->jml }} / {{ $item->total }}</span>
                                        </div>
                                        <div class="history-card-item">
                                            <span class="history-card-label">Status</span>
                                            <span class="history-card-value">{{ $item->persentase_text }}</span>
                                        </div>
                                    </div>

                                    <div class="history-card-footer">
                                        <span class="history-card-subtitle">Riwayat perkuliahan dan absensi</span>
                                        <a href="/lihatabsen/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-calendar-times-o"></i>
                                    <div>Belum ada history perkuliahan yang dapat ditampilkan.</div>
                                </div>
                            @endforelse

                            @if ($data->count())
                                <div class="empty-state" id="history-mobile-empty" style="display: none;">
                                    <i class="fa fa-search"></i>
                                    <div>Data history yang dicari tidak ditemukan.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            var $search = $('#history-search');
            var $items = $('.history-item');
            var $desktopEmpty = $('#history-not-found');
            var $mobileEmpty = $('#history-mobile-empty');

            $search.on('keyup', function() {
                var keyword = $(this).val().toLowerCase().trim();
                var visibleCount = 0;

                $items.each(function() {
                    var $item = $(this);
                    var searchText = ($item.data('search') || '').toString();
                    var isMatch = keyword === '' || searchText.indexOf(keyword) !== -1;

                    $item.toggle(isMatch);

                    if (isMatch) {
                        visibleCount++;
                    }
                });

                if ($desktopEmpty.length) {
                    $desktopEmpty.toggle(visibleCount === 0);
                }

                if ($mobileEmpty.length) {
                    $mobileEmpty.toggle(visibleCount === 0);
                }
            });
        });
    </script>
@endsection
