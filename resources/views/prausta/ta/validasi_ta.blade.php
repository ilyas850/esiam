@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Validasi TA & Skripsi
            <small>Validasi Akhir BAAK Tugas Akhir & Skripsi</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#">PraUSTA</a></li>
            <li class="active">Validasi Tugas Akhir</li>
        </ol>
    </section>

    <section class="content">
        @php
            $total = $data->count();
            $sudah = $data->where('validasi_baak', 'SUDAH')->count();
            $siap = $data->filter(function($i) {
                return $i->validasi_baak == 'BELUM' && !empty($i->nilai_huruf);
            })->count();
            $belum_lengkap = $data->filter(function($i) {
                return empty($i->nilai_huruf);
            })->count();
        @endphp

        <!-- Nav Tabs AdminLTE 2.4 -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="{{ url('data_val_ta_mahasiswa') }}">
                        <i class="fa fa-book text-blue"></i> <b>Validasi Tugas Akhir</b>
                        <span class="label label-primary" style="margin-left: 5px;">{{ $total }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('data_val_skripsi_mahasiswa') }}">
                        <i class="fa fa-graduation-cap text-green"></i> <b>Validasi Skripsi</b>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="padding: 15px 12px;">
                <!-- Ringkasan Statistik Metrik (Info-Box AdminLTE) -->
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box stat-card bg-aqua" onclick="filterByStatus('ALL')" style="cursor: pointer;" title="Klik untuk menampilkan semua data">
                            <span class="info-box-icon"><i class="fa fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Mahasiswa</span>
                                <span class="info-box-number" style="font-size: 24px;">{{ $total }}</span>
                                <div class="progress" style="height: 2px; margin: 5px 0; background: rgba(255,255,255,0.3);">
                                    <div class="progress-bar" style="width: 100%; background: #fff;"></div>
                                </div>
                                <span class="progress-description" style="font-size: 11px;">
                                    Semua Peserta TA Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box stat-card bg-yellow" onclick="filterByStatus('SIAP')" style="cursor: pointer;" title="Klik untuk filter data yang Siap Divalidasi">
                            <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Siap Divalidasi</span>
                                <span class="info-box-number" style="font-size: 24px;">{{ $siap }}</span>
                                <div class="progress" style="height: 2px; margin: 5px 0; background: rgba(255,255,255,0.3);">
                                    <div class="progress-bar" style="width: {{ $total > 0 ? ($siap / $total) * 100 : 0 }}%; background: #fff;"></div>
                                </div>
                                <span class="progress-description" style="font-size: 11px;">
                                    Nilai Ada, Menunggu BAAK
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box stat-card bg-green" onclick="filterByStatus('SUDAH')" style="cursor: pointer;" title="Klik untuk filter data yang Sudah Divalidasi">
                            <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sudah Divalidasi</span>
                                <span class="info-box-number" style="font-size: 24px;">{{ $sudah }}</span>
                                <div class="progress" style="height: 2px; margin: 5px 0; background: rgba(255,255,255,0.3);">
                                    <div class="progress-bar" style="width: {{ $total > 0 ? ($sudah / $total) * 100 : 0 }}%; background: #fff;"></div>
                                </div>
                                <span class="progress-description" style="font-size: 11px;">
                                    Tervalidasi BAAK
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box stat-card bg-red" onclick="filterByStatus('BELUM_LENGKAP')" style="cursor: pointer;" title="Klik untuk filter data yang Belum Memiliki Nilai">
                            <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Belum Lengkap</span>
                                <span class="info-box-number" style="font-size: 24px;">{{ $belum_lengkap }}</span>
                                <div class="progress" style="height: 2px; margin: 5px 0; background: rgba(255,255,255,0.3);">
                                    <div class="progress-bar" style="width: {{ $total > 0 ? ($belum_lengkap / $total) * 100 : 0 }}%; background: #fff;"></div>
                                </div>
                                <span class="progress-description" style="font-size: 11px;">
                                    Nilai Akhir Belum Ada
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Toolbar & Aksi Massal -->
                <div class="box box-default box-solid" style="border: 1px solid #d2d6de; margin-bottom: 15px; border-radius: 4px;">
                    <div class="box-body" style="padding: 12px 15px;">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 12px; margin-bottom: 3px;"><i class="fa fa-calendar"></i> Angkatan:</label>
                                    <select id="filter-angkatan" class="form-control input-sm">
                                        <option value="">-- Semua Angkatan --</option>
                                        @foreach ($angkatan as $akt)
                                            <option value="{{ $akt->angkatan }}">{{ $akt->angkatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 12px; margin-bottom: 3px;"><i class="fa fa-graduation-cap"></i> Program Studi:</label>
                                    <select id="filter-prodi" class="form-control input-sm">
                                        <option value="">-- Semua Program Studi --</option>
                                        @foreach ($prodi as $prd)
                                            <option value="{{ $prd->prodi }}">{{ $prd->prodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-size: 12px; margin-bottom: 3px;"><i class="fa fa-filter"></i> Status Validasi:</label>
                                    <select id="filter-status" class="form-control input-sm">
                                        <option value="ALL">-- Semua Status --</option>
                                        <option value="SIAP">Siap Validasi (Nilai Ada)</option>
                                        <option value="SUDAH">Sudah Validasi (BAAK)</option>
                                        <option value="BELUM_LENGKAP">Belum Lengkap (Nilai Kosong)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12 text-right" style="padding-top: 20px;">
                                <button type="button" id="btn-reset-filter" class="btn btn-default btn-sm btn-flat" title="Reset Semua Filter">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                                <button type="button" id="btn-bulk-validate" class="btn btn-primary btn-sm btn-flat" disabled title="Centang mahasiswa yang siap divalidasi">
                                    <i class="fa fa-check-square-o"></i> Validasi Terpilih (<span id="selected-count">0</span>)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form & Tabel Data Validasi Tugas Akhir -->
                <form id="form-bulk-validation" action="{{ url('validasi_akhir_prausta_bulk') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="table-responsive">
                        <table id="tbl-validasi" class="table table-bordered table-striped table-hover" style="width: 100%; font-size: 13px;">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th style="width: 30px; text-align: center; vertical-align: middle;">
                                        <input type="checkbox" id="check-all" title="Pilih Semua yang Siap Validasi">
                                    </th>
                                    <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                                    <th style="vertical-align: middle;">Mahasiswa / NIM</th>
                                    <th style="vertical-align: middle;">Program Studi</th>
                                    <th style="text-align: center; vertical-align: middle;">Kelas</th>
                                    <th style="text-align: center; vertical-align: middle;">Angkatan</th>
                                    <th style="text-align: center; vertical-align: middle;">Bimbingan</th>
                                    <th style="text-align: center; vertical-align: middle;">Nilai Akhir</th>
                                    <th style="text-align: center; vertical-align: middle;">Laporan Revisi</th>
                                    <th style="text-align: center; vertical-align: middle;">Plagiarisme</th>
                                    <th style="text-align: center; vertical-align: middle;">Status BAAK</th>
                                    <th style="text-align: center; width: 90px; vertical-align: middle;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    @php
                                        $isReady = ($item->validasi_baak == 'BELUM' && !empty($item->nilai_huruf));
                                        $statusType = $item->validasi_baak == 'SUDAH' ? 'SUDAH' : ($isReady ? 'SIAP' : 'BELUM_LENGKAP');
                                    @endphp
                                    <tr data-status="{{ $statusType }}" data-angkatan="{{ $item->angkatan }}" data-prodi="{{ $item->prodi }}">
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($isReady)
                                                <input type="checkbox" name="id_settingrelasi_prausta[]" value="{{ $item->id_settingrelasi_prausta }}" class="check-item">
                                            @else
                                                <input type="checkbox" disabled title="{{ $item->validasi_baak == 'SUDAH' ? 'Sudah tervalidasi' : 'Belum memiliki nilai akhir' }}">
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                                        <td style="vertical-align: middle;">
                                            <strong style="color: #222; font-size: 13px;">{{ $item->nama }}</strong><br>
                                            <small class="text-muted"><i class="fa fa-id-card-o"></i> {{ $item->nim }}</small>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <span class="text-primary">{{ $item->prodi }}</span>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <span class="label label-default">{{ $item->kelas }}</span>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <span class="badge bg-gray">{{ $item->angkatan }}</span>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($item->jml_bim > 0)
                                                <span class="badge bg-purple" title="{{ $item->jml_bim }} kali bimbingan">{{ $item->jml_bim }}x</span>
                                            @else
                                                <span class="badge bg-gray" title="Belum ada catatan bimbingan">0x</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if (!empty($item->nilai_huruf))
                                                <span class="label label-primary" style="font-size: 13px; font-weight: bold; padding: 4px 8px;">
                                                    {{ $item->nilai_huruf }}
                                                </span>
                                            @else
                                                <span class="label label-danger" title="Nilai belum diinput oleh dosen pembimbing/penguji">
                                                    <i class="fa fa-times"></i> Belum Ada
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if (!empty($item->file_laporan_revisi))
                                                <a href="{{ url('/File Laporan Revisi/' . $item->idstudent . '/' . $item->file_laporan_revisi) }}"
                                                   target="_blank" class="btn btn-default btn-xs btn-flat" style="border-color: #ccc;" title="Buka File Laporan">
                                                    <i class="fa fa-file-pdf-o text-red"></i> Laporan
                                                </a>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">
                                                    <i class="fa fa-minus-circle"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if (!empty($item->file_plagiarisme))
                                                <a href="{{ url('/File Plagiarisme/' . $item->idstudent . '/' . $item->file_plagiarisme) }}"
                                                   target="_blank" class="btn btn-default btn-xs btn-flat" style="border-color: #ccc;" title="Buka File Plagiarisme">
                                                    <i class="fa fa-file-text-o text-blue"></i> Plagiarisme
                                                </a>
                                            @else
                                                <span class="text-muted" style="font-size: 11px;">
                                                    <i class="fa fa-minus-circle"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($item->validasi_baak == 'SUDAH')
                                                <span class="label label-success" style="font-size: 11px; padding: 4px 7px;">
                                                    <i class="fa fa-check"></i> Sudah Validasi
                                                </span>
                                            @elseif ($isReady)
                                                <span class="label label-warning" style="font-size: 11px; padding: 4px 7px;">
                                                    <i class="fa fa-clock-o"></i> Siap Divalidasi
                                                </span>
                                            @else
                                                <span class="label label-default" style="font-size: 11px; padding: 4px 7px;">
                                                    <i class="fa fa-hourglass-start"></i> Menunggu Nilai
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($item->validasi_baak == 'BELUM')
                                                @if (!empty($item->nilai_huruf))
                                                    <button type="button" class="btn btn-info btn-xs btn-flat btn-confirm-action"
                                                            data-action="{{ url('validasi_akhir_prausta/' . $item->id_settingrelasi_prausta) }}"
                                                            data-title="Konfirmasi Validasi Tugas Akhir"
                                                            data-message="Apakah Anda yakin ingin memvalidasi akhir Tugas Akhir mahasiswa: <br><b>{{ $item->nama }} ({{ $item->nim }})</b>?"
                                                            data-btn-class="btn-info"
                                                            data-btn-text="Ya, Validasi Sekarang">
                                                        <i class="fa fa-check"></i> Validasi
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-default btn-xs btn-flat" disabled title="Nilai akhir belum ada, tombol validasi dinonaktifkan">
                                                        <i class="fa fa-ban"></i> Validasi
                                                    </button>
                                                @endif
                                            @elseif ($item->validasi_baak == 'SUDAH')
                                                <button type="button" class="btn btn-danger btn-xs btn-flat btn-confirm-action"
                                                        data-action="{{ url('batal_validasi_akhir_prausta/' . $item->id_settingrelasi_prausta) }}"
                                                        data-title="Konfirmasi Batal Validasi Tugas Akhir"
                                                        data-message="Apakah Anda yakin ingin membatalkan validasi akhir mahasiswa: <br><b>{{ $item->nama }} ({{ $item->nim }})</b>?"
                                                        data-btn-class="btn-danger"
                                                        data-btn-text="Ya, Batalkan">
                                                    <i class="fa fa-undo"></i> Batal
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Aksi Tunggal (Validasi / Batal) -->
    <div class="modal fade" id="modal-confirm-action" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-action-title">Konfirmasi Aksi</h4>
                </div>
                <div class="modal-body">
                    <p id="modal-action-message" style="margin-bottom: 0;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">Tutup</button>
                    <a href="#" id="modal-action-btn" class="btn btn-sm btn-flat">Lanjutkan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Validasi Massal -->
    <div class="modal fade" id="modal-confirm-bulk" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="color: #fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Validasi Massal</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin memvalidasi akhir <b id="bulk-modal-count">0</b> mahasiswa yang telah dipilih secara bersamaan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-submit-bulk" class="btn btn-primary btn-sm btn-flat">
                        <i class="fa fa-check"></i> Ya, Validasi Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#tbl-validasi').DataTable({
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                "order": [[5, "desc"], [1, "asc"]],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 11] }
                ],
                "language": {
                    "search": "Cari Cepat:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data mahasiswa yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ mahasiswa",
                    "infoEmpty": "Menampilkan 0 data",
                    "infoFiltered": "(difilter dari total _MAX_ mahasiswa)",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    if (settings.nTable.id !== 'tbl-validasi') {
                        return true;
                    }

                    var rowNode = table.row(dataIndex).node();
                    var rowStatus = $(rowNode).attr('data-status');
                    var rowAngkatan = $(rowNode).attr('data-angkatan');
                    var rowProdi = $(rowNode).attr('data-prodi');

                    var selectedAngkatan = $('#filter-angkatan').val();
                    var selectedProdi = $('#filter-prodi').val();
                    var selectedStatus = $('#filter-status').val();

                    if (selectedAngkatan && rowAngkatan !== selectedAngkatan) {
                        return false;
                    }

                    if (selectedProdi && rowProdi !== selectedProdi) {
                        return false;
                    }

                    if (selectedStatus && selectedStatus !== 'ALL' && rowStatus !== selectedStatus) {
                        return false;
                    }

                    return true;
                }
            );

            $('#filter-angkatan, #filter-prodi, #filter-status').on('change', function() {
                table.draw();
                updateBulkValidationState();
            });

            window.filterByStatus = function(status) {
                $('#filter-status').val(status).trigger('change');
            };

            $('#btn-reset-filter').on('click', function() {
                $('#filter-angkatan').val('');
                $('#filter-prodi').val('');
                $('#filter-status').val('ALL');
                table.search('').draw();
                updateBulkValidationState();
            });

            $('#check-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('#tbl-validasi tbody tr:visible').find('.check-item:not(:disabled)').prop('checked', isChecked);
                updateBulkValidationState();
            });

            $(document).on('change', '.check-item', function() {
                updateBulkValidationState();
            });

            function updateBulkValidationState() {
                var checkedCount = $('.check-item:checked').length;
                $('#selected-count').text(checkedCount);

                if (checkedCount > 0) {
                    $('#btn-bulk-validate').prop('disabled', false).removeClass('btn-default').addClass('btn-primary');
                } else {
                    $('#btn-bulk-validate').prop('disabled', true).removeClass('btn-primary').addClass('btn-default');
                }

                var visibleCheckboxes = $('#tbl-validasi tbody tr:visible').find('.check-item:not(:disabled)');
                if (visibleCheckboxes.length > 0 && visibleCheckboxes.length === visibleCheckboxes.filter(':checked').length) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            }

            $(document).on('click', '.btn-confirm-action', function(e) {
                e.preventDefault();
                var actionUrl = $(this).data('action');
                var title = $(this).data('title');
                var message = $(this).data('message');
                var btnClass = $(this).data('btn-class');
                var btnText = $(this).data('btn-text');

                $('#modal-action-title').text(title);
                $('#modal-action-message').html(message);
                $('#modal-action-btn')
                    .attr('href', actionUrl)
                    .attr('class', 'btn btn-sm btn-flat ' + btnClass)
                    .text(btnText);

                $('#modal-confirm-action').modal('show');
            });

            $('#btn-bulk-validate').on('click', function() {
                var checkedCount = $('.check-item:checked').length;
                if (checkedCount === 0) return;

                $('#bulk-modal-count').text(checkedCount);
                $('#modal-confirm-bulk').modal('show');
            });

            $('#btn-submit-bulk').on('click', function() {
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
                $('#form-bulk-validation').submit();
            });
        });
    </script>
@endsection
