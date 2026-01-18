@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-th-list"></i> Data Matakuliah
                    <b>{{ $nama_periodetahun }} - {{ $nama_periodetipe }}</b>
                </h3>
            </div>
            <div class="box-body table-responsive">
                <table id="example8" class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr class="bg-teal">
                            <th class="text-center" width="4%" style="vertical-align: middle;">No</th>
                            <th class="text-center" width="25%" style="vertical-align: middle;">Matakuliah</th>
                            <th class="text-center" style="vertical-align: middle;">Kelas & Prodi</th>
                            <th class="text-center" style="vertical-align: middle;">Jadwal & Ruang</th>
                            <th class="text-center" width="20%" style="vertical-align: middle;">Status Soal (UTS / UAS)</th>
                            <th class="text-center" width="15%" style="vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($makul as $item)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{ $no++ }}</td>
                                <td style="vertical-align: middle;">
                                    <span
                                        style="font-size: 15px; font-weight: bold; color: #3c8dbc;">{{ $item->makul }}</span><br>
                                    <small class="text-muted"><i class="fa fa-barcode"></i> {{ $item->kode }}</small>
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="label label-primary">{{ $item->prodi }}</span>
                                    <span class="label label-success">{{ $item->kelas }}</span>

                                    @if(isset($item->details) && count($item->details) > 0)
                                        <div style="margin-top: 5px; font-size: 12px; color: #666;">
                                            <i class="fa fa-angle-right"></i> Konsentrasi:
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
                                    <div class="row"
                                        style="margin-bottom: 5px; border-bottom: 1px dashed #ddd; padding-bottom: 5px;">
                                        <div class="col-xs-3 text-right"><b>UTS</b></div>
                                        <div class="col-xs-9">
                                            @if ($item->soal_uts == null)
                                                <button class="btn btn-default btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                    target="_blank" class="btn btn-primary btn-xs" title="Lihat">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif

                                            <!-- Validation Badge UTS -->
                                            @if ($item->validasi_uts == 'SUDAH')
                                                <span class="badge bg-green" title="Valid"><i class="fa fa-check"></i></span>
                                            @elseif ($item->validasi_uts == 'BELUM' or $item->validasi_uts == null)
                                                @if ($item->komentar_uts != null)
                                                    <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#modalTambahKomentarUts{{ $item->id_soal }}"
                                                        title="Lihat Komentar"><i class="fa fa-comment"></i></a>
                                                @else
                                                    <span class="badge bg-yellow" title="Belum Valid"><i
                                                            class="fa fa-hourglass-half"></i></span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- UAS SECTION -->
                                    <div class="row">
                                        <div class="col-xs-3 text-right"><b>UAS</b></div>
                                        <div class="col-xs-9">
                                            @if ($item->soal_uas == null)
                                                <button class="btn btn-default btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}">
                                                    <i class="fa fa-cloud-upload text-blue"></i> Upload
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                    target="_blank" class="btn btn-primary btn-xs" title="Lihat">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif

                                            <!-- Validation Badge UAS -->
                                            @if ($item->validasi_uas == 'SUDAH')
                                                <span class="badge bg-green" title="Valid"><i class="fa fa-check"></i></span>
                                            @elseif ($item->validasi_uas == 'BELUM' or $item->validasi_uas == null)
                                                @if ($item->komentar_uas != null)
                                                    <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#modalTambahKomentarUas{{ $item->id_soal }}"
                                                        title="Lihat Komentar"><i class="fa fa-comment"></i></a>
                                                @else
                                                    <span class="badge bg-yellow" title="Belum Valid"><i
                                                            class="fa fa-hourglass-half"></i></span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center" style="vertical-align: middle;">
                                    <div style="margin-bottom: 5px;">
                                        @if ($item->id_rps == null)
                                            <a href="/entri_rps/{{ $item->id_kurperiode }}"
                                                class="btn btn-success btn-xs btn-block">
                                                <i class="fa fa-plus"></i> Input RPS
                                            </a>
                                        @else
                                            <div class="btn-group">
                                                <a href="edit_rps/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs"
                                                    title="Edit RPS"><i class="fa fa-pencil"></i> RPS</a>
                                                <a href="cekmhs_dsn/{{ $item->id_kurperiode }}" class="btn btn-primary btn-xs"
                                                    title="Entri Nilai"><i class="fa fa-list-ol"></i> Nilai</a>
                                                <a href="entri_bap/{{ $item->id_kurperiode }}" class="btn btn-warning btn-xs"
                                                    title="BAP"><i class="fa fa-newspaper-o"></i> BAP</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="btn-group">
                                        <a href="/export_xlsnilai/{{ $item->id_kurperiode }}" class="btn btn-default btn-xs"
                                            title="Export Excel">
                                            <i class="fa fa-file-excel-o text-green"></i>
                                        </a>
                                        <a href="/unduh_pdf_nilai/{{ $item->id_kurperiode }}" class="btn btn-default btn-xs"
                                            title="Export PDF">
                                            <i class="fa fa-file-pdf-o text-red"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- MODALS (Inside loop to ensure ID uniqueness) --}}

                            <!-- Modal Upload UTS -->
                            <div class="modal fade" id="modalUploadSoalUts{{ $item->id_kurperiode }}" tabindex="-1"
                                role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-blue">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Upload Soal UTS - {{ $item->makul }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('simpan_soal_uts_dsn_dlm') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uts" class="form-control" required>
                                                        @if($item->tipe_ujian_uts)
                                                            <option value="{{ $item->tipe_ujian_uts }}">{{ $item->tipe_ujian_uts }}
                                                        </option>@endif
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UTS</label>
                                                    <input type="file" class="form-control" name="soal_uts"
                                                        accept="application/pdf">
                                                    <span class="help-block">Max. size 4 mb dengan format (.pdf)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uts" class="form-control" required>
                                                        @if($item->cetak_soal_uts)
                                                            <option value="{{ $item->cetak_soal_uts }}">{{ $item->cetak_soal_uts }}
                                                        </option>@endif
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Upload UAS -->
                            <div class="modal fade" id="modalUploadSoalUas{{ $item->id_kurperiode }}" tabindex="-1"
                                role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-blue">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Upload Soal UAS - {{ $item->makul }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('simpan_soal_uas_dsn_dlm') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uas" class="form-control" required>
                                                        @if($item->tipe_ujian_uas)
                                                            <option value="{{ $item->tipe_ujian_uas }}">{{ $item->tipe_ujian_uas }}
                                                        </option>@endif
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UAS</label>
                                                    <input type="file" class="form-control" name="soal_uas"
                                                        accept="application/pdf">
                                                    <span class="help-block">Max. size 4 mb dengan format (.pdf)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uas" class="form-control" required>
                                                        @if($item->cetak_soal_uas)
                                                            <option value="{{ $item->cetak_soal_uas }}">{{ $item->cetak_soal_uas }}
                                                        </option>@endif
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Komentar UTS -->
                            <div class="modal fade" id="modalTambahKomentarUts{{ $item->id_soal }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-red">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Komentar Validasi UTS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Isi Komentar:</label>
                                                <textarea class="form-control" readonly cols="20"
                                                    rows="5">{{ $item->komentar_uts }}</textarea>
                                            </div>
                                            <button type="button" class="btn btn-default pull-right"
                                                data-dismiss="modal">Tutup</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Komentar UAS -->
                            <div class="modal fade" id="modalTambahKomentarUas{{ $item->id_soal }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-red">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Komentar Validasi UAS</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Isi Komentar:</label>
                                                <textarea class="form-control" readonly cols="20"
                                                    rows="5">{{ $item->komentar_uas }}</textarea>
                                            </div>
                                            <button type="button" class="btn btn-default pull-right"
                                                data-dismiss="modal">Tutup</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection