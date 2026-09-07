@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart text-red"></i> Rekapitulasi KRS Mahasiswa
            <small>Ringkasan Headcount & Akumulasi KRS per Tahun Akademik</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Master KRS</a></li>
            <li class="active">Rekapitulasi KRS</li>
        </ol>
    </section>

    <section class="content">
        <!-- ==================== TOP KPI WIDGETS ==================== -->
        <div class="row">
            <!-- Widget 1: Mahasiswa Aktif Tahun Terkini -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua" title="Headcount riil individu mahasiswa yang mengambil KRS pada {{ $latestStats['periode_tahun'] }}">
                    <div class="inner">
                        <h3>{{ number_format($latestStats['total_mhs_unik']) }} <sup style="font-size: 16px;">Mhs</sup></h3>
                        <p><strong>Mhs Aktif ({{ $latestStats['display_tahun'] }})</strong></p>
                        <span style="font-size: 12px; opacity: 0.9;">
                            @if ($latestStats['growth_pct'] > 0)
                                <i class="fa fa-arrow-circle-up"></i> +{{ $latestStats['growth_pct'] }}% dari TA sebelumnya
                            @elseif ($latestStats['growth_pct'] < 0)
                                <i class="fa fa-arrow-circle-down"></i> {{ $latestStats['growth_pct'] }}% dari TA sebelumnya
                            @else
                                <i class="fa fa-minus-circle"></i> Stabil dari TA sebelumnya
                            @endif
                        </span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#tabel-detail" class="small-box-footer">
                        Lihat Data Rinci <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Widget 2: KRS Semester Ganjil Terkini -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ number_format($latestStats['jml_ganjil']) }} <sup style="font-size: 16px;">KRS</sup></h3>
                        <p><strong>Semester Ganjil {{ $latestStats['display_tahun'] }}</strong></p>
                        <span style="font-size: 12px; opacity: 0.9;">
                            <i class="fa fa-check-circle-o"></i> 4 Program Studi Terlayani
                        </span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <a href="#semester-chart-box" class="small-box-footer">
                        Lihat Tren Semester <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Widget 3: KRS Semester Genap Terkini -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ number_format($latestStats['jml_genap']) }} <sup style="font-size: 16px;">KRS</sup></h3>
                        <p><strong>Semester Genap {{ $latestStats['display_tahun'] }}</strong></p>
                        <span style="font-size: 12px; opacity: 0.9;">
                            <i class="fa fa-pie-chart"></i> Periode Reguler Semester 2
                        </span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar-plus-o"></i>
                    </div>
                    <a href="#semester-chart-box" class="small-box-footer">
                        Lihat Tren Semester <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Widget 4: KRS Semester Pendek Terkini -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ number_format($latestStats['jml_pendek']) }} <sup style="font-size: 16px;">KRS</sup></h3>
                        <p><strong>Semester Pendek {{ $latestStats['display_tahun'] }}</strong></p>
                        <span style="font-size: 12px; opacity: 0.9;">
                            <i class="fa fa-clock-o"></i> Remidi / Akselerasi SKS
                        </span>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bolt"></i>
                    </div>
                    <a href="#semester-chart-box" class="small-box-footer">
                        Lihat Tren Semester <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ==================== CHARTS SECTION ==================== -->
        <div class="row">
            <!-- Left Chart: Tren per Semester -->
            <div class="col-md-6" id="semester-chart-box">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart text-primary"></i> Tren KRS per Semester (Tahun ke Tahun)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- Chart Legend Badges -->
                        <div class="text-center" style="margin-bottom: 12px;">
                            <span class="label" style="background-color: #3c8dbc; padding: 5px 10px; font-size: 12px; margin-right: 8px;">
                                <i class="fa fa-square"></i> Semester Ganjil
                            </span>
                            <span class="label" style="background-color: #00a65a; padding: 5px 10px; font-size: 12px; margin-right: 8px;">
                                <i class="fa fa-square"></i> Semester Genap
                            </span>
                            <span class="label" style="background-color: #f39c12; padding: 5px 10px; font-size: 12px;">
                                <i class="fa fa-square"></i> Semester Pendek
                            </span>
                        </div>
                        <div class="chart" style="position: relative; height: 260px;">
                            <canvas id="semesterBarChart" style="height: 260px; width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="box-footer text-muted text-center" style="font-size: 12px;">
                        <i class="fa fa-info-circle"></i> Membandingkan total mahasiswa yang mengisi KRS pada setiap semester per Tahun Akademik.
                    </div>
                </div>
            </div>

            <!-- Right Chart: Pertumbuhan per Program Studi -->
            <div class="col-md-6" id="prodi-chart-box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-line-chart text-info"></i> Pertumbuhan Mahasiswa per Program Studi</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- Chart Legend Badges -->
                        <div class="text-center" style="margin-bottom: 12px;">
                            <span class="label" style="background-color: #0073b7; padding: 5px 10px; font-size: 12px; margin-right: 6px;">
                                <i class="fa fa-circle"></i> TRPL / TK
                            </span>
                            <span class="label" style="background-color: #dd4b39; padding: 5px 10px; font-size: 12px; margin-right: 6px;">
                                <i class="fa fa-circle"></i> Teknik Industri (TI)
                            </span>
                            <span class="label" style="background-color: #00a65a; padding: 5px 10px; font-size: 12px; margin-right: 6px;">
                                <i class="fa fa-circle"></i> Farmasi (FA)
                            </span>
                            <span class="label" style="background-color: #605ca8; padding: 5px 10px; font-size: 12px;">
                                <i class="fa fa-circle"></i> Logistik (TRL)
                            </span>
                        </div>
                        <div class="chart" style="position: relative; height: 260px;">
                            <canvas id="prodiLineChart" style="height: 260px; width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="box-footer text-muted text-center" style="font-size: 12px;">
                        <i class="fa fa-info-circle"></i> Jumlah mahasiswa unik per program studi yang aktif berkuliah tiap tahun akademik.
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== MATRIX DATA TABLE ==================== -->
        <div class="row" id="tabel-detail">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-table text-red"></i> Matriks Data Rekapitulasi KRS Mahasiswa</h3>
                        <div class="box-tools pull-right no-print">
                            <button type="button" class="btn btn-sm btn-success" onclick="exportTableToExcel('tabel-rekap-krs', 'Rekapitulasi_KRS_Mahasiswa')">
                                <i class="fa fa-file-excel-o"></i> Ekspor Excel
                            </button>
                            <button type="button" class="btn btn-sm btn-default" onclick="window.print()">
                                <i class="fa fa-print"></i> Cetak Laporan
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table id="tabel-rekap-krs" class="table table-bordered table-striped table-hover" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle; width: 40px; background-color: #f4f4f4;">No</th>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle; min-width: 125px; background-color: #f4f4f4;">Periode Tahun</th>
                                    <th colspan="5" class="text-center" style="background-color: #3c8dbc; color: #fff; border-bottom: 2px solid #286090;">
                                        <i class="fa fa-calendar-check-o"></i> SEMESTER GANJIL
                                    </th>
                                    <th colspan="5" class="text-center" style="background-color: #00a65a; color: #fff; border-bottom: 2px solid #00733e;">
                                        <i class="fa fa-calendar-plus-o"></i> SEMESTER GENAP
                                    </th>
                                    <th colspan="5" class="text-center" style="background-color: #f39c12; color: #fff; border-bottom: 2px solid #c87f0a;">
                                        <i class="fa fa-clock-o"></i> SEMESTER PENDEK
                                    </th>
                                    <th colspan="2" class="text-center" style="background-color: #605ca8; color: #fff; border-bottom: 2px solid #484488;">
                                        <i class="fa fa-calculator"></i> TOTAL TAHUNAN
                                    </th>
                                </tr>
                                <tr style="font-size: 12px;">
                                    <!-- Ganjil -->
                                    <th class="text-center" style="background-color: #eaf2f8; color: #1f618d;">TRPL</th>
                                    <th class="text-center" style="background-color: #eaf2f8; color: #1f618d;">TI</th>
                                    <th class="text-center" style="background-color: #eaf2f8; color: #1f618d;">FA</th>
                                    <th class="text-center" style="background-color: #eaf2f8; color: #1f618d;">TRL</th>
                                    <th class="text-center" style="background-color: #d4e6f1; color: #154360; font-weight: bold;">TOTAL</th>

                                    <!-- Genap -->
                                    <th class="text-center" style="background-color: #eafaf1; color: #196f3d;">TRPL</th>
                                    <th class="text-center" style="background-color: #eafaf1; color: #196f3d;">TI</th>
                                    <th class="text-center" style="background-color: #eafaf1; color: #196f3d;">FA</th>
                                    <th class="text-center" style="background-color: #eafaf1; color: #196f3d;">TRL</th>
                                    <th class="text-center" style="background-color: #d5f5e3; color: #145a32; font-weight: bold;">TOTAL</th>

                                    <!-- Pendek -->
                                    <th class="text-center" style="background-color: #fef9e7; color: #9a7d0a;">TRPL</th>
                                    <th class="text-center" style="background-color: #fef9e7; color: #9a7d0a;">TI</th>
                                    <th class="text-center" style="background-color: #fef9e7; color: #9a7d0a;">FA</th>
                                    <th class="text-center" style="background-color: #fef9e7; color: #9a7d0a;">TRL</th>
                                    <th class="text-center" style="background-color: #fcf3cf; color: #7d6608; font-weight: bold;">TOTAL</th>

                                    <!-- Total Tahunan -->
                                    <th class="text-center" style="background-color: #f4f0fa; color: #4a235a; font-weight: bold;" title="Akumulasi total pengisian KRS di 3 semester">Total KRS</th>
                                    <th class="text-center" style="background-color: #ebdef0; color: #4a235a; font-weight: bold;" title="Jumlah mahasiswa individu unik yang aktif pada tahun akademik tersebut">Mhs Unik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    @php
                                        $isLatest = ($loop->last);
                                    @endphp
                                    <tr class="{{ $isLatest ? 'warning' : '' }}" style="{{ $isLatest ? 'font-weight: 500;' : '' }}">
                                        <td class="text-center" style="vertical-align: middle;">{{ $no++ }}</td>
                                        <td style="vertical-align: middle; white-space: nowrap;">
                                            <strong>{{ $item->periode_tahun }}</strong>
                                            @if ($isLatest)
                                                <span class="label label-danger pull-right" style="margin-left: 5px;">Terkini</span>
                                            @endif
                                        </td>

                                        <!-- Ganjil -->
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->tk_gnj) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->ti_gnj) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->fa_gnj) }}</td>
                                        <td class="text-center" style="vertical-align: middle; {{ $item->trl_gnj > 0 ? 'font-weight: bold; color: #605ca8;' : 'color: #aaa;' }}">
                                            {{ $item->trl_gnj > 0 ? number_format($item->trl_gnj) : '-' }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle; background-color: #f2f7fa; font-weight: bold; color: #154360;">
                                            {{ number_format($item->jml_ganjil) }}
                                        </td>

                                        <!-- Genap -->
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->tk_gnp) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->ti_gnp) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->fa_gnp) }}</td>
                                        <td class="text-center" style="vertical-align: middle; {{ $item->trl_gnp > 0 ? 'font-weight: bold; color: #605ca8;' : 'color: #aaa;' }}">
                                            {{ $item->trl_gnp > 0 ? number_format($item->trl_gnp) : '-' }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle; background-color: #f2fbf6; font-weight: bold; color: #145a32;">
                                            {{ number_format($item->jml_genap) }}
                                        </td>

                                        <!-- Pendek -->
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->tk_pndk) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->ti_pndk) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">{{ number_format($item->fa_pndk) }}</td>
                                        <td class="text-center" style="vertical-align: middle; {{ $item->trl_pndk > 0 ? 'font-weight: bold; color: #605ca8;' : 'color: #aaa;' }}">
                                            {{ $item->trl_pndk > 0 ? number_format($item->trl_pndk) : '-' }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle; background-color: #fefcf5; font-weight: bold; color: #7d6608;">
                                            {{ number_format($item->jml_pendek) }}
                                        </td>

                                        <!-- Total Tahunan -->
                                        <td class="text-center" style="vertical-align: middle; background-color: #f9f7fc; font-weight: bold; color: #4a235a;">
                                            {{ number_format($item->total_krs) }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle; background-color: #f5eef8; font-weight: bold; color: #4a235a;">
                                            <span class="badge bg-purple" style="font-size: 12px; padding: 4px 8px;">
                                                {{ number_format($item->total_mhs_unik) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #2c3b41; color: #fff; font-weight: bold; font-size: 13px;">
                                    <td colspan="2" class="text-center" style="vertical-align: middle; letter-spacing: 1px;">
                                        TOTAL KESELURUHAN
                                    </td>
                                    <!-- Ganjil -->
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['tk_gnj']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['ti_gnj']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['fa_gnj']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['trl_gnj']) }}</td>
                                    <td class="text-center" style="vertical-align: middle; background-color: #1f618d; color: #fff;">
                                        {{ number_format($grandTotal['jml_ganjil']) }}
                                    </td>

                                    <!-- Genap -->
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['tk_gnp']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['ti_gnp']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['fa_gnp']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['trl_gnp']) }}</td>
                                    <td class="text-center" style="vertical-align: middle; background-color: #196f3d; color: #fff;">
                                        {{ number_format($grandTotal['jml_genap']) }}
                                    </td>

                                    <!-- Pendek -->
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['tk_pndk']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['ti_pndk']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['fa_pndk']) }}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{ number_format($grandTotal['trl_pndk']) }}</td>
                                    <td class="text-center" style="vertical-align: middle; background-color: #b7950b; color: #fff;">
                                        {{ number_format($grandTotal['jml_pendek']) }}
                                    </td>

                                    <!-- Total Tahunan -->
                                    <td class="text-center" style="vertical-align: middle; background-color: #512e5f; color: #fff;">
                                        {{ number_format($grandTotal['total_krs']) }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle; background-color: #4a235a; color: #fff;">
                                        {{ number_format($grandTotal['total_mhs_unik']) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer clearfix" style="font-size: 12px; color: #666;">
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>Keterangan Singkatan Prodi:</strong><br>
                                <strong>TRPL</strong>: Teknologi Rekayasa Perangkat Lunak (termasuk Teknik Komputer) | 
                                <strong>TI</strong>: Teknik Industri | 
                                <strong>FA</strong>: Farmasi | 
                                <strong>TRL</strong>: Terapan Rekayasa Logistik (Aktif sejak 2024)
                            </div>
                            <div class="col-sm-6 text-right">
                                <strong>Catatan:</strong> Kolom <em>Total KRS</em> adalah akumulasi pengisian di semua semester, sedangkan <em>Mhs Unik</em> adalah headcount orang mahasiswa yang terdaftar aktif pada tahun tersebut.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Print Styling -->
    <style>
        @media print {
            .main-header, .main-sidebar, .content-header, .small-box, #semester-chart-box, #prodi-chart-box, .box-tools, .btn, .no-print {
                display: none !important;
            }
            .content-wrapper, .right-side, .main-footer {
                margin-left: 0 !important;
                padding: 0 !important;
                min-height: 0 !important;
            }
            .box {
                border: none !important;
                box-shadow: none !important;
            }
            table {
                font-size: 10px !important;
                width: 100% !important;
            }
            th, td {
                padding: 3px 5px !important;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        $(function () {
            // -------------------------------------------------------------
            // 1. Inisialisasi Grafik Tren Semester (Bar Chart Chart.js)
            // -------------------------------------------------------------
            var semesterCanvas = $('#semesterBarChart').get(0).getContext('2d');
            var semesterChart = new Chart(semesterCanvas);

            var semesterData = {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Ganjil',
                        fillColor: 'rgba(60, 141, 188, 0.85)',
                        strokeColor: 'rgba(60, 141, 188, 1)',
                        pointColor: '#3c8dbc',
                        pointStrokeColor: 'rgba(60, 141, 188, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60, 141, 188, 1)',
                        data: @json($chartData['ganjil'])
                    },
                    {
                        label: 'Genap',
                        fillColor: 'rgba(0, 166, 90, 0.85)',
                        strokeColor: 'rgba(0, 166, 90, 1)',
                        pointColor: '#00a65a',
                        pointStrokeColor: 'rgba(0, 166, 90, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(0, 166, 90, 1)',
                        data: @json($chartData['genap'])
                    },
                    {
                        label: 'Pendek',
                        fillColor: 'rgba(243, 156, 18, 0.85)',
                        strokeColor: 'rgba(243, 156, 18, 1)',
                        pointColor: '#f39c12',
                        pointStrokeColor: 'rgba(243, 156, 18, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(243, 156, 18, 1)',
                        data: @json($chartData['pendek'])
                    }
                ]
            };

            var barChartOptions = {
                scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleGridLineColor: 'rgba(0,0,0,.06)',
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: false,
                barShowStroke: true,
                barStrokeWidth: 1,
                barValueSpacing: 6,
                barDatasetSpacing: 2,
                responsive: true,
                maintainAspectRatio: false
            };

            semesterChart.Bar(semesterData, barChartOptions);

            // -------------------------------------------------------------
            // 2. Inisialisasi Grafik Pertumbuhan per Prodi (Line Chart Chart.js)
            // -------------------------------------------------------------
            var prodiCanvas = $('#prodiLineChart').get(0).getContext('2d');
            var prodiChart = new Chart(prodiCanvas);

            var prodiData = {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'TRPL / TK',
                        fillColor: 'rgba(0, 115, 183, 0.1)',
                        strokeColor: '#0073b7',
                        pointColor: '#0073b7',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#0073b7',
                        data: @json($chartData['trpl'])
                    },
                    {
                        label: 'Teknik Industri',
                        fillColor: 'rgba(221, 75, 57, 0.1)',
                        strokeColor: '#dd4b39',
                        pointColor: '#dd4b39',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#dd4b39',
                        data: @json($chartData['ti'])
                    },
                    {
                        label: 'Farmasi',
                        fillColor: 'rgba(0, 166, 90, 0.1)',
                        strokeColor: '#00a65a',
                        pointColor: '#00a65a',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#00a65a',
                        data: @json($chartData['fa'])
                    },
                    {
                        label: 'TRL (Logistik)',
                        fillColor: 'rgba(96, 92, 168, 0.1)',
                        strokeColor: '#605ca8',
                        pointColor: '#605ca8',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#605ca8',
                        data: @json($chartData['trl'])
                    }
                ]
            };

            var lineChartOptions = {
                scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleGridLineColor: 'rgba(0,0,0,.06)',
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: false,
                bezierCurve: true,
                bezierCurveTension: 0.3,
                pointDot: true,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 12,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: false,
                responsive: true,
                maintainAspectRatio: false
            };

            prodiChart.Line(prodiData, lineChartOptions);
        });

        // -------------------------------------------------------------
        // 3. Fungsi Ekspor Tabel ke Format Excel (.xls)
        // -------------------------------------------------------------
        function exportTableToExcel(tableId, filename) {
            var table = document.getElementById(tableId);
            if (!table) return;

            var html = table.outerHTML;
            var uri = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(
                '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
                '<head>' +
                '<meta charset="utf-8">' +
                '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Rekap KRS</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->' +
                '<style>' +
                'table { border-collapse: collapse; width: 100%; }' +
                'th, td { border: 0.5pt solid #888888; text-align: center; vertical-align: middle; padding: 4px; }' +
                'th { background-color: #f2f2f2; font-weight: bold; }' +
                '</style>' +
                '</head>' +
                '<body>' +
                '<h3>Rekapitulasi KRS Mahasiswa Politeknik META Industri</h3>' +
                html +
                '</body>' +
                '</html>'
            );

            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            downloadLink.href = uri;
            downloadLink.download = (filename || 'Rekapitulasi_KRS') + '.xls';
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
@endsection
