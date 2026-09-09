@php
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $hariIndo = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];

    $formatTglIndo = function($tgl) use ($bulanIndo) {
        if (!$tgl) return '-';
        $time = strtotime($tgl);
        $d = date('j', $time);
        $m = (int)date('n', $time);
        $y = date('Y', $time);
        return $d . ' ' . ($bulanIndo[$m] ?? date('M', $time)) . ' ' . $y;
    };

    $today = date('Y-m-d');
    $currentDayName = $hariIndo[date('l')] ?? date('l');
    $currentDateFormatted = $currentDayName . ', ' . $formatTglIndo($today);

    $totalJadwal = isset($jadwal_prausta) ? count($jadwal_prausta) : 0;
    $totalPagesInit = (int) ceil($totalJadwal / 5);
    $initialPages = [];
    if ($totalPagesInit <= 7) {
        for ($p = 1; $p <= $totalPagesInit; $p++) {
            $initialPages[] = $p;
        }
    } else {
        $initialPages = [1, 2, 3, 4, 5, '...', $totalPagesInit];
    }
@endphp

<style>
    /* Minimal helper for table pagination hiding */
    .jadwal-item-row.hidden-item {
        display: none !important;
    }
</style>

{{-- 1. AdminLTE Header: Widget User & Info Boxes --}}
<div class="row">
    {{-- Widget User Profile (AdminLTE Standard) --}}
    <div class="col-md-6">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-navy">
                <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                <h5 class="widget-user-desc">Administrator PraUSTA</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-blue">{{ number_format($count_pkl_magang ?? 0) }}</h5>
                            <span class="description-text">PKL / MAGANG</span>
                        </div>
                    </div>
                    <div class="col-xs-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header text-green">{{ number_format($count_sempro ?? 0) }}</h5>
                            <span class="description-text">SEMPRO</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="description-block">
                            <h5 class="description-header text-purple">{{ number_format($count_ta_skripsi ?? 0) }}</h5>
                            <span class="description-text">TA / SKRIPSI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Boxes: Periode Akademik & Status Hari Ini --}}
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tahun Akademik</span>
                <span class="info-box-number">{{ $tahun->periode_tahun ?? '-' }}</span>
                <span class="info-box-text" style="margin-top: 2px;">
                    <span class="label {{ strtoupper($tipe->periode_tipe ?? '') == 'GANJIL' ? 'label-primary' : 'label-warning' }}">
                        {{ $tipe->periode_tipe ?? '-' }}
                    </span>
                </span>
            </div>
        </div>

        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Peserta Aktif</span>
                <span class="info-box-number">{{ number_format($count_total_prausta ?? 0) }}</span>
                <span class="info-box-text" style="color: #777;">
                    @if(isset($count_mhs_unik) && $count_mhs_unik > 0)
                        {{ $count_mhs_unik }} Mahasiswa Aktif
                    @else
                        Mahasiswa PraUSTA
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon {{ ($count_pending_val ?? 0) > 0 ? 'bg-yellow' : 'bg-green' }}">
                <i class="fa {{ ($count_pending_val ?? 0) > 0 ? 'fa-clock-o' : 'fa-check-circle' }}"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Menunggu Validasi</span>
                <span class="info-box-number">{{ number_format($count_pending_val ?? 0) }}</span>
                <span class="info-box-text" style="color: #777;">
                    {{ ($count_pending_val ?? 0) > 0 ? 'Perlu Tindakan BAAK' : 'Semua Tervalidasi' }}
                </span>
            </div>
        </div>

        <div class="info-box">
            <span class="info-box-icon bg-navy"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Hari Ini</span>
                <span class="info-box-number" style="font-size: 15px; margin-top: 3px;">{{ $currentDayName }}</span>
                <span class="info-box-text" style="color: #777;">{{ $formatTglIndo($today) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- 2. Callout Warning: Actionable Alert --}}
