@php
    $editProfileUrl = $mhs->id_mhs == null ? '/update/' . $mhs->idstudent : '/change/' . $mhs->id;
@endphp

<style>
    /* Styling pelengkap AdminLTE 2.4 untuk tampilan mobile & widget */
    .countdown-box-lte {
        background: #222d32;
        color: #00c0ef;
        border-radius: 3px;
        padding: 12px 10px;
        font-weight: 700;
        text-align: center;
        min-height: 65px;
        margin-top: 10px;
        margin-bottom: 8px;
    }

    .countdown-box-lte .digit {
        color: #ffffff;
        font-size: 13px;
        font-weight: normal;
    }

    .countdown-box-lte .judul {
        color: #f39c12;
        font-size: 11px;
        text-transform: uppercase;
        display: block;
        margin-top: 4px;
    }

    .profile-user-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin: 0 auto 10px;
        border: 3px solid #d2d6de;
    }

    .info-box {
        min-height: 90px;
        margin-bottom: 15px;
    }

    .info-box-icon {
        width: 90px;
        height: 90px;
        line-height: 90px;
        font-size: 40px;
    }

    .info-box-content {
        padding: 12px 15px;
        margin-left: 90px;
    }

    .info-box-text {
        text-transform: uppercase;
        font-size: 12px;
        color: #777;
        margin-bottom: 3px;
        display: block;
    }

    .info-box-number {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 3px;
        display: block;
    }

    .progress-description {
        font-size: 12px;
        color: #888;
        margin: 0;
        display: block;
    }

    @media (max-width: 767px) {
        .nav-tabs-custom > .nav-tabs {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            border-bottom: 1px solid #ddd;
        }

        .nav-tabs-custom > .nav-tabs > li {
            float: none;
            display: inline-block;
        }

        .info-box {
            min-height: 85px;
            margin-bottom: 12px;
        }

        .info-box-icon {
            width: 85px;
            height: 85px;
            line-height: 85px;
            font-size: 36px;
        }

        .info-box-content {
            padding: 12px 16px;
            margin-left: 85px;
        }

        .info-box-text {
            font-size: 11px;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }

        .info-box-number {
            font-size: 18px;
            margin-bottom: 2px;
        }

        .progress-description {
            font-size: 12px;
            white-space: normal;
        }
    }
</style>

