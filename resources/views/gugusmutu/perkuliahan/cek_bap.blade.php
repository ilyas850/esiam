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
            <li><a href="{{ url('data_bap_gugusmutu') }}">BAP Perkuliahan</a></li>
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
                <a href="{{ url('data_bap_gugusmutu') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Daftar Pertemuan & Validasi RPS</h3>
                <div class="box-tools pull-right">
                    <span class="label label-info">{{ count($data) }} Pertemuan</span>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tabelBap" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center align-middle" style="vertical-align: middle; width: 100px;">
                                    Pertemuan
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Kuliah
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle;">
                                    Materi Pembelajaran (RPS)
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle; width: 150px;">
                                    Kesesuaian RPS
                                </th>
                                <th class="text-center align-middle" style="vertical-align: middle; width: 220px;">
                                    Aksi
                                </th>
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
                                    <td>
                                        <small>{{ $item->materi_kuliah }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->materi_pembelajaran }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->kesesuaian_rps == 'SESUAI')
                                            <span class="label label-success" style="font-size: 12px; padding: 5px 8px;">
                                                <i class="fa fa-check"></i> SESUAI
                                            </span>
                                        @elseif ($item->kesesuaian_rps == 'TIDAK SESUAI')
                                            <span class="label label-danger" style="font-size: 12px; padding: 5px 8px;">
                                                <i class="fa fa-times"></i> TIDAK SESUAI
                                            </span>
                                        @else
                                            <span class="label label-warning" style="font-size: 12px;">BELUM DIVALIDASI</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group mb-2" style="margin-bottom: 5px;">
                                            <a href="/view_bap_gugusmutu/{{ $item->id_bap }}" class="btn btn-primary btn-sm"
                                                title="Lihat Detail BAP" data-toggle="tooltip">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if ($item->komentar == null)
                                                <button class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#modalTambahKomentar{{ $item->id_rps }}" title="Tambah Komentar">
                                                    <i class="fa fa-comment-o"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-target="#modalTambahKomentar{{ $item->id_rps }}" title="Lihat/Edit Komentar">
                                                    <i class="fa fa-comment"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="btn-group" style="margin-bottom: 5px;">
                                             <a href="/validasi_sesuai/{{ $item->id_bap }}"
                                                class="btn btn-success btn-sm" title="Validasi Sesuai" data-toggle="tooltip">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            <a href="/validasi_tidak_sesuai/{{ $item->id_bap }}"
                                                class="btn btn-danger btn-sm" title="Validasi Tidak Sesuai" data-toggle="tooltip">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Komentar -->
                                <div class="modal fade" id="modalTambahKomentar{{ $item->id_rps }}" tabindex="-1"
                                    aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><i class="fa fa-comments"></i> Komentar RPS - Pertemuan Ke-{{ $item->pertemuan }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/komentar_rps_makul/{{ $item->id_rps }}"
                                                    method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <label>Komentar:</label>
                                                        <textarea class="form-control" name="komentar" rows="5" placeholder="Tulis komentar disini...">{{ $item->komentar }}</textarea>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