@if (($count_pending_val ?? 0) > 0)
    <div class="callout callout-warning">
        <h4><i class="icon fa fa-warning"></i> Perhatian: Mahasiswa Menunggu Validasi PraUSTA</h4>
        <p>
            Terdapat <strong>{{ number_format($count_pending_val) }} pengajuan</strong> mahasiswa yang memerlukan verifikasi kelengkapan berkas:
            PKL/Magang (<strong>{{ $pending_pkl }}</strong>),
            SEMPRO (<strong>{{ $pending_sempro }}</strong>),
            TA/Skripsi (<strong>{{ $pending_ta }}</strong>).
        </p>
        <div style="margin-top: 10px;">
            @if ($pending_pkl > 0)
                <a href="{{ url('validasi_pkl_magang') }}" class="btn btn-warning btn-xs btn-flat" style="margin-right: 5px; color: #fff; border: 1px solid #fff;">
                    <i class="fa fa-briefcase"></i> Validasi PKL ({{ $pending_pkl }})
                </a>
            @endif
            @if ($pending_sempro > 0)
                <a href="{{ url('validasi_sempro') }}" class="btn btn-warning btn-xs btn-flat" style="margin-right: 5px; color: #fff; border: 1px solid #fff;">
                    <i class="fa fa-file-text-o"></i> Validasi SEMPRO ({{ $pending_sempro }})
                </a>
            @endif
            @if ($pending_ta > 0)
                <a href="{{ url('validasi_ta_skripsi') }}" class="btn btn-warning btn-xs btn-flat" style="margin-right: 5px; color: #fff; border: 1px solid #fff;">
                    <i class="fa fa-graduation-cap"></i> Validasi TA/Skripsi ({{ $pending_ta }})
                </a>
            @endif
        </div>
    </div>
@endif

{{-- 3. Top 4 Small-Boxes (AdminLTE Signature KPI Widgets) --}}
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ number_format($count_pkl_magang ?? 0) }}</h3>
                <p>Peserta PKL & Magang</p>
            </div>
            <div class="icon">
                <i class="fa fa-briefcase"></i>
            </div>
            <a href="{{ url('validasi_pkl_magang') }}" class="small-box-footer">
                Kelola Validasi <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ number_format($count_sempro ?? 0) }}</h3>
                <p>Peserta SEMPRO</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-text-o"></i>
            </div>
            <a href="{{ url('validasi_sempro') }}" class="small-box-footer">
                Kelola Validasi <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ number_format($count_ta_skripsi ?? 0) }}</h3>
                <p>Peserta TA & Skripsi</p>
            </div>
            <div class="icon">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <a href="{{ url('validasi_ta_skripsi') }}" class="small-box-footer">
                Kelola Validasi <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box {{ ($count_pending_val ?? 0) > 0 ? 'bg-yellow' : 'bg-teal' }}">
            <div class="inner">
                <h3>{{ number_format($count_pending_val ?? 0) }}</h3>
                <p>Menunggu Validasi</p>
            </div>
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <a href="{{ url('validasi_pkl_magang') }}" class="small-box-footer">
                Buka Antrean <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- 4. Quick Actions: AdminLTE App Buttons --}}
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-th text-primary"></i> Jalan Pintas Menu PraUSTA</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <a class="btn btn-app" href="{{ url('waktu_pkl') }}">
            <i class="fa fa-calendar text-aqua"></i> Waktu PKL
        </a>
        <a class="btn btn-app" href="{{ url('waktu_sempro') }}">
            <i class="fa fa-calendar-check-o text-green"></i> Waktu SEMPRO
        </a>
        <a class="btn btn-app" href="{{ url('waktu_ta') }}">
            <i class="fa fa-calendar-plus-o text-purple"></i> Waktu TA
        </a>
        <a class="btn btn-app" href="{{ url('nilai_prausta') }}">
            <i class="fa fa-list-alt text-blue"></i> Nilai Hasil
        </a>
        <a class="btn btn-app" href="{{ url('bap_pkl_magang') }}">
            <i class="fa fa-file-pdf-o text-yellow"></i> BAP Sidang
        </a>
        <a class="btn btn-app" href="{{ url('export_data') }}">
            <i class="fa fa-file-excel-o text-teal"></i> Export Data
        </a>
    </div>
</div>

