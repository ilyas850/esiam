@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <style>
        .info-box {
            min-height: 80px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            border-radius: 4px;
            margin-bottom: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .info-box-icon {
            height: 80px;
            line-height: 80px;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .info-box-content {
            padding: 10px;
            margin-left: 80px;
        }

        .info-box-number {
            font-size: 22px;
            font-weight: 700;
        }

        .table-hover>tbody>tr:hover {
            background-color: #f8fafc !important;
        }

        .badge-tipe {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
            letter-spacing: 0.3px;
        }

        .bg-purple {
            background-color: #605ca8 !important;
            color: #fff !important;
        }

        .bg-navy {
            background-color: #001f3f !important;
            color: #fff !important;
        }

        .bg-teal {
            background-color: #39cccc !important;
            color: #fff !important;
        }

        .badge-durasi {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
        }

        .modal-header-custom {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            padding: 15px;
            color: #fff;
        }

        .modal-header-success {
            background-color: #00a65a;
        }

        .modal-header-info {
            background-color: #00c0ef;
        }

        .btn-action-group .btn {
            margin: 0 2px;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
        }

        .dot-green { background-color: #00a65a; }
        .dot-yellow { background-color: #f39c12; }
        .dot-gray { background-color: #9e9e9e; }
    </style>

    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
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
        $totalData = $data->count();
        $totalPkl = $data->where('tipe_prausta', 'PKL')->count();
        $totalMagang = $data->whereIn('tipe_prausta', ['Magang', 'Magang 2'])->count();

        $totalBerjalan = $data->filter(function($item) use ($today) {
            return $today >= $item->set_waktu_awal && $today <= $item->set_waktu_akhir;
        })->count();
    @endphp

    {{-- Content Header --}}
    <section class="content-header">
        <h1>
            Waktu Pelaksanaan PKL & Magang
            <small>Manajemen jadwal dan batas waktu prausta prakerin / PKL dan magang</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="#">PraUSTA</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Waktu PKL / Magang</li>
        </ol>
    </section>

    {{-- Main Content --}}
    <section class="content">

        {{-- Ringkasan Statistik --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Jadwal</span>
                        <span class="info-box-number">{{ number_format($totalData) }} <small style="font-size: 13px; font-weight: normal; color: #777;">Periode</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-briefcase"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jadwal PKL</span>
                        <span class="info-box-number">{{ number_format($totalPkl) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-purple"><i class="fa fa-building-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jadwal Magang</span>
                        <span class="info-box-number">{{ number_format($totalMagang) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sedang Berlangsung</span>
                        <span class="info-box-number">{{ number_format($totalBerjalan) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt text-primary"></i> Daftar Waktu Pelaksanaan PKL & Magang</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addwaktu">
                        <i class="fa fa-plus-circle"></i> Tambah Waktu Pelaksanaan
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-gray-light">
                                <th style="width: 40px; text-align: center;">No</th>
                                <th style="width: 140px; text-align: center;">Periode Akademik</th>
                                <th>Program Studi</th>
                                <th style="width: 120px; text-align: center;">Tipe Kegiatan</th>
                                <th>Rentang Waktu Pelaksanaan</th>
                                <th style="width: 100px; text-align: center;">Durasi</th>
                                <th style="width: 80px; text-align: center;">Status</th>
                                <th style="width: 90px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                @php
                                    $t1 = strtotime($item->set_waktu_awal);
                                    $t2 = strtotime($item->set_waktu_akhir);
                                    $durasiHari = ($t1 && $t2 && $t2 >= $t1) ? round(($t2 - $t1) / 86400) + 1 : 0;
                                    
                                    // Status Pelaksanaan
                                    $isBerjalan = ($today >= $item->set_waktu_awal && $today <= $item->set_waktu_akhir);
                                    $isMendatang = ($today < $item->set_waktu_awal);
                                    $isSelesai = ($today > $item->set_waktu_akhir);
                                @endphp
                                <tr>
                                    <td align="center" style="vertical-align: middle;">{{ $no++ }}</td>
                                    <td align="center" style="vertical-align: middle;">
                                        <span class="badge bg-navy" style="font-size: 11px;">{{ $item->periode_tahun }}</span>
                                        <div style="margin-top: 4px;">
                                            <span class="label {{ strtoupper($item->periode_tipe) == 'GANJIL' ? 'label-primary' : 'label-warning' }}" style="font-size: 10px;">
                                                {{ $item->periode_tipe }}
                                            </span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <strong style="font-size: 13px; color: #2c3e50;">{{ $item->prodi }}</strong>
                                        @if (!empty($item->konsentrasi))
                                            <div style="color: #666; font-size: 12px;">
                                                <i class="fa fa-angle-right text-muted"></i> Konsentrasi: {{ $item->konsentrasi }}
                                            </div>
                                        @endif
                                    </td>
                                    <td align="center" style="vertical-align: middle;">
                                        @if ($item->tipe_prausta == 'PKL')
                                            <span class="badge bg-blue badge-tipe"><i class="fa fa-briefcase"></i> PKL</span>
                                        @elseif ($item->tipe_prausta == 'Magang')
                                            <span class="badge bg-purple badge-tipe"><i class="fa fa-building-o"></i> Magang 1</span>
                                        @elseif ($item->tipe_prausta == 'Magang 2')
                                            <span class="badge bg-teal badge-tipe"><i class="fa fa-building"></i> Magang 2</span>
                                        @else
                                            <span class="badge bg-gray badge-tipe">{{ $item->tipe_prausta }}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div>
                                            <i class="fa fa-calendar text-muted"></i>
                                            <span style="font-weight: 600; color: #333;">{{ $formatTglIndo($item->set_waktu_awal) }}</span>
                                            <span style="color: #999; margin: 0 4px;">s/d</span>
                                            <span style="font-weight: 600; color: #333;">{{ $formatTglIndo($item->set_waktu_akhir) }}</span>
                                        </div>
                                        <div style="margin-top: 4px;">
                                            @if ($isBerjalan)
                                                <span class="label label-success" style="font-size: 10px;">
                                                    <span class="status-dot dot-green"></span> Sedang Berlangsung
                                                </span>
                                            @elseif ($isMendatang)
                                                <span class="label label-warning" style="font-size: 10px;">
                                                    <span class="status-dot dot-yellow"></span> Belum Dimulai
                                                </span>
                                            @else
                                                <span class="label label-default" style="font-size: 10px;">
                                                    <span class="status-dot dot-gray"></span> Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td align="center" style="vertical-align: middle;">
                                        @if ($durasiHari > 0)
                                            <span class="badge-durasi"><i class="fa fa-hourglass-half text-muted"></i> {{ $durasiHari }} Hari</span>
                                        @else
                                            <span class="badge-durasi">-</span>
                                        @endif
                                    </td>
                                    <td align="center" style="vertical-align: middle;">
                                        @if ($item->status == 'ACTIVE')
                                            <span class="label label-success"><i class="fa fa-check"></i> AKTIF</span>
                                        @else
                                            <span class="label label-danger">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td align="center" style="vertical-align: middle;">
                                        <div class="btn-action-group">
                                            <button type="button" 
                                                class="btn btn-info btn-xs btn-edit-waktu" 
                                                title="Edit Jadwal"
                                                data-toggle="tooltip"
                                                data-id="{{ $item->id_masterwaktu_prausta }}"
                                                data-tahun="{{ $item->id_periodetahun }}"
                                                data-tipe="{{ $item->id_periodetipe }}"
                                                data-prausta="{{ $item->tipe_prausta }}"
                                                data-prodi="{{ $item->id_prodi }}"
                                                data-awal="{{ $item->set_waktu_awal }}"
                                                data-akhir="{{ $item->set_waktu_akhir }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="{{ url('hapus_waktu_prausta/' . $item->id_masterwaktu_prausta) }}"
                                                class="btn btn-danger btn-xs btn-delete-waktu" 
                                                title="Hapus Jadwal"
                                                data-toggle="tooltip"
                                                onclick="return confirm('Apakah Anda yakin akan menghapus jadwal waktu pelaksanaan ini?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

    {{-- Modal Tambah Waktu Pelaksanaan --}}
    <div class="modal fade" id="addwaktu" tabindex="-1" role="dialog" aria-labelledby="addWaktuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ url('post_waktu_prausta') }}" id="formAddWaktu">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header modal-header-custom modal-header-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="addWaktuLabel">
                            <i class="fa fa-plus-circle"></i> Tambah Waktu Pelaksanaan PKL/Magang
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar"></i> Periode Tahun <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_periodetahun" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @foreach ($periodetahun as $thn)
                                            <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-clock-o"></i> Semester <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_periodetipe" required>
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach ($periodetipe as $tp)
                                            <option value="{{ $tp->id_periodetipe }}">{{ $tp->periode_tipe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-tag"></i> Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select class="form-control" name="tipe_prausta" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="PKL">PKL</option>
                                        <option value="Magang">Magang 1</option>
                                        <option value="Magang 2">Magang 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><i class="fa fa-graduation-cap"></i> Program Studi <span class="text-danger">*</span></label>
                                    <select class="form-control" name="kodeprodi" required>
                                        <option value="">-- Pilih Program Studi --</option>
                                        @foreach ($prodi as $prd)
                                            <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted"><i class="fa fa-info-circle"></i> Jadwal otomatis berlaku untuk seluruh konsentrasi pada prodi yang dipilih.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar-plus-o"></i> Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="set_waktu_awal" id="add_waktu_awal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar-check-o"></i> Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="set_waktu_akhir" id="add_waktu_akhir" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #f9fafb;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Waktu Pelaksanaan (Single Reusable Dynamic Modal) --}}
    <div class="modal fade" id="modalUpdateWaktu" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formUpdateWaktu" method="post" action="">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header modal-header-custom modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalUpdateLabel">
                            <i class="fa fa-pencil-square-o"></i> Perbarui Waktu PKL / Magang
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar"></i> Periode Tahun <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_periodetahun" id="edit_id_periodetahun" required>
                                        @foreach ($periodetahun as $thn)
                                            <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-clock-o"></i> Semester <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_periodetipe" id="edit_id_periodetipe" required>
                                        @foreach ($periodetipe as $tp)
                                            <option value="{{ $tp->id_periodetipe }}">{{ $tp->periode_tipe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fa fa-tag"></i> Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select class="form-control" name="tipe_prausta" id="edit_tipe_prausta" required>
                                        <option value="PKL">PKL</option>
                                        <option value="Magang">Magang 1</option>
                                        <option value="Magang 2">Magang 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><i class="fa fa-graduation-cap"></i> Program Studi & Konsentrasi <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_prodi" id="edit_id_prodi" required>
                                        @foreach ($prodi_all as $prd)
                                            <option value="{{ $prd->id_prodi }}">
                                                {{ $prd->prodi }} @if(!empty($prd->konsentrasi)) - {{ $prd->konsentrasi }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar-plus-o"></i> Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="set_waktu_awal" id="edit_waktu_awal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-calendar-check-o"></i> Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="set_waktu_akhir" id="edit_waktu_akhir" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #f9fafb;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Perbarui Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Handler tombol edit modal dinamis
            $('.btn-edit-waktu').on('click', function () {
                var id = $(this).data('id');
                var tahun = $(this).data('tahun');
                var tipe = $(this).data('tipe');
                var prausta = $(this).data('prausta');
                var prodi = $(this).data('prodi');
                var awal = $(this).data('awal');
                var akhir = $(this).data('akhir');

                $('#formUpdateWaktu').attr('action', '{{ url("put_waktu_prausta") }}/' + id);
                $('#edit_id_periodetahun').val(tahun);
                $('#edit_id_periodetipe').val(tipe);
                $('#edit_tipe_prausta').val(prausta);
                $('#edit_id_prodi').val(prodi);
                $('#edit_waktu_awal').val(awal);
                $('#edit_waktu_akhir').val(akhir);

                // Set min tanggal akhir berdasarkan tanggal awal
                if (awal) {
                    $('#edit_waktu_akhir').attr('min', awal);
                }

                $('#modalUpdateWaktu').modal('show');
            });

            // Validasi tanggal pada form Tambah
            $('#add_waktu_awal').on('change', function () {
                var valAwal = $(this).val();
                if (valAwal) {
                    $('#add_waktu_akhir').attr('min', valAwal);
                    if ($('#add_waktu_akhir').val() && $('#add_waktu_akhir').val() < valAwal) {
                        $('#add_waktu_akhir').val(valAwal);
                    }
                }
            });

            // Validasi tanggal pada form Edit
            $('#edit_waktu_awal').on('change', function () {
                var valAwal = $(this).val();
                if (valAwal) {
                    $('#edit_waktu_akhir').attr('min', valAwal);
                    if ($('#edit_waktu_akhir').val() && $('#edit_waktu_akhir').val() < valAwal) {
                        $('#edit_waktu_akhir').val(valAwal);
                    }
                }
            });
        });
    </script>
@endsection

