@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-clipboard"></i> Detail Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-home"></i> Halaman Utama</a></li>
            <li><a href="{{ url('rekap_perkuliahan') }}">Rekap Perkuliahan</a></li>
            <li class="active">Cek BAP</li>
        </ol>
    </section>

    <section class="content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> {{ $message }}
            </div>
        @endif

        <!-- Info Card -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Mata Kuliah</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mata Kuliah</span>
                                <span class="info-box-number">{{ $bap->makul }}</span>
                                <span class="progress-description">
                                    Kelas: <strong>{{ $bap->kelas }}</strong> | Semester: <strong>{{ $bap->semester }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Program Studi</span>
                                <span class="info-box-number">{{ $bap->prodi }}</span>
                                <span class="progress-description">
                                    Total Pertemuan: <strong>{{ count($data) }}</strong> dari 16
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="box box-default">
            <div class="box-body">
                <a href="{{ url('rekap_perkuliahan') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <a href="/cek_sum_absen/{{ $bap->id_kurperiode }}" class="btn btn-info">
                    <i class="fa fa-list-alt"></i> Absensi Perkuliahan
                </a>
                <a href="/cek_jurnal_bap/{{ $bap->id_kurperiode }}" class="btn btn-warning">
                    <i class="fa fa-book"></i> Jurnal Perkuliahan
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pertemuan</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info">{{ count($data) }} Pertemuan</span>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tabelBap" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 80px;">
                                    Pertemuan
                                </th>
                                <th colspan="2" class="text-center">Tanggal</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle;">Jam</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 80px;">
                                    <i class="fa fa-clock-o"></i> Kurang
                                </th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Kuliah
                                </th>
                                <th colspan="2" class="text-center">Mode Kuliah</th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    <i class="fa fa-users"></i> Kehadiran
                                </th>
                                <th rowspan="2" class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    Aksi
                                </th>
                            </tr>
                            <tr class="bg-primary">
                                <th class="text-center"><i class="fa fa-calendar"></i> Kuliah</th>
                                <th class="text-center"><i class="fa fa-calendar-check-o"></i> Aktual</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-blue" style="font-size: 14px; padding: 8px 12px;">
                                            Ke-{{ $item->pertemuan }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-default">
                                            {{ Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                                            {{ Carbon\Carbon::parse($item->jam_selsai)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $kurangJam = Carbon\Carbon::parse($item->kurang_jam)->format('H:i');
                                        @endphp
                                        @if($kurangJam != '00:00')
                                            <span class="label label-danger">{{ $kurangJam }}</span>
                                        @else
                                            <span class="label label-success">{{ $kurangJam }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($item->materi_kuliah, 50) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($item->tipe_kuliah == 'Online')
                                            <span class="label label-info"><i class="fa fa-wifi"></i> {{ $item->tipe_kuliah }}</span>
                                        @else
                                            <span class="label label-success"><i class="fa fa-building"></i> {{ $item->tipe_kuliah }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="label label-default">{{ $item->jenis_kuliah }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-success"><strong>{{ $item->hadir }}</strong></span>
                                        <span class="text-muted">/</span>
                                        <span class="text-danger"><strong>{{ $item->tidak_hadir }}</strong></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="/cek_view_bap/{{ $item->id_bap }}" class="btn btn-primary btn-sm"
                                                title="Lihat Detail BAP" data-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="/cek_absen_bap/{{ $item->id_bap }}" class="btn btn-warning btn-sm"
                                                title="Lihat/Edit Absensi" data-toggle="tooltip">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-green"><i class="fa fa-check-circle"></i></span>
                            <h5 class="description-header">{{ count($data) }} / 16</h5>
                            <span class="description-text">TOTAL PERTEMUAN</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-info"><i class="fa fa-wifi"></i></span>
                            <h5 class="description-header">
                                {{ collect($data)->where('tipe_kuliah', 'Online')->count() }}
                            </h5>
                            <span class="description-text">PERTEMUAN ONLINE</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <span class="description-percentage text-success"><i class="fa fa-building"></i></span>
                            <h5 class="description-header">
                                {{ collect($data)->where('tipe_kuliah', 'Offline')->count() }}
                            </h5>
                            <span class="description-text">PERTEMUAN OFFLINE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .info-box-number {
            font-size: 18px;
        }
        .progress-description {
            font-size: 13px;
        }
        .table > thead > tr > th {
            border-bottom: 2px solid #ddd;
        }
        .badge {
            border-radius: 4px;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .description-header {
            font-size: 24px;
            font-weight: bold;
        }
        .box-footer .description-block {
            padding: 15px 0;
        }
        .align-middle {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('script')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
