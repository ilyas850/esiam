@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <style>
        .info-box {
            min-height: 80px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            border-radius: 4px;
            margin-bottom: 15px;
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
        .table-hover > tbody > tr:hover {
            background-color: #f5f9fc !important;
        }
        .select2-container .select2-selection--single {
            height: 34px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }
        .btn-action-group .btn {
            margin-bottom: 5px;
        }
    </style>

    <section class="content-header">
        <h1>
            Rekapitulasi KRS Mahasiswa
            <small>Tahun Akademik {{ $namaperiodetahun }} - {{ $namaperiodetipe }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li class="active">Rekap KRS</li>
        </ol>
    </section>

    <section class="content">
        {{-- Ringkasan Statistik --}}
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Matakuliah</span>
                        <span class="info-box-number">{{ number_format($stats['total_makul']) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-cubes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Kelas Dibuka</span>
                        <span class="info-box-number">{{ number_format($stats['total_kelas']) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mahasiswa Ambil KRS</span>
                        <span class="info-box-number">{{ number_format($stats['total_mhs_krs']) }} <small style="font-size: 14px; font-weight: normal; color: #555;">Orang</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" title="{{ $stats['total_sks'] }} SKS aktif berjalan (dari total {{ $stats['total_sks_dibuka'] }} SKS kelas dibuka)">
                    <span class="info-box-icon bg-red"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">SKS Kelas Aktif</span>
                        <span class="info-box-number">{{ number_format($stats['total_sks']) }} <small style="font-size: 14px; font-weight: normal; color: #555;">SKS</small></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Filter & Export Terpadu --}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter & Ekspor Data KRS</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ url('data_krs') }}" method="GET" class="form-horizontal-filter">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-calendar"></i> Periode Tahun</label>
                                <select class="form-control select2" name="id_periodetahun" style="width: 100%;">
                                    @foreach ($tahun_list as $item_thn)
                                        <option value="{{ $item_thn->id_periodetahun }}" {{ $id_periodetahun == $item_thn->id_periodetahun ? 'selected' : '' }}>
                                            {{ $item_thn->periode_tahun }} {{ $item_thn->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-clock-o"></i> Tipe Semester</label>
                                <select class="form-control select2" name="id_periodetipe" style="width: 100%;">
                                    @foreach ($tipe_list as $item_tp)
                                        <option value="{{ $item_tp->id_periodetipe }}" {{ $id_periodetipe == $item_tp->id_periodetipe ? 'selected' : '' }}>
                                            {{ $item_tp->periode_tipe }} {{ $item_tp->status == 'ACTIVE' ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label><i class="fa fa-university"></i> Program Studi</label>
                                <select class="form-control select2" name="id_prodi" style="width: 100%;">
                                    <option value="all" {{ $id_prodi == 'all' || empty($id_prodi) ? 'selected' : '' }}>-- Semua Program Studi --</option>
                                    @foreach ($prodi_list as $item_prd)
                                        <option value="{{ $item_prd->kodeprodi }}" {{ $id_prodi == $item_prd->kodeprodi || $id_prodi == $item_prd->id_prodi ? 'selected' : '' }}>
                                            {{ $item_prd->prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="hidden-xs" style="display: block;">&nbsp;</label>
                                <div class="btn-action-group">
                                    <button type="submit" class="btn btn-primary btn-sm" title="Terapkan Filter">
                                        <i class="fa fa-search"></i> Filter
                                    </button>
                                    <a href="{{ url('data_krs') }}" class="btn btn-default btn-sm" title="Reset ke Periode Aktif">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                    <button type="submit" formaction="{{ url('export_krs_mhs') }}" formmethod="POST" class="btn btn-success btn-sm" title="Download Format Excel">
                                        <i class="fa fa-file-excel-o"></i> Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Rekapitulasi KRS --}}
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-table"></i> Rekap KRS Mahasiswa:
                    <strong>{{ $namaperiodetahun }} {{ $namaperiodetipe }}</strong>
                </h3>
                <div class="box-tools pull-right">
                    @if ($id_prodi && $id_prodi !== 'all')
                        @php
                            $selectedProdi = $prodi_list->first(function($p) use ($id_prodi) {
                                return $p->kodeprodi == $id_prodi || $p->id_prodi == $id_prodi;
                            });
                        @endphp
                        <span class="label label-primary" style="font-size: 12px; padding: 4px 8px;">
                            <i class="fa fa-university"></i> {{ $selectedProdi ? $selectedProdi->prodi : 'Prodi Terpilih' }}
                        </span>
                    @else
                        <span class="label label-default" style="font-size: 12px; padding: 4px 8px;">
                            <i class="fa fa-globe"></i> Semua Program Studi
                        </span>
                    @endif
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-gray-light">
                            <th style="width: 40px; text-align: center; vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">Kode / Matakuliah</th>
                            <th style="width: 85px; text-align: center; vertical-align: middle;">SKS</th>
                            <th style="vertical-align: middle;">Program Studi</th>
                            <th style="width: 70px; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="width: 110px; text-align: center; vertical-align: middle;">Peserta KRS</th>
                            <th style="vertical-align: middle;">Dosen Pengampu</th>
                            <th style="width: 85px; text-align: center; vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($krs as $index => $item)
                            <tr>
                                <td align="center" style="vertical-align: middle;">{{ $index + 1 }}</td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-default" style="font-size: 11px; letter-spacing: 0.5px;">{{ $item->kode }}</span>
                                    <div style="font-weight: 600; margin-top: 3px; font-size: 13px;">{{ $item->makul }}</div>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="badge bg-light-blue" style="font-size: 12px;">
                                        {{ $item->akt_sks_teori + $item->akt_sks_praktek }} SKS
                                    </span>
                                    <div style="font-size: 11px; color: #777; margin-top: 2px;">
                                        T:{{ $item->akt_sks_teori }} | P:{{ $item->akt_sks_praktek }}
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $item->prodi }}</strong>
                                    @if (!empty($item->daftar_konsentrasi))
                                        <br><small class="text-muted"><i class="fa fa-tags"></i> {{ $item->daftar_konsentrasi }}</small>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info" style="font-size: 12px;">{{ $item->kelas }}</span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    @if ($item->jml_mhs > 0)
                                        <span class="badge bg-green" style="font-size: 13px; padding: 4px 8px;" title="{{ $item->jml_mhs }} mahasiswa terdaftar">
                                            <i class="fa fa-user"></i> {{ $item->jml_mhs }} Mhs
                                        </span>
                                    @else
                                        <span class="badge bg-gray" style="font-size: 12px; padding: 4px 8px;" title="Belum ada mahasiswa mengambil kelas ini">
                                            0 Mhs
                                        </span>
                                    @endif
                                </td>
                                <td style="vertical-align: middle;">
                                    @if (!empty($item->nama))
                                        <i class="fa fa-user text-muted" style="margin-right: 3px;"></i> {{ $item->nama }}
                                    @else
                                        <span class="text-muted"><i class="fa fa-question-circle"></i> Belum Ditentukan</span>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    @if ($item->jml_mhs > 0)
                                        <a href="{{ url('cek_krs_mhs/' . $item->ids_kurperiode) }}" class="btn btn-primary btn-sm btn-flat" title="Lihat {{ $item->jml_mhs }} Mahasiswa Terdaftar">
                                            <i class="fa fa-users"></i> Cek KRS
                                        </a>
                                    @else
                                        <a href="{{ url('cek_krs_mhs/' . $item->ids_kurperiode) }}" class="btn btn-default btn-sm btn-flat" title="Kelas belum memiliki mahasiswa" style="opacity: 0.6;">
                                            <i class="fa fa-users"></i> Cek KRS
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                                    <i class="fa fa-info-circle fa-2x"></i><br>
                                    <span style="font-size: 14px; margin-top: 5px; display: inline-block;">
                                        Tidak ada data matakuliah atau kelas yang dibuka pada periode ini.
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
