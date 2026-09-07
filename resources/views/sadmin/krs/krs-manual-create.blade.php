@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil"></i> Input & Kelola KRS Manual
            <small>{{ $tahunActive->periode_tahun ?? '-' }} ({{ $tipeActive->periode_tipe ?? '-' }})</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="{{ url('krs-manual') }}">KRS Manual</a></li>
            <li class="active">Input KRS</li>
        </ol>
    </section>

    <section class="content">
        {{-- Notification Alert Container --}}
        <div id="alert-container"></div>

        {{-- Profil Mahasiswa Card --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Data Mahasiswa</h3>
                <div class="box-tools pull-right">
                    <a href="{{ url('krs-manual') }}" class="btn btn-default btn-sm btn-flat">
                        <i class="fa fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                    <a href="{{ url('krs-manual/detail/' . $dataMhs->idstudent) }}" class="btn btn-info btn-sm btn-flat">
                        <i class="fa fa-eye"></i> Lihat Detail KRS
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <table class="table table-condensed table-striped" style="margin-bottom: 0;">
                            <tr>
                                <th style="width: 25%;">Nama Mahasiswa</th>
                                <td style="width: 3%;">:</td>
                                <td><strong>{{ $dataMhs->nama }}</strong></td>
                                <th style="width: 20%;">Program Studi</th>
                                <td style="width: 3%;">:</td>
                                <td>
                                    <strong>{{ $dataMhs->prodi }}</strong>
                                    @if (!empty($dataMhs->konsentrasi) && $dataMhs->konsentrasi != '-')
                                        <br><small class="text-muted"><i class="fa fa-tag"></i> {{ $dataMhs->konsentrasi }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>NIM</th>
                                <td>:</td>
                                <td><span class="label label-default" style="font-size: 12px;">{{ $dataMhs->nim }}</span></td>
                                <th>Kelas / Angkatan</th>
                                <td>:</td>
                                <td>
                                    <span class="label label-info">{{ optional($dataMhs->kelas)->kelas ?? '-' }}</span>
                                    <span class="label label-primary">{{ optional($dataMhs->angkatan)->angkatan ?? '-' }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4 col-sm-12 text-center" style="border-left: 1px solid #eee;">
                        <div style="padding: 10px 0;">
                            <span class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px; font-weight: bold;">
                                Beban SKS Semester Ini
                            </span>
                            <div style="margin-top: 5px;">
                                <span id="badge-total-sks" class="badge {{ $totalSksDiambil > 0 ? 'bg-green' : 'bg-red' }}" style="font-size: 18px; padding: 6px 14px; font-weight: bold;">
                                    <span id="val-total-sks">{{ $totalSksDiambil }}</span> SKS
                                </span>
                            </div>
                            <small class="text-muted" style="display: block; margin-top: 4px;">
                                Periode: {{ $tahunActive->periode_tahun ?? '-' }} ({{ $tipeActive->periode_tipe ?? '-' }})
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Two-Panel Layout --}}
        <div class="row">
            {{-- Panel Kiri: Mata Kuliah Diambil --}}
            <div class="col-md-5 col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-check-square-o text-green"></i> Mata Kuliah Diambil
                        </h3>
                        <div class="box-tools pull-right">
                            <span id="badge-count-diambil" class="label label-success" style="font-size: 11px;">
                                {{ $dataKrsMhs->count() }} Mata Kuliah
                            </span>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered table-striped table-hover" id="matakuliah-diambil" style="margin-bottom: 0;">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th>Kode & Matakuliah</th>
                                    <th style="width: 60px; text-align: center;">SKS</th>
                                    <th>Dosen</th>
                                    <th style="width: 50px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $takenKurperiodeIds = [];
                                @endphp
                                @forelse ($dataKrsMhs as $krs)
                                    @php
                                        $kurperiode = $krs->kurperiode;
                                        $makul = optional($kurperiode)->makul;
                                        $sksItem = ($makul->akt_sks_teori ?? 0) + ($makul->akt_sks_praktek ?? 0);
                                        $takenKurperiodeIds[] = $krs->id_kurperiode;
                                    @endphp
                                    <tr id="row-taken-{{ $krs->id_studentrecord }}" data-kurperiode="{{ $krs->id_kurperiode }}">
                                        <td>
                                            <span class="label label-default" style="font-size: 10px;">{{ $makul->kode ?? '' }}</span><br>
                                            <strong>{{ $makul->makul ?? '-' }}</strong>
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            <span class="badge bg-light-blue">{{ $sksItem }}</span>
                                        </td>
                                        <td style="vertical-align: middle; font-size: 12px;">
                                            {{ optional($kurperiode->dosen)->nama ?? '-' }}
                                        </td>
                                        <td align="center" style="vertical-align: middle;">
                                            <button type="button" class="btn btn-danger btn-xs btn-flat btn-cancel"
                                                data-id="{{ $krs->id_studentrecord }}"
                                                data-kurperiode="{{ $krs->id_kurperiode }}"
                                                title="Batalkan Mata Kuliah">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-diambil">
                                        <td colspan="4" class="text-center text-muted" style="padding: 30px 15px;">
                                            <i class="fa fa-info-circle fa-2x" style="color: #bbb;"></i><br>
                                            <span style="font-size: 13px; margin-top: 5px; display: inline-block;">
                                                Belum ada mata kuliah yang diambil.<br>Pilih dari katalog di sebelah kanan.
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer" style="background-color: #f9f9f9; padding: 10px 15px;">
                        <span class="text-muted">
                            <i class="fa fa-info-circle"></i> Perubahan penambahan dan pembatalan otomatis tersimpan realtime.
                        </span>
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Katalog Mata Kuliah Ditawarkan --}}
            <div class="col-md-7 col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-book text-blue"></i> Katalog Mata Kuliah Ditawarkan
                        </h3>
                        <div class="box-tools pull-right">
                            <span class="label label-primary" id="katalog-count">{{ $dataKrs->count() }} Tersedia</span>
                        </div>
                    </div>
                    <div class="box-body" style="padding-bottom: 5px;">
                        {{-- Filter Katalog Bar --}}
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 5px;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <select id="filter-katalog-kelas" class="form-control" title="Pilih Kelas Ditawarkan">
                                        <option value="all" {{ $selectedKelas == 'all' ? 'selected' : '' }}>-- Semua Kelas --</option>
                                        @foreach ($kelasList as $kls)
                                            <option value="{{ $kls->idkelas }}" {{ $selectedKelas == $kls->idkelas ? 'selected' : '' }}>
                                                Kelas: {{ $kls->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 5px;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <select id="filter-katalog-smt" class="form-control" title="Filter Semester">
                                        <option value="">-- Semua Smt --</option>
                                        @foreach ($semesterList as $smt)
                                            <option value="{{ $smt->idsemester }}" {{ $selectedSemester == $smt->idsemester ? 'selected' : '' }}>
                                                {{ $smt->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 5px;">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="katalog-search" class="form-control" placeholder="Cari kode/nama...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Katalog --}}
                        <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                            <table class="table table-bordered table-striped table-hover" id="katalog-table" style="margin-bottom: 0;">
                                <thead>
                                    <tr class="bg-gray-light">
                                        <th>Kode & Nama Matakuliah</th>
                                        <th style="width: 70px; text-align: center;">Kelas</th>
                                        <th style="width: 55px; text-align: center;">SKS</th>
                                        <th style="width: 50px; text-align: center;">Smt</th>
                                        <th>Dosen</th>
                                        <th style="width: 90px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataKrs as $item)
                                        @php
                                            $sksItem = (optional($item->makul)->akt_sks_teori ?? 0) + (optional($item->makul)->akt_sks_praktek ?? 0);
                                            $isTaken = in_array($item->id_kurperiode, $takenKurperiodeIds);
                                        @endphp
                                        <tr class="katalog-row"
                                            id="katalog-row-{{ $item->id_kurperiode }}"
                                            data-kurperiode="{{ $item->id_kurperiode }}"
                                            data-kelas="{{ optional($item->kelas)->idkelas }}"
                                            data-semester="{{ optional($item->semester)->idsemester }}"
                                            data-search="{{ strtolower((optional($item->makul)->kode ?? '') . ' ' . (optional($item->makul)->makul ?? '') . ' ' . (optional($item->dosen)->nama ?? '')) }}">
                                            <td>
                                                <span class="label label-default" style="font-size: 10px;">{{ optional($item->makul)->kode }}</span><br>
                                                <strong>{{ optional($item->makul)->makul }}</strong>
                                            </td>
                                            <td align="center" style="vertical-align: middle;">
                                                <span class="label label-info" style="font-size: 10px;">{{ optional($item->kelas)->kelas }}</span>
                                            </td>
                                            <td align="center" style="vertical-align: middle;">
                                                <span class="badge bg-light-blue">{{ $sksItem }}</span>
                                            </td>
                                            <td align="center" style="vertical-align: middle;">
                                                {{ optional($item->semester)->semester }}
                                            </td>
                                            <td style="vertical-align: middle; font-size: 12px;">
                                                {{ optional($item->dosen)->nama ?? '-' }}
                                            </td>
                                            <td align="center" style="vertical-align: middle;">
                                                @if ($isTaken)
                                                    <button type="button" class="btn btn-default btn-xs btn-flat btn-save-krs"
                                                        id="btn-add-{{ $item->id_kurperiode }}" disabled
                                                        data-id-student="{{ $dataMhs->idstudent }}"
                                                        data-id-kurperiode="{{ $item->id_kurperiode }}"
                                                        data-id-kurtrans="{{ optional($item->kurtrans)->idkurtrans }}">
                                                        <i class="fa fa-check text-green"></i> Diambil
                                                    </button>
                                                @elseif ($item->kurtrans)
                                                    <button type="button" class="btn btn-success btn-xs btn-flat btn-save-krs"
                                                        id="btn-add-{{ $item->id_kurperiode }}"
                                                        data-id-student="{{ $dataMhs->idstudent }}"
                                                        data-id-kurperiode="{{ $item->id_kurperiode }}"
                                                        data-id-kurtrans="{{ optional($item->kurtrans)->idkurtrans }}">
                                                        <i class="fa fa-plus"></i> Tambah
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-default btn-xs btn-flat" disabled
                                                        title="Mata kuliah tidak tersedia dalam kurikulum">
                                                        <i class="fa fa-ban"></i> N/A
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="empty-katalog">
                                            <td colspan="6" class="text-center text-muted" style="padding: 30px 15px;">
                                                <i class="fa fa-info-circle fa-2x" style="color: #bbb;"></i><br>
                                                <span style="font-size: 13px; margin-top: 5px; display: inline-block;">
                                                    Tidak ada mata kuliah yang ditawarkan untuk filter ini.
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Helper function to show alert notification
            function showAlert(type, message) {
                var icon = type === 'success' ? 'fa-check' : 'fa-warning';
                var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible" style="border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<h4><i class="icon fa ' + icon + '"></i> ' + (type === 'success' ? 'Berhasil!' : 'Pemberitahuan') + '</h4>' +
                    message +
                    '</div>';

                $('#alert-container').html(alertHtml);
                $('html, body').animate({ scrollTop: 0 }, 200);

                // Auto hide after 4 seconds
                setTimeout(function () {
                    $('#alert-container .alert').slideUp(300, function() {
                        $(this).remove();
                    });
                }, 4000);
            }

            // Update Total SKS Badge in Header
            function updateTotalSks(newTotal, countMakul) {
                $('#val-total-sks').text(newTotal);
                if (newTotal > 0) {
                    $('#badge-total-sks').removeClass('bg-red').addClass('bg-green');
                } else {
                    $('#badge-total-sks').removeClass('bg-green').addClass('bg-red');
                }

                if (countMakul !== undefined) {
                    $('#badge-count-diambil').text(countMakul + ' Mata Kuliah');
                }
            }

            // ----------------------------------------------------
            // EVENT: Tambah Mata Kuliah ke KRS (Realtime AJAX)
            // ----------------------------------------------------
            $(document).on('click', '.btn-save-krs', function (e) {
                e.preventDefault();
                var button = $(this);
                var idStudent = button.data('id-student');
                var idKurperiode = button.data('id-kurperiode');
                var idKurtrans = button.data('id-kurtrans');

                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: '{{ url("save-krs-manual") }}',
                    method: 'POST',
                    data: {
                        id_student: idStudent,
                        id_kurperiode: idKurperiode,
                        id_kurtrans: idKurtrans
                    },
                    success: function (response) {
                        if (response.success) {
                            // 1. Update tombol di katalog kanan
                            button.removeClass('btn-success').addClass('btn-default');
                            button.html('<i class="fa fa-check text-green"></i> Diambil');
                            button.prop('disabled', true);

                            // 2. Tambah baris baru ke tabel kiri (Mata Kuliah Diambil)
                            $('#empty-diambil').remove();

                            var newRowHtml = '<tr id="row-taken-' + response.id_studentrecord + '" data-kurperiode="' + response.id_kurperiode + '" style="background-color: #dff0d8;">' +
                                '<td>' +
                                '<span class="label label-default" style="font-size: 10px;">' + response.kode_makul + '</span><br>' +
                                '<strong>' + response.nama_makul + '</strong>' +
                                '</td>' +
                                '<td align="center" style="vertical-align: middle;">' +
                                '<span class="badge bg-light-blue">' + response.sks + '</span>' +
                                '</td>' +
                                '<td style="vertical-align: middle; font-size: 12px;">' +
                                (response.nama_dosen || '-') +
                                '</td>' +
                                '<td align="center" style="vertical-align: middle;">' +
                                '<button type="button" class="btn btn-danger btn-xs btn-flat btn-cancel" ' +
                                'data-id="' + response.id_studentrecord + '" ' +
                                'data-kurperiode="' + response.id_kurperiode + '" ' +
                                'title="Batalkan Mata Kuliah">' +
                                '<i class="fa fa-trash"></i>' +
                                '</button>' +
                                '</td>' +
                                '</tr>';

                            $('#matakuliah-diambil tbody').append(newRowHtml);

                            // Smooth animation highlight
                            setTimeout(function () {
                                $('#row-taken-' + response.id_studentrecord).css('background-color', '');
                            }, 1500);

                            // 3. Update counter total SKS
                            updateTotalSks(response.total_sks_now, response.total_makul_now);

                            showAlert('success', response.message);
                        } else {
                            button.prop('disabled', false).html('<i class="fa fa-plus"></i> Tambah');
                            showAlert('warning', response.message);
                        }
                    },
                    error: function (xhr) {
                        button.prop('disabled', false).html('<i class="fa fa-plus"></i> Tambah');
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menambahkan mata kuliah.';
                        showAlert('danger', msg);
                    }
                });
            });

            // ----------------------------------------------------
            // EVENT: Batalkan Mata Kuliah dari KRS (Realtime AJAX)
            // ----------------------------------------------------
            $(document).on('click', '.btn-cancel', function (e) {
                e.preventDefault();
                var button = $(this);
                var studentRecordId = button.data('id');
                var idKurperiode = button.data('kurperiode');
                var row = $('#row-taken-' + studentRecordId);

                if (!confirm('Apakah Anda yakin ingin membatalkan mata kuliah ini dari KRS?')) {
                    return;
                }

                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: '{{ url("krs-manual-cancel") }}/' + studentRecordId,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            // 1. Hapus baris dari tabel kiri dengan animasi fadeOut
                            row.fadeOut(300, function () {
                                $(this).remove();
                                // Jika tabel kosong, tampilkan empty state
                                if ($('#matakuliah-diambil tbody tr').length === 0) {
                                    $('#matakuliah-diambil tbody').html(
                                        '<tr id="empty-diambil">' +
                                        '<td colspan="4" class="text-center text-muted" style="padding: 30px 15px;">' +
                                        '<i class="fa fa-info-circle fa-2x" style="color: #bbb;"></i><br>' +
                                        '<span style="font-size: 13px; margin-top: 5px; display: inline-block;">' +
                                        'Belum ada mata kuliah yang diambil.<br>Pilih dari katalog di sebelah kanan.' +
                                        '</span>' +
                                        '</td>' +
                                        '</tr>'
                                    );
                                }
                            });

                            // 2. Aktifkan kembali tombol di katalog kanan
                            var katalogBtn = $('#btn-add-' + response.id_kurperiode);
                            if (katalogBtn.length) {
                                katalogBtn.removeClass('btn-default').addClass('btn-success');
                                katalogBtn.html('<i class="fa fa-plus"></i> Tambah');
                                katalogBtn.prop('disabled', false);
                            }

                            // 3. Update counter total SKS
                            updateTotalSks(response.total_sks_now, response.total_makul_now);

                            showAlert('success', response.message);
                        } else {
                            button.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                            showAlert('danger', response.message || 'Gagal membatalkan KRS.');
                        }
                    },
                    error: function (xhr) {
                        button.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                        showAlert('danger', 'Terjadi kesalahan sistem saat membatalkan KRS.');
                    }
                });
            });

            // ----------------------------------------------------
            // FILTER KATALOG: Live Search & Semester Filter
            // ----------------------------------------------------
            function filterKatalog() {
                var searchKeyword = $('#katalog-search').val().toLowerCase().trim();
                var selectedSmt = $('#filter-katalog-smt').val();
                var visibleCount = 0;

                $('.katalog-row').each(function () {
                    var row = $(this);
                    var rowSearchText = row.data('search') || '';
                    var rowSmt = row.data('semester') || '';

                    var matchSearch = searchKeyword === '' || rowSearchText.indexOf(searchKeyword) > -1;
                    var matchSmt = selectedSmt === '' || String(rowSmt) === String(selectedSmt);

                    if (matchSearch && matchSmt) {
                        row.show();
                        visibleCount++;
                    } else {
                        row.hide();
                    }
                });

                $('#katalog-count').text(visibleCount + ' Ditemukan');
            }

            $('#katalog-search').on('keyup', filterKatalog);
            $('#filter-katalog-smt').on('change', filterKatalog);

            // Filter Kelas (pindah kelas ditawarkan dengan reload parameter)
            $('#filter-katalog-kelas').on('change', function () {
                var kelasId = $(this).val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('filter_kelas', kelasId);
                var smtVal = $('#filter-katalog-smt').val();
                if (smtVal) {
                    currentUrl.searchParams.set('filter_semester', smtVal);
                }
                window.location.href = currentUrl.toString();
            });
        });
    </script>
@endsection