<div class="row">
    {{-- KOLOM KIRI: PROFIL & DATA AKUN MAHASISWA --}}
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{ $mhs->photo_url }}" alt="Foto {{ $mhs->nama }}">

                <h3 class="profile-username text-center" style="font-size: 18px; font-weight: 700; margin-top: 5px; margin-bottom: 3px;">
                    {{ $mhs->nama }}
                </h3>

                <p class="text-muted text-center" style="margin-bottom: 8px;">
                    <strong>{{ $mhs->nim }}</strong> &middot; {{ $mhs->display_prodi }}
                </p>

                <div class="text-center" style="margin-bottom: 15px;">
                    <span class="label label-primary">{{ $mhs->kelas ?: '-' }}</span>
                    <span class="label label-info">Angkatan {{ $mhs->angkatan ?: '-' }}</span>
                </div>

                <ul class="list-group list-group-unbordered" style="margin-bottom: 15px;">
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap;"><i class="fa fa-credit-card text-muted margin-r-5"></i> Virtual Account</b>
                        <span class="text-bold text-primary text-right">{{ $mhs->virtual_account ?: '-' }}</span>
                    </li>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap;"><i class="fa fa-phone text-muted margin-r-5"></i> No. HP</b>
                        <span class="text-right">{{ $mhs->display_phone }}</span>
                    </li>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap; margin-right: 10px;"><i class="fa fa-envelope text-muted margin-r-5"></i> E-mail</b>
                        <span class="text-muted text-right" style="word-break: break-word;" title="{{ $mhs->display_email }}">{{ $mhs->display_email }}</span>
                    </li>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap;"><i class="fa fa-id-card-o text-muted margin-r-5"></i> NISN</b>
                        <span class="text-right">
                            {{ $mhs->nisn ?: '-' }}
                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalUpdateNisn{{ $mhs->idstudent }}" title="Edit NISN" style="margin-left: 4px;">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </span>
                    </li>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap;"><i class="fa fa-windows text-muted margin-r-5"></i> MS Teams User</b>
                        <span class="text-right">{{ $mhs->username ?: '-' }}</span>
                    </li>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <b style="white-space: nowrap;"><i class="fa fa-key text-muted margin-r-5"></i> MS Teams Pass</b>
                        <span class="text-right">{{ $mhs->password ?: '-' }}</span>
                    </li>
                </ul>

                <a href="/ganti_foto/{{ $mhs->nim }}" class="btn btn-primary btn-block">
                    <i class="fa fa-camera margin-r-5"></i> <b>Ganti Foto</b>
                </a>
                <a href="{{ $editProfileUrl }}" class="btn btn-default btn-block">
                    <i class="fa fa-edit margin-r-5"></i> <b>Edit No HP dan E-mail</b>
                </a>
            </div>
        </div>

        {{-- MODAL UPDATE NISN --}}
        <div class="modal fade" id="modalUpdateNisn{{ $mhs->idstudent }}" tabindex="-1" role="dialog" aria-labelledby="modalNisnTitle{{ $mhs->idstudent }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalNisnTitle{{ $mhs->idstudent }}">
                            <i class="fa fa-pencil text-yellow"></i> Perbarui NISN Mahasiswa
                        </h4>
                    </div>
                    <form action="/put_nisn/{{ $mhs->idstudent }}" method="post">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="inputNisn">Nomor Induk Siswa Nasional (NISN)</label>
                                <input class="form-control" id="inputNisn" type="number" name="nisn" value="{{ $mhs->nisn }}" required placeholder="Contoh: 0012345678">
                                <span class="help-block">Pastikan nomor NISN yang diinput sudah sesuai dengan data Dapodik / Ijazah.</span>
                            </div>
                            <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: SAMBUTAN, INFO-BOX METRIK, DAN TAB AKTIVITAS --}}
    <div class="col-md-8 col-sm-12 col-xs-12">
        {{-- BANNER SAMBUTAN STANDAR ADMINLTE --}}
        <div class="callout callout-info" style="margin-bottom: 15px;">
            <h4><i class="fa fa-graduation-cap"></i> Selamat Datang, {{ $mhs->nama }}!</h4>
            <p>
                Portal Akademik Mahasiswa ESIAM &mdash; Tahun Akademik: <strong>{{ $tahun ? $tahun->periode_tahun : '-' }} {{ $tipe ? $tipe->periode_tipe : '' }}</strong>.
                Pantau jadwal layanan akademik, status kartu rencana studi, serta paket matakuliah Anda di bawah ini.
            </p>
        </div>

        {{-- 4 INFO-BOX METRIK ADMINLTE --}}
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tahun Akademik</span>
                        <span class="info-box-number">{{ $tahun ? $tahun->periode_tahun : '-' }}</span>
                        <span class="progress-description text-muted">
                            <i class="fa fa-tag"></i> {{ $tipe ? $tipe->periode_tipe : 'Aktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Status KRS</span>
                        <span class="info-box-number">{{ $dashboard['krs_status'] }}</span>
                        <span class="progress-description text-muted">
                            <i class="fa fa-clock-o"></i> {{ $time && (int) $time->status === 1 ? 'Jadwal dibuka' : 'Belum tersedia' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clipboard"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Status EDOM</span>
                        <span class="info-box-number">{{ $dashboard['edom_status'] }}</span>
                        <span class="progress-description text-muted">
                            <i class="fa fa-check-square-o"></i> {{ $edom && (int) $edom->status === 1 ? 'Periode berjalan' : 'Belum dibuka' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-repeat"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Makul Mengulang</span>
                        <span class="info-box-number">{{ $dashboard['mengulang_count'] }}</span>
                        <span class="progress-description text-muted">
                            <i class="fa fa-info-circle"></i> {{ $dashboard['mengulang_count'] > 0 ? 'Perlu perbaikan nilai' : 'Nilai akademik aman' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- NAV-TABS CUSTOM ADMINLTE --}}
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_aktivitas" data-toggle="tab"><i class="fa fa-dashboard margin-r-5"></i> Aktivitas & Info</a></li>
                <li><a href="#tab_paket" data-toggle="tab"><i class="fa fa-book margin-r-5"></i> Paket Matakuliah ({{ $dashboard['paket_count'] }})</a></li>
                <li><a href="#tab_mengulang" data-toggle="tab"><i class="fa fa-warning margin-r-5"></i> Matakuliah Mengulang ({{ $dashboard['mengulang_count'] }})</a></li>
            </ul>

            <div class="tab-content">
                {{-- TAB 1: AKTIVITAS & INFORMASI --}}
                <div class="tab-pane active" id="tab_aktivitas">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Waktu Pengisian KRS</h3>
                                    <div class="box-tools pull-right">
                                        @if ($time && (int) $time->status === 1)
                                            <span class="label label-success">Aktif</span>
                                        @else
                                            <span class="label label-default">Tutup</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="box-body">
                                    <p class="text-muted" style="margin-bottom: 5px;">
                                        Pastikan pengisian KRS dilakukan tepat waktu sesuai jadwal perwalian.
                                    </p>
                                    <div id="krs-countdown" class="countdown-box-lte"
                                        data-target="{{ $time && (int) $time->status === 1 ? $time->waktu_akhir : '' }}"
                                        data-message="Menuju Penutupan Pengisian KRS">
                                        @if (!$time || (int) $time->status === 0)
                                            <span class="digit">Belum ada jadwal KRS aktif</span>
                                        @endif
                                    </div>
                                    <div class="text-center text-muted" style="font-size: 12px; margin-top: 6px;">
                                        <i class="fa fa-calendar"></i> {{ $dashboard['krs_schedule'] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-warning box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-clipboard"></i> Waktu Pengisian EDOM</h3>
                                    <div class="box-tools pull-right">
                                        @if ($edom && (int) $edom->status === 1)
                                            <span class="label label-warning">Aktif</span>
                                        @else
                                            <span class="label label-default">Tutup</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="box-body">
                                    <p class="text-muted" style="margin-bottom: 5px;">
                                        Selesaikan Evaluasi Dosen Oleh Mahasiswa (EDOM) sebelum batas akhir.
                                    </p>
                                    <div id="edom-countdown" class="countdown-box-lte"
                                        data-target="{{ $edom && (int) $edom->status === 1 ? $edom->waktu_akhir : '' }}"
                                        data-message="Menuju Penutupan Pengisian EDOM">
                                        @if (!$edom || (int) $edom->status === 0)
                                            <span class="digit">Belum ada jadwal EDOM aktif</span>
                                        @endif
                                    </div>
                                    <div class="text-center text-muted" style="font-size: 12px; margin-top: 6px;">
                                        <i class="fa fa-calendar"></i> {{ $dashboard['edom_schedule'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOX INFORMASI KAMPUS TERBARU --}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-bullhorn text-primary"></i> Informasi & Pengumuman Kampus</h3>
                            <div class="box-tools pull-right">
                                <span class="label label-primary">{{ $info->total() }} Pengumuman</span>
                            </div>
                        </div>
                        <div class="box-body">
                            @if ($info->count())
                                @foreach ($info as $item)
                                    <div class="post" style="border-bottom: 1px solid #f4f4f4; padding-bottom: 12px; margin-bottom: 12px;">
                                        <div class="user-block" style="margin-bottom: 6px;">
                                            <span class="username" style="margin-left: 0; font-size: 15px;">
                                                <a href="/lihat/{{ $item->id_informasi }}">{{ $item->judul }}</a>
                                            </span>
                                            <span class="description" style="margin-left: 0; font-size: 12px; color: #999;">
                                                <i class="fa fa-calendar-o"></i> {{ date('d-m-Y', strtotime($item->created_at)) }} &middot;
                                                <i class="fa fa-clock-o"></i> {{ $item->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-muted" style="margin-bottom: 0; font-size: 13px; line-height: 1.6;">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 200) }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted" style="padding: 30px 15px;">
                                    <i class="fa fa-info-circle fa-2x text-muted" style="margin-bottom: 8px;"></i>
                                    <p>Belum ada informasi terbaru untuk saat ini.</p>
                                </div>
                            @endif
                        </div>
                        <div class="box-footer clearfix">
                            <div class="pull-left">
                                @if ($dashboard['calendar_url'])
                                    <a href="{{ $dashboard['calendar_url'] }}" target="_blank" class="btn btn-default btn-sm">
                                        <i class="fa fa-download text-red"></i> Unduh Kalender Akademik
                                    </a>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a href="/lihat_semua" class="btn btn-primary btn-sm">
                                    <i class="fa fa-arrow-circle-right"></i> Lihat Semua Informasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: PAKET MATAKULIAH --}}
                <div class="tab-pane" id="tab_paket">
                    <div class="margin-bottom clearfix" style="margin-bottom: 15px;">
                        <span class="text-muted pull-left" style="line-height: 30px;">
                            Matakuliah kurikulum aktif yang menjadi acuan pengambilan studi Anda.
                        </span>
                        <span class="label label-primary pull-right" style="font-size: 13px; padding: 6px 10px;">
                            Total: {{ $dashboard['paket_count'] }} Matakuliah
                        </span>
                    </div>

                    {{-- TABEL DESKTOP & TABLET --}}
                    <div class="table-responsive hidden-xs">
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kurikulum</th>
                                    <th>Prodi</th>
                                    <th style="width: 70px; text-align: center;">Smt</th>
                                    <th style="width: 80px; text-align: center;">Angkatan</th>
                                    <th>Kode &amp; Matakuliah</th>
                                    <th style="width: 100px; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td align="center">{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_kurikulum }}</td>
                                        <td>{{ $item->prodi }}</td>
                                        <td align="center">{{ $item->semester }}</td>
                                        <td align="center">{{ $item->angkatan }}</td>
                                        <td>
                                            <strong>{{ $item->makul }}</strong><br>
                                            <small class="text-muted"><code>{{ $item->kode }}</code></small>
                                        </td>
                                        <td align="center">
                                            @if ($item->id_studentrecord != null)
                                                <span class="label label-success"><i class="fa fa-check"></i> Diambil</span>
                                            @else
                                                <span class="label label-warning"><i class="fa fa-clock-o"></i> Belum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- CARD LIST KHUSUS HP DENGAN ACCORDION PER SEMESTER & LIVE SEARCH --}}
                    <div class="visible-xs-block">
                        @php
                            $groupedData = $data->groupBy('semester');
                        @endphp

                        <div class="form-group" style="margin-bottom: 12px;">
                            <div class="input-group">
                                <input type="text" id="searchMobileCourse" class="form-control input-sm" placeholder="Cari nama atau kode matakuliah...">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                        </div>

                        <div class="box-group" id="accordionSemester">
                            @forelse ($groupedData as $semName => $semItems)
                                @php
                                    $semSlug = \Illuminate\Support\Str::slug($semName ?: 'lainnya');
                                    $isFirst = $loop->first;
                                    $diambilCount = $semItems->where('id_studentrecord', '!=', null)->count();
                                    $totalCount = $semItems->count();
                                    $formattedSem = \Illuminate\Support\Str::startsWith(trim($semName), 'Semester') ? trim($semName) : 'Semester ' . trim($semName);
                                @endphp
                                <div class="panel box {{ $isFirst ? 'box-primary' : 'box-default' }} sem-panel" style="margin-bottom: 8px; border: 1px solid #d2d6de;">
                                    <div class="box-header with-border" style="padding: 10px 12px; background: #fafafa;">
                                        <h4 class="box-title" style="width: 100%; margin: 0;">
                                            <a data-toggle="collapse" data-parent="#accordionSemester" href="#collapseSem{{ $semSlug }}"
                                               style="display: flex; justify-content: space-between; align-items: center; color: #333; text-decoration: none; font-size: 13px;">
                                                <span>
                                                    <i class="fa {{ $isFirst ? 'fa-folder-open' : 'fa-folder' }} text-primary margin-r-5 sem-icon"></i>
                                                    <strong>{{ $formattedSem }}</strong>
                                                    <small class="text-muted" style="margin-left: 3px;">({{ $totalCount }} Makul)</small>
                                                </span>
                                                <span>
                                                    @if ($diambilCount == $totalCount && $totalCount > 0)
                                                        <span class="label label-success" style="font-size: 10px;">{{ $diambilCount }}/{{ $totalCount }} Diambil</span>
                                                    @elseif ($diambilCount > 0)
                                                        <span class="label label-info" style="font-size: 10px;">{{ $diambilCount }}/{{ $totalCount }} Diambil</span>
                                                    @else
                                                        <span class="label label-default" style="font-size: 10px;">{{ $diambilCount }}/{{ $totalCount }}</span>
                                                    @endif
                                                    <i class="fa fa-chevron-down text-muted" style="font-size: 10px; margin-left: 5px;"></i>
                                                </span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSem{{ $semSlug }}" class="panel-collapse collapse {{ $isFirst ? 'in' : '' }}">
                                        <div class="box-body" style="padding: 8px 10px 4px; background: #fdfdfd;">
                                            @foreach ($semItems as $item)
                                                @php
                                                    $semText = \Illuminate\Support\Str::startsWith(trim($item->semester), 'Semester') ? trim($item->semester) : 'Semester ' . trim($item->semester);
                                                @endphp
                                                <div class="box box-solid box-default course-card-item" data-search="{{ strtolower($item->makul . ' ' . $item->kode) }}" style="margin-bottom: 8px; border: 1px solid #e5e8ec;">
                                                    <div class="box-body" style="padding: 10px 12px;">
                                                        <div class="clearfix">
                                                            <span class="pull-right">
                                                                @if ($item->id_studentrecord != null)
                                                                    <span class="label label-success"><i class="fa fa-check"></i> Diambil</span>
                                                                @else
                                                                    <span class="label label-warning"><i class="fa fa-clock-o"></i> Belum</span>
                                                                @endif
                                                            </span>
                                                            <strong style="font-size: 14px; color: #333;">{{ $item->makul }}</strong>
                                                            <div class="text-muted" style="font-size: 12px; margin-top: 3px;">
                                                                <code>{{ $item->kode }}</code> &middot; {{ $semText }} &middot; Angkatan {{ $item->angkatan }}
                                                            </div>
                                                        </div>
                                                        <div style="font-size: 11px; color: #777; margin-top: 6px; border-top: 1px dashed #eee; padding-top: 5px;">
                                                            <i class="fa fa-book text-muted"></i> {{ $item->nama_kurikulum }} | {{ $item->prodi }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted" style="padding: 20px;">
                                    <i class="fa fa-book fa-2x"></i>
                                    <p style="margin-top: 8px;">Belum ada paket matakuliah.</p>
                                </div>
                            @endforelse
                        </div>

                        <div id="noCourseSearchResult" class="text-center text-muted" style="display: none; padding: 20px;">
                            <i class="fa fa-search fa-2x text-muted" style="margin-bottom: 5px;"></i>
                            <p>Matakuliah tidak ditemukan.</p>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: MATAKULIAH MENGULANG --}}
                <div class="tab-pane" id="tab_mengulang">
                    <div class="margin-bottom clearfix" style="margin-bottom: 15px;">
                        <span class="text-muted pull-left" style="line-height: 30px;">
                            Daftar matakuliah dengan nilai (D / E) yang wajib diulang pada semester berikutnya.
                        </span>
                        <span class="label label-danger pull-right" style="font-size: 13px; padding: 6px 10px;">
                            Total: {{ $dashboard['mengulang_count'] }} Matakuliah
                        </span>
                    </div>

                    {{-- TABEL DESKTOP & TABLET --}}
                    <div class="table-responsive hidden-xs">
                        <table id="example3" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th style="width: 50px; text-align: center;">No</th>
                                    <th>Kurikulum</th>
                                    <th>Prodi</th>
                                    <th style="width: 70px; text-align: center;">Smt</th>
                                    <th style="width: 80px; text-align: center;">Angkatan</th>
                                    <th>Kode &amp; Matakuliah</th>
                                    <th style="width: 80px; text-align: center;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_mengulang as $index => $item)
                                    <tr>
                                        <td align="center">{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_kurikulum }}</td>
                                        <td>{{ $item->prodi }}</td>
                                        <td align="center">{{ $item->semester }}</td>
                                        <td align="center">{{ $item->angkatan }}</td>
                                        <td>
                                            <strong>{{ $item->makul }}</strong><br>
                                            <small class="text-muted"><code>{{ $item->kode }}</code></small>
                                        </td>
                                        <td align="center">
                                            <span class="label label-danger" style="font-size: 13px; padding: 4px 8px;">{{ $item->nilai_AKHIR }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- CARD LIST KHUSUS HP (RESPONSIF MOBILE TANPA SCROLL HORIZONTAL) --}}
                    <div class="visible-xs-block">
                        @forelse ($data_mengulang as $item)
                            <div class="box box-solid box-danger" style="margin-bottom: 10px;">
                                <div class="box-body" style="padding: 10px 12px;">
                                    <div class="clearfix">
                                        <span class="pull-right label label-danger" style="font-size: 14px; font-weight: 700; padding: 4px 8px;">
                                            Nilai: {{ $item->nilai_AKHIR }}
                                        </span>
                                        <strong style="font-size: 14px; color: #333;">{{ $item->makul }}</strong>
                                        <div class="text-muted" style="font-size: 12px; margin-top: 3px;">
                                            <code>{{ $item->kode }}</code> &middot; Semester {{ $item->semester }} &middot; Angkatan {{ $item->angkatan }}
                                        </div>
                                    </div>
                                    <div style="font-size: 11px; color: #777; margin-top: 6px; border-top: 1px dashed #eee; padding-top: 5px;">
                                        <i class="fa fa-book text-muted"></i> {{ $item->nama_kurikulum }} | {{ $item->prodi }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-success" style="margin-top: 10px;">
                                <i class="fa fa-check-circle margin-r-5"></i>
                                <strong>Alhamdulillah!</strong> Tidak ada matakuliah yang wajib diulang. Semua matakuliah Anda lulus dengan baik.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function initCountdown(elementId) {
            var el = document.getElementById(elementId);
            if (!el) {
                return;
            }

            var target = el.getAttribute('data-target');
            var message = el.getAttribute('data-message') || '';
            if (!target) {
                return;
            }

            var targetDate = new Date(target).getTime();
            if (isNaN(targetDate)) {
                return;
            }

            var render = function() {
                var now = new Date().getTime();
                var diff = Math.floor((targetDate - now) / 1000);

                if (diff <= 0) {
                    el.innerHTML = "<span class='judul text-yellow'>Waktu layanan telah berakhir</span>";
                    return;
                }

                var days = parseInt(diff / 86400, 10);
                diff = diff % 86400;
                var hours = parseInt(diff / 3600, 10);
                diff = diff % 3600;
                var minutes = parseInt(diff / 60, 10);
                var seconds = parseInt(diff % 60, 10);

                el.innerHTML = days + " <span class='digit'>hari</span> " +
                    hours + " <span class='digit'>jam</span> " +
                    minutes + " <span class='digit'>menit</span> " +
                    seconds + " <span class='digit'>detik</span>" +
                    "<span class='judul'>" + message + "</span>";
            };

            render();
            setInterval(render, 1000);
        }

        initCountdown('krs-countdown');
        initCountdown('edom-countdown');

        // Live Search Matakuliah di Mobile
        var searchInput = document.getElementById('searchMobileCourse');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                var query = this.value.toLowerCase().trim();
                var items = document.querySelectorAll('.course-card-item');
                var panels = document.querySelectorAll('.sem-panel');
                var totalMatches = 0;

                items.forEach(function(item) {
                    var searchData = item.getAttribute('data-search') || '';
                    if (!query || searchData.indexOf(query) !== -1) {
                        item.style.display = '';
                        totalMatches++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                panels.forEach(function(panel) {
                    var visibleItems = panel.querySelectorAll('.course-card-item:not([style*="display: none"])');
                    var collapse = panel.querySelector('.panel-collapse');
                    if (query) {
                        if (visibleItems.length > 0) {
                            panel.style.display = '';
                            if (collapse && typeof $ !== 'undefined') {
                                $(collapse).collapse('show');
                            }
                        } else {
                            panel.style.display = 'none';
                        }
                    } else {
                        panel.style.display = '';
                    }
                });

                var noResult = document.getElementById('noCourseSearchResult');
                if (noResult) {
                    noResult.style.display = (query && totalMatches === 0) ? '' : 'none';
                }
            });
        }

        // Toggle Folder Icon saat Accordion dibuka/ditutup
        if (typeof $ !== 'undefined') {
            $('#accordionSemester').on('shown.bs.collapse', function(e) {
                $(e.target).closest('.panel').find('.sem-icon').removeClass('fa-folder').addClass('fa-folder-open');
            });
            $('#accordionSemester').on('hidden.bs.collapse', function(e) {
                $(e.target).closest('.panel').find('.sem-icon').removeClass('fa-folder-open').addClass('fa-folder');
            });
        }
    })();
</script>
