@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @php
            $total_sks = 0;
            $rps_selesai = 0;
            $uts_terunggah = 0;
            $uas_terunggah = 0;
            $total_pertemuan_terisi = 0;
            $total_makul = count($makul);
            foreach ($makul as $item) {
                $sks_item = ($item->akt_sks_teori ?? 0) + ($item->akt_sks_praktek ?? 0);
                $total_sks += $sks_item;
                $total_pertemuan_terisi += (int)($item->total_bap ?? 0);
                if (!empty($item->id_rps)) {
                    $rps_selesai++;
                }
                if (!empty($item->soal_uts)) {
                    $uts_terunggah++;
                }
                if (!empty($item->soal_uas)) {
                    $uas_terunggah++;
                }
            }
            $persen_rps = $total_makul > 0 ? round(($rps_selesai / $total_makul) * 100) : 0;
        @endphp

        <!-- 1. WIDGET STATISTIK (INFO-BOX ADMINLTE) -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Matakuliah</span>
                        <span class="info-box-number">{{ $total_makul }} <small>Kelas/MK</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total SKS Diampu</span>
                        <span class="info-box-number">{{ $total_sks }} <small>SKS</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-file-text-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kelengkapan RPS</span>
                        <span class="info-box-number">{{ $rps_selesai }} / {{ $total_makul }} <small class="text-muted">({{ $persen_rps }}%)</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-cloud-upload"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Soal Terunggah</span>
                        <span class="info-box-number">UTS: {{ $uts_terunggah }} &bull; UAS: {{ $uas_terunggah }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. FILTER PERIODE AKADEMIK -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode Akademik</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ url('filter_makul_diampu_kprd') }}" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="form-group" style="margin-right: 15px;">
                        <label for="id_periodetahun" style="margin-right: 5px;">Tahun Akademik:</label>
                        <select name="id_periodetahun" id="id_periodetahun" class="form-control input-sm" required>
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($thn as $t)
                                <option value="{{ $t->id_periodetahun }}" {{ (isset($idperiodetahun) && $idperiodetahun == $t->id_periodetahun) ? 'selected' : '' }}>
                                    {{ $t->periode_tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-right: 15px;">
                        <label for="id_periodetipe" style="margin-right: 5px;">Semester / Tipe:</label>
                        <select name="id_periodetipe" id="id_periodetipe" class="form-control input-sm" required>
                            <option value="">-- Pilih Tipe --</option>
                            @foreach ($tp as $tipe)
                                <option value="{{ $tipe->id_periodetipe }}" {{ (isset($idperiodetipe) && $idperiodetipe == $tipe->id_periodetipe) ? 'selected' : '' }}>
                                    {{ $tipe->periode_tipe }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-flat">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ url('makul_diampu_kprd') }}" class="btn btn-default btn-sm btn-flat" title="Kembali ke Periode Aktif">
                        <i class="fa fa-refresh"></i> Periode Aktif
                    </a>
                </form>
            </div>
        </div>

        <!-- 3. TABEL DATA MATAKULIAH -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-th-list"></i> Data Matakuliah Diampu (Kaprodi)
                    <span class="text-muted" style="font-size: 14px; font-weight: normal; margin-left: 8px;">
                        Periode: <b>{{ $nama_periodetahun }} - {{ $nama_periodetipe }}</b>
                    </span>
                </h3>
                <div class="box-tools pull-right">
                    <span class="label label-primary">{{ $total_makul }} Matakuliah</span>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="example8" class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="4%" style="vertical-align: middle;">No</th>
                            <th class="text-center" width="28%" style="vertical-align: middle;">Matakuliah</th>
                            <th class="text-center" width="16%" style="vertical-align: middle;">Kelas & Prodi</th>
                            <th class="text-center" width="16%" style="vertical-align: middle;">Jadwal & Ruang</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Status Soal (UTS / UAS)</th>
                            <th class="text-center" width="16%" style="vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($makul as $item)
                            @php
                                $sks = ($item->akt_sks_teori ?? 0) + ($item->akt_sks_praktek ?? 0);
                                $bap_count = (int)($item->total_bap ?? 0);
                                $bap_pct = min(100, round(($bap_count / 16) * 100));
                                $bap_badge_class = 'label-danger';
                                $bap_bar_class = 'progress-bar-danger';
                                if ($bap_count >= 14) {
                                    $bap_badge_class = 'label-success';
                                    $bap_bar_class = 'progress-bar-success';
                                } elseif ($bap_count >= 7) {
                                    $bap_badge_class = 'label-primary';
                                    $bap_bar_class = 'progress-bar-primary';
                                } elseif ($bap_count > 0) {
                                    $bap_badge_class = 'label-warning';
                                    $bap_bar_class = 'progress-bar-warning';
                                }
                            @endphp
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $no++ }}</td>
                                <td style="vertical-align: middle;">
                                    <div style="font-size: 15px; font-weight: bold; color: #3c8dbc; margin-bottom: 4px;">
                                        {{ $item->makul }}
                                    </div>
                                    <div style="margin-bottom: 4px;">
                                        <span class="label label-default" title="Kode Matakuliah"><i class="fa fa-barcode"></i> {{ $item->kode }}</span>
                                        @if($sks > 0)
                                            <span class="label label-info" title="Jumlah SKS"><i class="fa fa-hourglass-end"></i> {{ $sks }} SKS</span>
                                        @endif
                                        @if(!empty($item->semester))
                                            <span class="label label-warning" title="Semester"><i class="fa fa-level-up"></i> Smt {{ $item->semester }}</span>
                                        @endif
                                        <span class="label {{ $bap_badge_class }}" title="Pertemuan Terlaksana: {{ $bap_count }} dari 16 pertemuan">
                                            <i class="fa fa-calendar-check-o"></i> {{ $bap_count }}/16 BAP
                                        </span>
                                    </div>
                                    <div class="progress progress-xs" style="margin-top: 5px; margin-bottom: 2px; height: 5px; background: #e8e8e8;" title="Progres Perkuliahan: {{ $bap_count }}/16 Pertemuan ({{ $bap_pct }}%)">
                                        <div class="progress-bar {{ $bap_bar_class }}" style="width: {{ $bap_pct }}%"></div>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div style="margin-bottom: 3px;">
                                        <span class="label label-primary" title="Program Studi">{{ $item->prodi }}</span>
                                        <span class="label label-success" title="Kelas"><i class="fa fa-users"></i> {{ $item->kelas }}</span>
                                    </div>

                                    @if(isset($item->details) && count($item->details) > 0)
                                        <div style="margin-top: 5px; font-size: 11px; color: #555;">
                                            <i class="fa fa-angle-right"></i> <b>Konsentrasi:</b>
                                            <ul style="padding-left: 15px; margin-bottom: 0;">
                                                @foreach ($item->details as $detail)
                                                    <li>{{ $detail['konsentrasi'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td style="vertical-align: middle;">
                                    <div style="margin-bottom: 3px;">
                                        <i class="fa fa-calendar text-blue"></i> {{ $item->hari }}
                                    </div>
                                    <div style="margin-bottom: 3px;">
                                        <i class="fa fa-clock-o text-green"></i> {{ $item->jam }}
                                    </div>
                                    <div>
                                        <i class="fa fa-map-marker text-red"></i> <b>{{ $item->nama_ruangan }}</b>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <!-- UTS SECTION -->
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; padding-bottom: 5px; border-bottom: 1px dashed #e0e0e0;">
                                        <div>
                                            <span class="label label-default" style="font-size: 11px;">UTS</span>
                                            @if ($item->soal_uts == null)
                                                <span class="text-muted" style="font-size: 11px; margin-left: 4px;">Belum upload</span>
                                            @else
                                                @if ($item->validasi_uts == 'SUDAH')
                                                    <span class="label label-success" title="Telah Divalidasi" style="margin-left: 4px;"><i class="fa fa-check"></i> Valid</span>
                                                @elseif ($item->komentar_uts != null)
                                                    <button type="button" class="btn btn-danger btn-xs btn-flat" data-toggle="modal" data-target="#modalTambahKomentarUts{{ $item->id_soal }}" title="Lihat Catatan Revisi" style="margin-left: 4px;">
                                                        <i class="fa fa-comment"></i> Revisi
                                                    </button>
                                                @else
                                                    <span class="label label-warning" title="Menunggu Validasi" style="margin-left: 4px;"><i class="fa fa-hourglass-half"></i> Menunggu</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            @if ($item->soal_uts == null)
                                                <button class="btn btn-default btn-xs btn-flat" data-toggle="modal" data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}" title="Upload Soal UTS">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            @else
                                                <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}" target="_blank" class="btn btn-primary btn-xs btn-flat" title="Lihat Berkas Soal UTS">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <button class="btn btn-warning btn-xs btn-flat" data-toggle="modal" data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}" title="Edit Soal UTS">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- UAS SECTION -->
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <div>
                                            <span class="label label-default" style="font-size: 11px;">UAS</span>
                                            @if ($item->soal_uas == null)
                                                <span class="text-muted" style="font-size: 11px; margin-left: 4px;">Belum upload</span>
                                            @else
                                                @if ($item->validasi_uas == 'SUDAH')
                                                    <span class="label label-success" title="Telah Divalidasi" style="margin-left: 4px;"><i class="fa fa-check"></i> Valid</span>
                                                @elseif ($item->komentar_uas != null)
                                                    <button type="button" class="btn btn-danger btn-xs btn-flat" data-toggle="modal" data-target="#modalTambahKomentarUas{{ $item->id_soal }}" title="Lihat Catatan Revisi" style="margin-left: 4px;">
                                                        <i class="fa fa-comment"></i> Revisi
                                                    </button>
                                                @else
                                                    <span class="label label-warning" title="Menunggu Validasi" style="margin-left: 4px;"><i class="fa fa-hourglass-half"></i> Menunggu</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            @if ($item->soal_uas == null)
                                                <button class="btn btn-default btn-xs btn-flat" data-toggle="modal" data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}" title="Upload Soal UAS">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            @else
                                                <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}" target="_blank" class="btn btn-primary btn-xs btn-flat" title="Lihat Berkas Soal UAS">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <button class="btn btn-warning btn-xs btn-flat" data-toggle="modal" data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}" title="Edit Soal UAS">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center" style="vertical-align: middle;">
                                    <div style="margin-bottom: 4px;">
                                        @if ($item->id_rps == null)
                                            <a href="/entri_rps_kprd/{{ $item->id_kurperiode }}" class="btn btn-success btn-xs btn-flat btn-block" title="Input RPS Baru">
                                                <i class="fa fa-plus"></i> Input RPS
                                            </a>
                                        @else
                                            <div class="btn-group btn-group-justified">
                                                <a href="/edit_rps_kprd/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs btn-flat" title="Edit RPS"><i class="fa fa-pencil"></i> RPS</a>
                                                <a href="/cekmhs_dsn_kprd/{{ $item->id_kurperiode }}" class="btn btn-primary btn-xs btn-flat" title="Entri Nilai Mahasiswa"><i class="fa fa-list-ol"></i> Nilai</a>
                                                <a href="/entri_bap_kprd/{{ $item->id_kurperiode }}" class="btn btn-warning btn-xs btn-flat" title="Berita Acara Perkuliahan: {{ $bap_count }}/16 Terisi"><i class="fa fa-newspaper-o"></i> BAP ({{ $bap_count }})</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="btn-group btn-group-justified">
                                        <a href="/export_xlsnilai_kprd/{{ $item->id_kurperiode }}" class="btn btn-default btn-xs btn-flat" title="Unduh Rekap Nilai Excel">
                                            <i class="fa fa-file-excel-o text-green"></i> Excel
                                        </a>
                                        <a href="/unduh_pdf_nilai_kprd/{{ $item->id_kurperiode }}" class="btn btn-default btn-xs btn-flat" title="Unduh Rekap Nilai PDF">
                                            <i class="fa fa-file-pdf-o text-red"></i> PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. MODALS (Ditempatkan di luar table agar hierarki DOM rapi dan valid) -->
        @foreach ($makul as $item)
            <!-- Modal Upload UTS -->
            <div class="modal fade" id="modalUploadSoalUts{{ $item->id_kurperiode }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload Soal UTS - {{ $item->makul }}</h4>
                        </div>
                        <form action="{{ url('simpan_soal_uts_dsn_kprd') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                <div class="form-group">
                                    <label>Tipe Ujian <span class="text-danger">*</span></label>
                                    <select name="tipe_ujian_uts" class="form-control" required>
                                        <option value="TATAP MUKA" {{ $item->tipe_ujian_uts == 'TATAP MUKA' ? 'selected' : '' }}>TATAP MUKA</option>
                                        <option value="TAKE HOME" {{ $item->tipe_ujian_uts == 'TAKE HOME' ? 'selected' : '' }}>TAKE HOME</option>
                                        <option value="PROJECT" {{ $item->tipe_ujian_uts == 'PROJECT' ? 'selected' : '' }}>PROJECT</option>
                                        <option value="PRAKTIKUM" {{ $item->tipe_ujian_uts == 'PRAKTIKUM' ? 'selected' : '' }}>PRAKTIKUM</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>File Soal UTS (.pdf) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="soal_uts" accept="application/pdf" {{ $item->soal_uts ? '' : 'required' }}>
                                    <span class="help-block"><i class="fa fa-info-circle"></i> Maksimal ukuran 4 MB dengan format file (.pdf)</span>
                                    @if ($item->soal_uts)
                                        <p class="text-success"><i class="fa fa-check-circle"></i> File tersimpan saat ini: <b>{{ $item->soal_uts }}</b></p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Cetak Soal <span class="text-danger">*</span></label>
                                    <select name="cetak_soal_uts" class="form-control" required>
                                        <option value="YA" {{ $item->cetak_soal_uts == 'YA' ? 'selected' : '' }}>YA</option>
                                        <option value="TIDAK" {{ $item->cetak_soal_uts == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan Soal UTS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Upload UAS -->
            <div class="modal fade" id="modalUploadSoalUas{{ $item->id_kurperiode }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload Soal UAS - {{ $item->makul }}</h4>
                        </div>
                        <form action="{{ url('simpan_soal_uas_dsn_kprd') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                <div class="form-group">
                                    <label>Tipe Ujian <span class="text-danger">*</span></label>
                                    <select name="tipe_ujian_uas" class="form-control" required>
                                        <option value="TATAP MUKA" {{ $item->tipe_ujian_uas == 'TATAP MUKA' ? 'selected' : '' }}>TATAP MUKA</option>
                                        <option value="TAKE HOME" {{ $item->tipe_ujian_uas == 'TAKE HOME' ? 'selected' : '' }}>TAKE HOME</option>
                                        <option value="PROJECT" {{ $item->tipe_ujian_uas == 'PROJECT' ? 'selected' : '' }}>PROJECT</option>
                                        <option value="PRAKTIKUM" {{ $item->tipe_ujian_uas == 'PRAKTIKUM' ? 'selected' : '' }}>PRAKTIKUM</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>File Soal UAS (.pdf) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="soal_uas" accept="application/pdf" {{ $item->soal_uas ? '' : 'required' }}>
                                    <span class="help-block"><i class="fa fa-info-circle"></i> Maksimal ukuran 4 MB dengan format file (.pdf)</span>
                                    @if ($item->soal_uas)
                                        <p class="text-success"><i class="fa fa-check-circle"></i> File tersimpan saat ini: <b>{{ $item->soal_uas }}</b></p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Cetak Soal <span class="text-danger">*</span></label>
                                    <select name="cetak_soal_uas" class="form-control" required>
                                        <option value="YA" {{ $item->cetak_soal_uas == 'YA' ? 'selected' : '' }}>YA</option>
                                        <option value="TIDAK" {{ $item->cetak_soal_uas == 'TIDAK' ? 'selected' : '' }}>TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Simpan Soal UAS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Komentar UTS -->
            @if (!empty($item->id_soal) && !empty($item->komentar_uts))
                <div class="modal fade" id="modalTambahKomentarUts{{ $item->id_soal }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-red">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa fa-comments"></i> Catatan Revisi Soal UTS - {{ $item->makul }}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="callout callout-warning">
                                    <h4><i class="icon fa fa-warning"></i> Perhatian</h4>
                                    <p>Mohon perbaiki berkas soal UTS Anda sesuai dengan catatan validator di bawah ini, kemudian upload kembali.</p>
                                </div>
                                <div class="form-group">
                                    <label>Isi Catatan Validator:</label>
                                    <textarea class="form-control" readonly rows="5" style="background-color: #f9f9f9;">{{ $item->komentar_uts }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Komentar UAS -->
            @if (!empty($item->id_soal) && !empty($item->komentar_uas))
                <div class="modal fade" id="modalTambahKomentarUas{{ $item->id_soal }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-red">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa fa-comments"></i> Catatan Revisi Soal UAS - {{ $item->makul }}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="callout callout-warning">
                                    <h4><i class="icon fa fa-warning"></i> Perhatian</h4>
                                    <p>Mohon perbaiki berkas soal UAS Anda sesuai dengan catatan validator di bawah ini, kemudian upload kembali.</p>
                                </div>
                                <div class="form-group">
                                    <label>Isi Catatan Validator:</label>
                                    <textarea class="form-control" readonly rows="5" style="background-color: #f9f9f9;">{{ $item->komentar_uas }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </section>
@endsection
