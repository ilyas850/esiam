@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            display: block !important;
        }
        .select2-container .select2-selection--single {
            height: 36px !important;
            border: 1px solid #d2d6de !important;
            border-radius: 3px !important;
            background-color: #ffffff !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px !important;
            padding-left: 12px !important;
            color: #444 !important;
            font-size: 13px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px !important;
            right: 8px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3c8dbc !important;
        }
        .filter-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
            display: block;
            font-size: 13px;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-pencil-square-o"></i> Pengelolaan KRS Manual Mahasiswa
            <small>Tahun Akademik {{ $tahunActive->periode_tahun ?? '-' }} ({{ $tipeActive->periode_tipe ?? '-' }})</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li class="active">KRS Manual</li>
        </ol>
    </section>

    <section class="content">
        {{-- Statistik Info-Boxes --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Periode Aktif</span>
                        <span class="info-box-number" style="font-size: 16px;">
                            {{ $tahunActive->periode_tahun ?? 'Non-Aktif' }}
                        </span>
                        <span class="progress-description text-muted">
                            Semester {{ $tipeActive->periode_tipe ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Mahasiswa Aktif</span>
                        <span class="info-box-number">{{ number_format($totalMahasiswaAktif, 0, ',', '.') }}</span>
                        <span class="progress-description text-muted">Status Aktif / Pending</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Isi KRS</span>
                        <span class="info-box-number">{{ number_format($sudahKrsCount, 0, ',', '.') }}</span>
                        <span class="progress-description text-success" style="font-weight: bold;">
                            {{ $totalMahasiswaAktif > 0 ? round(($sudahKrsCount / $totalMahasiswaAktif) * 100, 1) : 0 }}% Mahasiswa
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Belum Isi KRS</span>
                        <span class="info-box-number">{{ number_format($belumKrsCount, 0, ',', '.') }}</span>
                        <span class="progress-description text-danger">
                            {{ $totalMahasiswaAktif > 0 ? round(($belumKrsCount / $totalMahasiswaAktif) * 100, 1) : 0 }}% Mahasiswa
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter text-primary"></i> Filter Data Mahasiswa</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body" style="background-color: #fafbfd;">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="filter-label" for="filter_prodi"><i class="fa fa-graduation-cap"></i> Program Studi</label>
                            <select id="filter_prodi" class="form-control select2" style="width: 100%;">
                                <option value="">-- Semua Program Studi --</option>
                                @foreach ($listProdi as $p)
                                    <option value="{{ $p->kodeprodi }}">{{ $p->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="filter-label" for="filter_kelas"><i class="fa fa-building-o"></i> Kelas</label>
                            <select id="filter_kelas" class="form-control select2" style="width: 100%;">
                                <option value="">-- Semua Kelas --</option>
                                @foreach ($listKelas as $k)
                                    <option value="{{ $k->idkelas }}">{{ $k->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="filter-label" for="filter_angkatan"><i class="fa fa-calendar-o"></i> Angkatan</label>
                            <select id="filter_angkatan" class="form-control select2" style="width: 100%;">
                                <option value="">-- Semua Angkatan --</option>
                                @foreach ($listAngkatan as $a)
                                    <option value="{{ $a->idangkatan }}">{{ $a->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="filter-label" for="filter_status_krs"><i class="fa fa-check-square-o"></i> Status KRS Periode Ini</label>
                            <select id="filter_status_krs" class="form-control select2" style="width: 100%;">
                                <option value="">-- Semua Status --</option>
                                <option value="sudah">Sudah Ambil KRS (SKS &gt; 0)</option>
                                <option value="belum">Belum Ambil KRS (0 SKS)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer text-right">
                <button type="button" id="btn-reset-filter" class="btn btn-default btn-sm btn-flat">
                    <i class="fa fa-refresh"></i> Reset Filter
                </button>
                <button type="button" id="btn-apply-filter" class="btn btn-primary btn-sm btn-flat">
                    <i class="fa fa-search"></i> Terapkan Filter
                </button>
            </div>
        </div>

        {{-- Tabel Data KRS Mahasiswa --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Daftar Mahasiswa & Status KRS</h3>
            </div>
            <div class="box-body table-responsive">
                <table id="krs-table" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr class="bg-gray-light">
                            <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">NIM & Nama Mahasiswa</th>
                            <th style="vertical-align: middle;">Program Studi</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="width: 80px; text-align: center; vertical-align: middle;">Angkatan</th>
                            <th style="vertical-align: middle;">Dosen Pembimbing</th>
                            <th style="width: 130px; text-align: center; vertical-align: middle;">Beban SKS</th>
                            <th style="width: 115px; text-align: center; vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data populated via DataTables serverSide --}}
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2 jika tersedia
            if ($.fn.select2) {
                $('.select2').select2();
            }

            var table = $('#krs-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('krs-manual') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_prodi = $('#filter_prodi').val();
                        d.filter_kelas = $('#filter_kelas').val();
                        d.filter_angkatan = $('#filter_angkatan').val();
                        d.filter_status_krs = $('#filter_status_krs').val();
                    }
                },
                columns: [
                    { data: 'no', name: 'no', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'nim_nama', name: 'student.nim' },
                    { data: 'prodi', name: 'prodi.prodi' },
                    { data: 'kelas', name: 'student.idstatus', className: 'text-center' },
                    { data: 'angkatan', name: 'student.idangkatan', className: 'text-center' },
                    { data: 'dosen_pembimbing', name: 'dosen_pembimbing', orderable: false, searchable: false },
                    { data: 'jml_sks', name: 'jml_sks', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[1, 'desc']],
                language: {
                    processing: '<div style="padding: 10px; background: rgba(255,255,255,0.9); border-radius: 4px;"><i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary"></i><br><span>Memuat data KRS mahasiswa...</span></div>',
                    search: "Cari (NIM / Nama):",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ mahasiswa",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 mahasiswa",
                    infoFiltered: "(disaring dari _MAX_ total mahasiswa)",
                    zeroRecords: "Tidak ditemukan data mahasiswa yang sesuai kriteria",
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>',
                        next: '<i class="fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>'
                    }
                }
            });

            // Terapkan filter saat klik tombol
            $('#btn-apply-filter').on('click', function () {
                table.draw();
            });

            // Auto filter saat dropdown berubah
            $('#filter_prodi, #filter_kelas, #filter_angkatan, #filter_status_krs').on('change', function () {
                table.draw();
            });

            // Reset filter
            $('#btn-reset-filter').on('click', function () {
                $('#filter_prodi').val('').trigger('change');
                $('#filter_kelas').val('').trigger('change');
                $('#filter_angkatan').val('').trigger('change');
                $('#filter_status_krs').val('').trigger('change');
                table.draw();
            });
        });
    </script>
@endsection