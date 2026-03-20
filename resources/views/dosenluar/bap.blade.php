@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
                {{ $message }}
            </div>
        @endif

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-file-text-o"></i> Detail BAP & Absensi</h3>
            </div>
            
            <div class="box-body" style="background-color: #f9f9f9; border-bottom: 1px solid #eee;">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="dl-horizontal" style="margin-bottom: 0;">
                            <dt>Matakuliah :</dt>
                            <dd style="font-weight: bold; color: #3c8dbc;">{{ $bap->makul }}</dd>
                            <dt>Program Studi :</dt>
                            <dd>{{ $bap->prodi }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                         <dl class="dl-horizontal" style="margin-bottom: 0;">
                            <dt>Kelas :</dt>
                            <dd><span class="label label-success">{{ $bap->kelas }}</span></dd>
                            <dt>Semester :</dt>
                            <dd>{{ $bap->semester }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-12">
                        <a href="/input_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-success btn-flat">
                            <i class="fa fa-plus"></i> Input BAP
                        </a>
                        <a href="/sum_absen_dsn/{{ $bap->id_kurperiode }}" class="btn btn-info btn-flat">
                            <i class="fa fa-list-alt"></i> Absensi Perkuliahan
                        </a>
                        <a href="/jurnal_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-warning btn-flat">
                            <i class="fa fa-book"></i> Jurnal Perkuliahan
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example6" class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr class="bg-teal">
                                <th class="text-center" width="5%" style="vertical-align: middle;">Pertemuan</th>
                                <th class="text-center" width="15%" style="vertical-align: middle;">Waktu</th>
                                <th class="text-center" style="vertical-align: middle;">Materi & Pembelajaran</th>
                                <th class="text-center" width="10%" style="vertical-align: middle;">Kesesuaian RPS</th>
                                <th class="text-center" width="15%" style="vertical-align: middle;">Absensi</th>
                                <th class="text-center" width="12%" style="vertical-align: middle;">Aksi / Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <span class="badge bg-gray">Ke-{{ $item->pertemuan }}</span>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div style="margin-bottom: 5px; font-weight: bold;">
                                            <i class="fa fa-calendar"></i> {{ Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                        </div>
                                        <div class="label label-primary">
                                            <i class="fa fa-clock-o"></i> {{ Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}-{{ Carbon\Carbon::parse($item->jam_selsai)->format('H:i') }}
                                        </div>
                                        @if ($item->kurang_jam != null)
                                            <div style="margin-top: 5px; color: red;">
                                                <small><i class="fa fa-warning"></i> Kurang: {{ Carbon\Carbon::parse($item->kurang_jam)->format('H:i') }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="vertical-align: top;">
                                        <div>
                                            <span class="label label-info">{{ $item->tipe_kuliah }}</span>
                                            @if($item->praktikum)
                                                <span class="label label-warning">Praktikum</span>
                                            @endif
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <b>Materi:</b> {{ $item->materi_kuliah }}
                                        </div>
                                        @if($item->alasan_pembaharuan_materi)
                                            <div style="margin-top: 5px; font-style: italic; color: #666;">
                                                <b>Alasan Update:</b> {{ $item->alasan_pembaharuan_materi }}
                                            </div>
                                        @endif
                                        <div style="margin-top: 5px; font-size: 11px; color: #999;">
                                            <i class="fa fa-user"></i> Dosen: {{ $item->nama }}
                                        </div>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                       @if ($item->kesesuaian_rps == 'SESUAI')
                                            <div style="color: green; font-size: 16px;">
                                                <i class="fa fa-check-circle" title="Sesuai"></i>
                                            </div>
                                            <small class="text-success">Sesuai</small>
                                        @elseif($item->kesesuaian_rps == 'TIDAK SESUAI')
                                            <div style="color: red; font-size: 16px;">
                                                <i class="fa fa-times-circle" title="Tidak Sesuai"></i>
                                            </div>
                                            <small class="text-danger">Tidak Sesuai</small>
                                        @endif

                                        @if ($item->komentar != null)
                                            <div style="margin-top: 5px;">
                                                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modalTambahKomentar{{ $item->id_rps }}" title="Lihat Komentar">
                                                    <i class="fa fa-comment-o"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <table style="width: 100%; margin-bottom: 5px;">
                                            <tr>
                                                <td class="text-center" style="border-right: 1px solid #ddd;">
                                                    <span class="text-green" style="font-weight: bold; font-size: 16px;">{{ $item->hadir }}</span><br><small>Hadir</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-red" style="font-weight: bold; font-size: 16px;">{{ $item->tidak_hadir }}</span><br><small>Absen</small>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        @if ($item->hadir != null && $item->tidak_hadir != null)
                                            <a href="/edit_absen_dsn/{{ $item->id_bap }}" class="btn btn-success btn-xs btn-block">
                                                <i class="fa fa-edit"></i> Edit Absen
                                            </a>
                                        @elseif ($item->hadir == null && $item->tidak_hadir == null)
                                            <a href="/entri_absen_dsn/{{ $item->id_bap }}" class="btn btn-warning btn-xs btn-block">
                                                <i class="fa fa-edit"></i> Entri Absen
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="btn-group-vertical btn-block">
                                            <a href="/view_bap_dsn/{{ $item->id_bap }}" class="btn btn-info btn-xs btn-flat" title="Lihat Detail">
                                                <i class="fa fa-eye"></i> Detail
                                            </a>

                                            @if ($item->payroll_check == '2001-01-01' or $item->tanggal_validasi == null)
                                                <a href="/edit_bap_dsn/{{ $item->id_bap }}" class="btn btn-success btn-xs btn-flat" title="Edit BAP">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <a href="/delete_bap_dsn/{{ $item->id_bap }}" class="btn btn-danger btn-xs btn-flat" title="Hapus BAP" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            @else
                                                <span class="btn btn-default btn-xs btn-flat disabled" style="cursor: default; background-color: #f4f4f4;">
                                                    <i class="fa fa-lock text-yellow"></i> Valid
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL KOMENTAR (Inside loop for unique ID) --}}
                                <div class="modal fade" id="modalTambahKomentar{{ $item->id_rps }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-yellow">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><i class="fa fa-comment"></i> Komentar RPS</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/komentar_rps_makul/{{ $item->id_rps }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="form-group">
                                                        <label>Isi Komentar:</label>
                                                        <textarea class="form-control" name="komentar" cols="20" rows="5" readonly>{{ $item->komentar }}</textarea>
                                                    </div>
                                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
                                                    <div class="clearfix"></div>
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
        </div>
    </section>
@endsection