{{-- 5. Split Section: Monitoring Jadwal Pelaksanaan & Distribusi per Prodi --}}
<div class="row">
    {{-- Left Column: Monitoring Jadwal Pelaksanaan --}}
    <div class="col-md-7">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clock-o text-primary"></i> Monitoring Jadwal Pelaksanaan PraUSTA</h3>
                <div class="box-tools pull-right">
                    @if (isset($jadwal_prausta) && count($jadwal_prausta) > 0)
                        <div class="btn-group btn-group-xs" id="jadwal-filter-group" style="margin-right: 5px;">
                            <button type="button" class="btn btn-primary active filter-jadwal-btn" data-filter="all">Semua</button>
                            <button type="button" class="btn btn-default filter-jadwal-btn" data-filter="pkl">PKL</button>
                            <button type="button" class="btn btn-default filter-jadwal-btn" data-filter="sempro">SEMPRO</button>
                            <button type="button" class="btn btn-default filter-jadwal-btn" data-filter="ta">TA</button>
                        </div>
                    @endif
                    <a href="{{ url('waktu_pkl') }}" class="btn btn-default btn-xs" title="Kelola Jadwal"><i class="fa fa-cog"></i> Atur</a>
                </div>
            </div>
            <div class="box-body no-padding">
                @if (isset($jadwal_prausta) && count($jadwal_prausta) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="table-jadwal-monitoring" style="margin-bottom: 0;">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th style="width: 100px;">Kegiatan</th>
                                    <th>Program Studi</th>
                                    <th>Rentang Waktu</th>
                                    <th style="width: 120px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-jadwal-monitoring">
                                @foreach ($jadwal_prausta as $jw)
                                    @php
                                        $t1 = strtotime($jw->set_waktu_awal);
                                        $t2 = strtotime($jw->set_waktu_akhir);
                                        $isBerjalan = ($today >= $jw->set_waktu_awal && $today <= $jw->set_waktu_akhir);
                                        $isMendatang = ($today < $jw->set_waktu_awal);
                                        $isSelesai = ($today > $jw->set_waktu_akhir);

                                        $katGroup = 'other';
                                        if (in_array($jw->tipe_prausta, ['PKL', 'Magang', 'Magang 2'])) {
                                            $katGroup = 'pkl';
                                        } elseif ($jw->tipe_prausta == 'SEMPRO') {
                                            $katGroup = 'sempro';
                                        } elseif (in_array($jw->tipe_prausta, ['TA', 'Skripsi'])) {
                                            $katGroup = 'ta';
                                        }
                                    @endphp
                                    <tr class="jadwal-item-row {{ $loop->iteration > 5 ? 'hidden-item' : '' }}" data-kategori="{{ $katGroup }}" style="{{ $loop->iteration > 5 ? 'display: none !important;' : '' }}">
                                        <td style="vertical-align: middle;">
                                            @if ($jw->tipe_prausta == 'PKL')
                                                <span class="badge bg-blue"><i class="fa fa-briefcase"></i> PKL</span>
                                            @elseif ($jw->tipe_prausta == 'Magang')
                                                <span class="badge bg-purple"><i class="fa fa-building-o"></i> Magang</span>
                                            @elseif ($jw->tipe_prausta == 'Magang 2')
                                                <span class="badge bg-teal"><i class="fa fa-building"></i> Magang 2</span>
                                            @elseif ($jw->tipe_prausta == 'SEMPRO')
                                                <span class="badge bg-green"><i class="fa fa-file-text-o"></i> SEMPRO</span>
                                            @elseif ($jw->tipe_prausta == 'TA' || $jw->tipe_prausta == 'Skripsi')
                                                <span class="badge bg-maroon"><i class="fa fa-graduation-cap"></i> {{ $jw->tipe_prausta }}</span>
                                            @else
                                                <span class="badge bg-gray">{{ $jw->tipe_prausta }}</span>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <strong>{{ $jw->prodi }}</strong>
                                            @if (!empty($jw->konsentrasi))
                                                <div style="font-size: 11px; color: #777;">
                                                    {{ $jw->konsentrasi }}
                                                </div>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <div style="font-size: 12px;">
                                                <i class="fa fa-calendar text-muted"></i>
                                                {{ $formatTglIndo($jw->set_waktu_awal) }}
                                                <span class="text-muted">s/d</span>
                                                {{ $formatTglIndo($jw->set_waktu_akhir) }}
                                            </div>
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            @if ($isBerjalan)
                                                <span class="label label-success"><i class="fa fa-circle"></i> Berjalan</span>
                                            @elseif ($isMendatang)
                                                <span class="label label-warning"><i class="fa fa-circle"></i> Mendatang</span>
                                            @else
                                                <span class="label label-default"><i class="fa fa-circle"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding: 30px; text-align: center; color: #777;">
                        <i class="fa fa-calendar-times-o" style="font-size: 40px; color: #ccc; margin-bottom: 10px; display: block;"></i>
                        <p style="margin-bottom: 12px;">Belum ada jadwal pelaksanaan PraUSTA aktif yang diatur.</p>
                        <a href="{{ url('waktu_pkl') }}" class="btn btn-primary btn-sm btn-flat">
                            <i class="fa fa-plus-circle"></i> Atur Jadwal Sekarang
                        </a>
                    </div>
                @endif
            </div>
            @if (isset($jadwal_prausta) && count($jadwal_prausta) > 0)
                <div class="box-footer clearfix">
                    <span class="text-muted pull-left" style="font-size: 12px; line-height: 28px;" id="jadwal-page-info">
                        Menampilkan 1 - {{ min(5, $totalJadwal) }} dari {{ $totalJadwal }} jadwal
                    </span>
                    <ul class="pagination pagination-sm no-margin pull-right" id="jadwal-pagination">
                        @if ($totalPagesInit > 1)
                            <li class="disabled"><a href="javascript:void(0)" class="page-nav" data-page="1" title="Sebelumnya">&laquo;</a></li>
                            @foreach ($initialPages as $pg)
                                @if ($pg === '...')
                                    <li class="disabled"><span style="cursor: default;">...</span></li>
                                @else
                                    <li class="{{ $pg == 1 ? 'active' : '' }}">
                                        <a href="javascript:void(0)" class="page-nav" data-page="{{ $pg }}">{{ $pg }}</a>
                                    </li>
                                @endif
                            @endforeach
                            <li class="{{ $totalPagesInit <= 1 ? 'disabled' : '' }}"><a href="javascript:void(0)" class="page-nav" data-page="2" title="Berikutnya">&raquo;</a></li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: Distribusi Peserta per Program Studi --}}
    <div class="col-md-5">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pie-chart text-green"></i> Distribusi Peserta per Prodi</h3>
            </div>
            <div class="box-body no-padding">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="margin-bottom: 0;">
                        <thead>
                            <tr class="bg-gray-light">
                                <th>Program Studi</th>
                                <th style="width: 55px; text-align: center;" title="PKL & Magang">PKL</th>
                                <th style="width: 65px; text-align: center;" title="Seminar Proposal">SEMPRO</th>
                                <th style="width: 55px; text-align: center;" title="Tugas Akhir & Skripsi">TA</th>
                                <th style="width: 60px; text-align: center;" class="bg-gray">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $tot_all_pkl = 0;
                                $tot_all_sempro = 0;
                                $tot_all_ta = 0;
                                $tot_all = 0;
                            @endphp
                            @forelse ($rekap_prodi as $rk)
                                @php
                                    $tot_all_pkl += $rk->jml_pkl;
                                    $tot_all_sempro += $rk->jml_sempro;
                                    $tot_all_ta += $rk->jml_ta;
                                    $tot_all += $rk->jml_total;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $rk->nama_prodi }}</strong>
                                    </td>
                                    <td align="center">
                                        <span class="badge bg-blue">{{ $rk->jml_pkl }}</span>
                                    </td>
                                    <td align="center">
                                        <span class="badge bg-green">{{ $rk->jml_sempro }}</span>
                                    </td>
                                    <td align="center">
                                        <span class="badge bg-purple">{{ $rk->jml_ta }}</span>
                                    </td>
                                    <td align="center" style="font-weight: 700; background: #fdfdfd;">
                                        {{ $rk->jml_total }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center" style="padding: 20px; color: #777;">
                                        Belum ada data peserta PraUSTA aktif semester ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($rekap_prodi) > 0)
                            <tfoot>
                                <tr style="background: #f4f4f4; font-weight: 700;">
                                    <td>Total Keseluruhan</td>
                                    <td align="center" class="text-blue">{{ $tot_all_pkl }}</td>
                                    <td align="center" class="text-green">{{ $tot_all_sempro }}</td>
                                    <td align="center" class="text-purple">{{ $tot_all_ta }}</td>
                                    <td align="center" class="bg-gray">{{ $tot_all }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
            <div class="box-footer text-center">
                <a href="{{ url('nilai_prausta') }}" class="btn btn-default btn-xs btn-flat">
                    <i class="fa fa-list-alt"></i> Lihat Rekap Nilai PraUSTA Lengkap
                </a>
            </div>
        </div>
    </div>
</div>

{{-- 6. Bottom Split Section: Aktivitas Pendaftaran Terbaru & Pengumuman Kampus --}}
<div class="row">
    {{-- Left: Pendaftaran Terbaru --}}
    <div class="col-md-7">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user-plus text-aqua"></i> Pendaftaran Mahasiswa Terbaru</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info">6 Terkini</span>
                </div>
            </div>
            <div class="box-body no-padding">
                @if (isset($latest_prausta) && count($latest_prausta) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="margin-bottom: 0;">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th>Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>Kategori Kegiatan</th>
                                    <th style="width: 100px; text-align: center;">Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latest_prausta as $lp)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <strong>{{ $lp->nama }}</strong>
                                            <div style="font-size: 11px; color: #777;">
                                                <i class="fa fa-id-card-o"></i> {{ $lp->nim }}
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ $lp->prodi ?? '-' }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <span style="font-weight: 600;">{{ $lp->nama_prausta }}</span>
                                            <div style="font-size: 10px; color: #999;">
                                                {{ $lp->kode_prausta }}
                                            </div>
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            @if (strtoupper($lp->validasi_baak) == 'SUDAH')
                                                <span class="label label-success">
                                                    <i class="fa fa-check"></i> Sudah
                                                </span>
                                            @else
                                                <span class="label label-warning">
                                                    <i class="fa fa-clock-o"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding: 24px; text-align: center; color: #777;">
                        Belum ada data pendaftaran mahasiswa terbaru.
                    </div>
                @endif
            </div>
            <div class="box-footer text-center">
                <a href="{{ url('validasi_pkl_magang') }}" class="btn btn-default btn-xs btn-flat" style="margin: 2px;">Validasi PKL</a>
                <a href="{{ url('validasi_sempro') }}" class="btn btn-default btn-xs btn-flat" style="margin: 2px;">Validasi Sempro</a>
                <a href="{{ url('validasi_ta_skripsi') }}" class="btn btn-default btn-xs btn-flat" style="margin: 2px;">Validasi TA</a>
            </div>
        </div>
    </div>

    {{-- Right: Informasi & Pengumuman Terbaru --}}
    <div class="col-md-5">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bullhorn text-yellow"></i> Informasi Kampus Terkini</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                @if (isset($info) && count($info) > 0)
                    <ul class="products-list product-list-in-box">
                        @foreach ($info as $item)
                            <li class="item">
                                @if (!empty($item->file))
                                    <div class="product-img">
                                        <img class="img-circle" src="{{ asset('/data_file/' . $item->file) }}" alt="Info Image">
                                    </div>
                                @endif
                                <div class="product-info" style="{{ empty($item->file) ? 'margin-left: 0;' : '' }}">
                                    <a href="/lihat/{{ $item->id_informasi }}" class="product-title">
                                        {{ $item->judul }}
                                        <span class="label label-info pull-right">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        {{ $item->deskripsi }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div style="padding: 20px; text-align: center; color: #777;">
                        Tidak ada informasi atau pengumuman saat ini.
                    </div>
                @endif
            </div>
            <div class="box-footer text-center">
                <a href="/lihat_semua" class="uppercase">
                    Lihat Semua Informasi <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Vanilla JS Sliding-Window Pagination & Filter for Monitoring Table --}}
