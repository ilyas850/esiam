@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <div class="header-wrapper">
            <h1 class="page-title">
                <i class="fas fa-file-alt"></i>
                View Berita Acara Perkuliahan
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-home"></i> Halaman Utama</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('rekap_perkuliahan') }}">Rekap Perkuliahan</a></li>
                    <li class="breadcrumb-item"><a href="/cek_rekapan/{{$dtbp->id_kurperiode}}">Cek BAP</a></li>
                    <li class="breadcrumb-item active">View BAP</li>
                </ol>
            </nav>
        </div>
    </section>
@endsection

@section('content')
    <section class="content">
        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <a class="btn btn-primary btn-lg" href="/cek_rekapan/{{$dtbp->id_kurperiode}}">
                Kembali
            </a>
            <a class="btn btn-warning btn-lg ml-2" href="/cek_print_bap/{{$dtbp->id_bap}}" target="_blank">
                <i class="fa fa-print"></i> CETAK BAP
            </a>
        </div>
        <br>
        <!-- Header Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="card-title mb-2">
                    <i class="fas fa-graduation-cap"></i>
                    Laporan Pembelajaran Daring
                </h2>
                <h4 class="mb-0">
                    Prodi {{$prd}} | Semester {{$tipe}} – {{$tahun}}
                </h4>
            </div>
        </div>

        <div class="row">
            <!-- Main Information Card -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informasi Perkuliahan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-book text-success"></i>
                                        Mata Kuliah
                                    </label>
                                    <div class="info-value">{{$data->makul}}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-user-tie text-info"></i>
                                        Nama Dosen
                                    </label>
                                    <div class="info-value">{{$data->nama}}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-users text-warning"></i>
                                        Kelas / Semester
                                    </label>
                                    <div class="info-value">{{$data->kelas}} / {{$data->semester}}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-desktop text-purple"></i>
                                        Media Pembelajaran
                                    </label>
                                    <div class="info-value">{{$dtbp->media_pembelajaran}}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-clock text-primary"></i>
                                        Waktu Pelaksanaan
                                    </label>
                                    <div class="info-value">{{$dtbp->jam_mulai}} - {{$dtbp->jam_selsai}}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="fas fa-calendar text-danger"></i>
                                        Tanggal Perkuliahan
                                    </label>
                                    <div class="info-value">{{$dtbp->tanggal}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-primary"></i>
                            Ringkasan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="summary-item mb-4">
                            <div class="summary-label">
                                <i class="fas fa-hashtag text-info"></i>
                                Pertemuan
                            </div>
                            <div class="summary-value bg-info">
                                Ke-{{$dtbp->pertemuan}}
                            </div>
                        </div>

                        <div class="attendance-section">
                            <div class="attendance-header mb-3">
                                <h6 class="attendance-title">
                                    <i class="fas fa-user-check text-success me-2"></i>
                                    Kehadiran Mahasiswa
                                </h6>
                            </div>

                            <div class="attendance-stats">
                                <div class="attendance-item present">
                                    <div class="attendance-icon">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="attendance-info">
                                        <div class="attendance-count">{{$dtbp->hadir}}</div>
                                        <div class="attendance-text">Mahasiswa Hadir</div>
                                    </div>
                                </div>

                                <div class="attendance-divider"></div>

                                <div class="attendance-item absent">
                                    <div class="attendance-icon">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                    <div class="attendance-info">
                                        <div class="attendance-count">{{$dtbp->tidak_hadir}}</div>
                                        <div class="attendance-text">Tidak Hadir</div>
                                    </div>
                                </div>
                            </div>

                            <div class="attendance-total mt-3">
                                <div class="total-students">
                                    <span class="total-label">Total Mahasiswa:</span>
                                    <span class="total-number">{{$dtbp->hadir + $dtbp->tidak_hadir}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Material Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt text-primary"></i>
                    Materi Perkuliahan
                </h5>
            </div>
            <div class="card-body">
                <div class="material-content">
                    <p class="material-text">{{$dtbp->materi_kuliah}}</p>
                </div>
            </div>
        </div>

        <!-- Attachments Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paperclip text-primary"></i>
                    Lampiran & Materi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Kuliah Tatap Muka -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="attachment-card">
                            <div class="attachment-icon bg-primary">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="attachment-content">
                                <h6>Kuliah Tatap Muka</h6>
                                @if (($dtbp->file_kuliah_tatapmuka) != null)
                                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Kuliah Tatap Muka/{{$dtbp->file_kuliah_tatapmuka}}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-times-circle"></i> Tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Materi Perkuliahan -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="attachment-card">
                            <div class="attachment-icon bg-success">
                                <i class="fas fa-file-powerpoint"></i>
                            </div>
                            <div class="attachment-content">
                                <h6>Materi Perkuliahan</h6>
                                @if (($dtbp->file_materi_kuliah) != null)
                                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Materi Kuliah/{{$dtbp->file_materi_kuliah}}"
                                        target="_blank" class="btn btn-success btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-times-circle"></i> Tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Materi Tugas -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="attachment-card">
                            <div class="attachment-icon bg-warning">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="attachment-content">
                                <h6>Materi Tugas</h6>
                                @if (($dtbp->file_materi_tugas) != null)
                                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Tugas Kuliah/{{$dtbp->file_materi_tugas}}"
                                        target="_blank" class="btn btn-warning btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-times-circle"></i> Tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Link Materi -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="attachment-card">
                            <div class="attachment-icon bg-info">
                                <i class="fas fa-link"></i>
                            </div>
                            <div class="attachment-content">
                                <h6>Link Materi</h6>
                                @if (($dtbp->link_materi) != null)
                                    <a href="{{$dtbp->link_materi}}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-external-link-alt"></i> Buka Link
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-times-circle"></i> Tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Styles -->
    <style>
        .header-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 10px;
            color: white;
            margin-bottom: 20px;
        }

        .page-title {
            color: white !important;
            margin-bottom: 10px;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: white;
        }

        .action-buttons {
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .summary-item {
            text-align: center;
        }

        .summary-label {
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: white;
            padding: 15px;
            border-radius: 10px;
        }

        .attendance-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .attendance-header {
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }

        .attendance-title {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
            font-size: 16px;
        }

        .attendance-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .attendance-item {
            display: flex;
            align-items: center;
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .attendance-item.present {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .attendance-item.absent {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }

        .attendance-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .attendance-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .attendance-icon i {
            font-size: 18px;
        }

        .attendance-info {
            flex: 1;
        }

        .attendance-count {
            font-size: 24px;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 5px;
        }

        .attendance-text {
            font-size: 13px;
            opacity: 0.9;
            font-weight: 500;
        }

        .attendance-divider {
            width: 2px;
            height: 50px;
            background: #dee2e6;
            margin: 0 15px;
            border-radius: 1px;
        }

        .attendance-total {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .total-students {
            background: white;
            padding: 12px 20px;
            border-radius: 25px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .total-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
            margin-right: 10px;
        }

        .total-number {
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
        }

        .material-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }

        .material-text {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 0;
            color: #2c3e50;
        }

        .attachment-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .attachment-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .attachment-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
        }

        .attachment-icon i {
            font-size: 24px;
        }

        .attachment-content h6 {
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .btn {
            border-radius: 25px;
            font-weight: 500;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-purple {
            background-color: #6f42c1;
            color: white;
        }

        .text-purple {
            color: #6f42c1 !important;
        }
    </style>
@endsection