<script>
    (function() {
        function initJadwalPaging() {
            var rows = document.querySelectorAll('.jadwal-item-row');
            if (!rows || rows.length === 0) return;

            var perPage = 5;
            var currentPage = 1;
            var currentFilter = 'all';

            function getVisibleRows() {
                var filtered = [];
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    if (currentFilter === 'all' || row.getAttribute('data-kategori') === currentFilter) {
                        filtered.push(row);
                    }
                }
                return filtered;
            }

            function getPaginationRange(current, total) {
                if (total <= 7) {
                    var pages = [];
                    for (var i = 1; i <= total; i++) pages.push(i);
                    return pages;
                }
                if (current <= 4) {
                    return [1, 2, 3, 4, 5, '...', total];
                }
                if (current >= total - 3) {
                    return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
                }
                return [1, '...', current - 1, current, current + 1, '...', total];
            }

            function updateTable() {
                var filtered = getVisibleRows();
                var total = filtered.length;
                var totalPages = Math.ceil(total / perPage) || 1;

                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                var startIndex = (currentPage - 1) * perPage;
                var endIndex = startIndex + perPage;

                // Hide all rows
                for (var i = 0; i < rows.length; i++) {
                    rows[i].style.setProperty('display', 'none', 'important');
                    rows[i].classList.add('hidden-item');
                }

                // Show current page slice
                for (var j = startIndex; j < Math.min(endIndex, total); j++) {
                    filtered[j].style.removeProperty('display');
                    filtered[j].classList.remove('hidden-item');
                }

                // Update text
                var infoEl = document.getElementById('jadwal-page-info');
                if (infoEl) {
                    var displayStart = total > 0 ? (startIndex + 1) : 0;
                    var displayEnd = Math.min(endIndex, total);
                    var text = 'Menampilkan ' + displayStart + ' - ' + displayEnd + ' dari ' + total + ' jadwal';
                    if (currentFilter !== 'all') {
                        text += ' (Filter: ' + currentFilter.toUpperCase() + ')';
                    }
                    infoEl.textContent = text;
                }

                // Build sliding window pagination buttons
                var pagEl = document.getElementById('jadwal-pagination');
                if (!pagEl) return;

                pagEl.innerHTML = '';
                if (totalPages <= 1) return;

                // Prev button
                var prevLi = document.createElement('li');
                if (currentPage === 1) prevLi.className = 'disabled';
                var prevA = document.createElement('a');
                prevA.href = 'javascript:void(0)';
                prevA.innerHTML = '&laquo;';
                prevA.className = 'page-nav';
                prevA.title = 'Sebelumnya';
                prevA.onclick = function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                    }
                };
                prevLi.appendChild(prevA);
                pagEl.appendChild(prevLi);

                // Sliding window numbers
                var pageItems = getPaginationRange(currentPage, totalPages);
                for (var idx = 0; idx < pageItems.length; idx++) {
                    var p = pageItems[idx];
                    if (p === '...') {
                        var dotsLi = document.createElement('li');
                        dotsLi.className = 'disabled';
                        var dotsSpan = document.createElement('span');
                        dotsSpan.textContent = '...';
                        dotsSpan.style.cursor = 'default';
                        dotsLi.appendChild(dotsSpan);
                        pagEl.appendChild(dotsLi);
                    } else {
                        (function(pageNum) {
                            var li = document.createElement('li');
                            if (pageNum === currentPage) li.className = 'active';
                            var a = document.createElement('a');
                            a.href = 'javascript:void(0)';
                            a.textContent = pageNum;
                            a.className = 'page-nav';
                            a.onclick = function(e) {
                                e.preventDefault();
                                if (currentPage !== pageNum) {
                                    currentPage = pageNum;
                                    updateTable();
                                }
                            };
                            li.appendChild(a);
                            pagEl.appendChild(li);
                        })(p);
                    }
                }

                // Next button
                var nextLi = document.createElement('li');
                if (currentPage === totalPages) nextLi.className = 'disabled';
                var nextA = document.createElement('a');
                nextA.href = 'javascript:void(0)';
                nextA.innerHTML = '&raquo;';
                nextA.className = 'page-nav';
                nextA.title = 'Berikutnya';
                nextA.onclick = function(e) {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                    }
                };
                nextLi.appendChild(nextA);
                pagEl.appendChild(nextLi);
            }

            // Filter buttons event
            var filterBtns = document.querySelectorAll('.filter-jadwal-btn');
            for (var f = 0; f < filterBtns.length; f++) {
                (function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        for (var k = 0; k < filterBtns.length; k++) {
                            filterBtns[k].classList.remove('btn-primary', 'active');
                            filterBtns[k].classList.add('btn-default');
                        }
                        btn.classList.remove('btn-default');
                        btn.classList.add('btn-primary', 'active');

                        currentFilter = btn.getAttribute('data-filter') || 'all';
                        currentPage = 1;
                        updateTable();
                    });
                })(filterBtns[f]);
            }

            // Initial setup
            updateTable();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initJadwalPaging);
        } else {
            initJadwalPaging();
        }
        window.addEventListener('load', initJadwalPaging);
    })();
</script>
