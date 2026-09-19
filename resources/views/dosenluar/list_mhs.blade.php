@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data List Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu') }}"> Data Matakuliah yang diampu</a></li>
            <li class="active">Data List Mahasiswa</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        @php
            $is_obe = ($nilai && ((float)$nilai->partisipatif > 0 || (float)$nilai->proyek > 0 || (float)$nilai->tugas > 0 || (float)$nilai->kuis > 0));
        @endphp
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> Data List Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div style="margin-bottom: 15px;">
                    @if ($nilai == null)
                        <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#addsettingnilai">
                            <i class="fa fa-cogs"></i> Setting Persentase (%) Nilai
                        </button>
                    @else
                        <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal"
                            data-target="#editsettingnilai{{ $nilai->id_settingnilai }}">
                            <i class="fa fa-pencil"></i> Edit Setting Persentase (%) Nilai
                        </button>

                        {{-- Tombol input komponen evaluasi yang aktif (> 0%) --}}
                        @if ((float)$nilai->partisipatif > 0)
                            <a href="/input_partisipatif/{{ $ids }}" class="btn btn-primary btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Partisipatif ({{ $nilai->partisipatif }}%)
                            </a>
                        @endif

                        @if ((float)$nilai->proyek > 0)
                            <a href="/input_proyek/{{ $ids }}" class="btn btn-success btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Proyek ({{ $nilai->proyek }}%)
                            </a>
                        @endif

                        @if ((float)$nilai->tugas > 0)
                            <a href="/input_tugas/{{ $ids }}" class="btn btn-info btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Tugas ({{ $nilai->tugas }}%)
                            </a>
                        @endif

                        @if ((float)$nilai->kuis > 0)
                            <a href="/input_kuis/{{ $ids }}" class="btn btn-warning btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Quiz ({{ $nilai->kuis }}%)
                            </a>
                        @endif

                        {{-- Dukungan untuk matakuliah format lama yang hanya menggunakan KAT --}}
                        @if (!$is_obe && (float)$nilai->kat > 0)
                            <a href="/input_kat/{{ $ids }}" class="btn btn-success btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Nilai KAT ({{ $nilai->kat }}%)
                            </a>
                        @endif

                        @if ((float)$nilai->uts > 0)
                            <a href="/input_uts/{{ $ids }}" class="btn bg-purple btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Nilai UTS ({{ $nilai->uts }}%)
                            </a>
                        @endif

                        @if ((float)$nilai->uas > 0)
                            <a href="/input_uas/{{ $ids }}" class="btn bg-maroon btn-sm btn-flat">
                                <i class="fa fa-pencil"></i> Input Nilai UAS ({{ $nilai->uas }}%)
                            </a>
                        @endif

                        <button type="button" class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#modal-danger">
                            <i class="fa fa-calculator"></i> Generate Nilai Akhir
                        </button>
                    @endif
                </div>

                {{-- Modal Edit Setting Nilai --}}
                @if ($nilai != null)
                    <div class="modal fade" id="editsettingnilai{{ $nilai->id_settingnilai }}" tabindex="-1" role="dialog"
                        aria-labelledby="editSettingLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form method="post" action="/put_settingnilai_dsn_luar/{{ $nilai->id_settingnilai }}" class="form-setting-nilai">
                                @csrf
                                @method('put')
                                <input type="hidden" value="{{ $nilai->id_kurperiode }}" name="id_kurperiode">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="editSettingLabel"><i class="fa fa-cogs"></i> Edit Setting Persentase Nilai Matakuliah (OBE / PDDikti)</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="callout callout-info" style="margin-bottom: 15px;">
                                            <p><i class="fa fa-info-circle"></i> Tentukan bobot evaluasi untuk setiap komponen. Total seluruh bobot harus tepat <strong>100%</strong>.</p>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr class="bg-gray-light">
                                                        <th width="35%">Basis Evaluasi</th>
                                                        <th width="35%">Komponen Evaluasi</th>
                                                        <th width="30%">Bobot (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Aktivitas Partisipatif</strong></td>
                                                        <td>-</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="partisipatif" class="form-control bobot-input"
                                                                    value="{{ $nilai->partisipatif ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Hasil Proyek</strong></td>
                                                        <td>-</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="proyek" class="form-control bobot-input"
                                                                    value="{{ $nilai->proyek ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="4" style="vertical-align: middle;"><strong>Kognitif / Pengetahuan</strong></td>
                                                        <td>Tugas</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="tugas" class="form-control bobot-input"
                                                                    value="{{ $nilai->tugas ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Quiz</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="kuis" class="form-control bobot-input"
                                                                    value="{{ $nilai->kuis ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>UTS</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="uts" class="form-control bobot-input"
                                                                    value="{{ $nilai->uts ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>UAS</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="any" min="0" max="100" name="uas" class="form-control bobot-input"
                                                                    value="{{ $nilai->uas ?? 0 }}" required>
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-gray-light">
                                                        <th colspan="2" class="text-right">Total Bobot:</th>
                                                        <th>
                                                            <span class="total-bobot-badge label label-success" style="font-size: 13px;">100%</span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary btn-flat btn-submit-setting"><i class="fa fa-save"></i> Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Modal Tambah Setting Nilai --}}
                <div class="modal fade" id="addsettingnilai" tabindex="-1" role="dialog"
                    aria-labelledby="addSettingLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form method="post" action="{{ url('post_settingnilai_dsn_luar') }}" class="form-setting-nilai">
                            @csrf
                            <input type="hidden" name="id_kurperiode" value="{{ $ids }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="addSettingLabel"><i class="fa fa-cogs"></i> Setting Persentase Nilai Matakuliah (OBE / PDDikti)</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="callout callout-info" style="margin-bottom: 15px;">
                                        <p><i class="fa fa-info-circle"></i> Tentukan bobot evaluasi untuk setiap komponen. Total seluruh bobot harus tepat <strong>100%</strong>.</p>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr class="bg-gray-light">
                                                    <th width="35%">Basis Evaluasi</th>
                                                    <th width="35%">Komponen Evaluasi</th>
                                                    <th width="30%">Bobot (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Aktivitas Partisipatif</strong></td>
                                                    <td>-</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="partisipatif" class="form-control bobot-input"
                                                                value="5" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Hasil Proyek</strong></td>
                                                    <td>-</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="proyek" class="form-control bobot-input"
                                                                value="5" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="4" style="vertical-align: middle;"><strong>Kognitif / Pengetahuan</strong></td>
                                                    <td>Tugas</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="tugas" class="form-control bobot-input"
                                                                value="10" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Quiz</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="kuis" class="form-control bobot-input"
                                                                value="15" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>UTS</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="uts" class="form-control bobot-input"
                                                                value="30" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>UAS</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="any" min="0" max="100" name="uas" class="form-control bobot-input"
                                                                value="35" required>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-gray-light">
                                                    <th colspan="2" class="text-right">Total Bobot:</th>
                                                    <th>
                                                        <span class="total-bobot-badge label label-success" style="font-size: 13px;">100%</span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary btn-flat btn-submit-setting"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Konfirmasi Generate Nilai Akhir --}}
                <div class="modal modal-danger fade" id="modal-danger">
                    <div class="modal-dialog">
                        <form action="{{ url('generate_nilai_akhir_dsn_luar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_kurperiode" value="{{ $ids }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title"><i class="fa fa-calculator"></i> Generate Nilai Akhir</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Anda yakin akan melakukan generate dan menyimpan nilai akhir untuk seluruh mahasiswa pada matakuliah ini?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-outline">Ya, Generate Nilai</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel Rekap Nilai Mahasiswa --}}
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-gray-light">
                                <th width="4%" class="text-center">No</th>
                                <th width="10%" class="text-center">NIM</th>
                                <th width="20%">Nama Mahasiswa</th>
                                <th width="12%">Program Studi</th>
                                <th width="6%" class="text-center">Kelas</th>
                                <th width="6%" class="text-center">Angkatan</th>

                                @if ($is_obe)
                                    @if ((float)$nilai->partisipatif > 0)
                                        <th class="text-center">Partisipatif<br><small class="text-muted">({{ $nilai->partisipatif }}%)</small></th>
                                    @endif
                                    @if ((float)$nilai->proyek > 0)
                                        <th class="text-center">Proyek<br><small class="text-muted">({{ $nilai->proyek }}%)</small></th>
                                    @endif
                                    @if ((float)$nilai->tugas > 0)
                                        <th class="text-center">Tugas<br><small class="text-muted">({{ $nilai->tugas }}%)</small></th>
                                    @endif
                                    @if ((float)$nilai->kuis > 0)
                                        <th class="text-center">Quiz<br><small class="text-muted">({{ $nilai->kuis }}%)</small></th>
                                    @endif
                                    @if ((float)$nilai->uts > 0)
                                        <th class="text-center">UTS<br><small class="text-muted">({{ $nilai->uts }}%)</small></th>
                                    @endif
                                    @if ((float)$nilai->uas > 0)
                                        <th class="text-center">UAS<br><small class="text-muted">({{ $nilai->uas }}%)</small></th>
                                    @endif
                                @else
                                    <th class="text-center">Nilai KAT<br><small class="text-muted">({{ $nilai->kat ?? 0 }}%)</small></th>
                                    <th class="text-center">Nilai UTS<br><small class="text-muted">({{ $nilai->uts ?? 0 }}%)</small></th>
                                    <th class="text-center">Nilai UAS<br><small class="text-muted">({{ $nilai->uas ?? 0 }}%)</small></th>
                                @endif

                                <th width="8%" class="text-center">Nilai AKHIR</th>
                                <th width="8%" class="text-center">Nilai HURUF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($ck as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $item->nim }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td class="text-center">{{ $item->kelas }}</td>
                                    <td class="text-center">{{ $item->angkatan }}</td>

                                    @if ($is_obe)
                                        @if ((float)$nilai->partisipatif > 0)
                                            <td class="text-center">{{ $item->nilai_partisipatif ?? 0 }}</td>
                                        @endif
                                        @if ((float)$nilai->proyek > 0)
                                            <td class="text-center">{{ $item->nilai_proyek ?? 0 }}</td>
                                        @endif
                                        @if ((float)$nilai->tugas > 0)
                                            <td class="text-center">{{ $item->nilai_tugas ?? 0 }}</td>
                                        @endif
                                        @if ((float)$nilai->kuis > 0)
                                            <td class="text-center">{{ $item->nilai_kuis ?? 0 }}</td>
                                        @endif
                                        @if ((float)$nilai->uts > 0)
                                            <td class="text-center">{{ $item->nilai_UTS ?? 0 }}</td>
                                        @endif
                                        @if ((float)$nilai->uas > 0)
                                            <td class="text-center">{{ $item->nilai_UAS ?? 0 }}</td>
                                        @endif
                                    @else
                                        <td class="text-center">{{ $item->nilai_KAT ?? 0 }}</td>
                                        <td class="text-center">{{ $item->nilai_UTS ?? 0 }}</td>
                                        <td class="text-center">{{ $item->nilai_UAS ?? 0 }}</td>
                                    @endif

                                    <td class="text-center">
                                        <strong>{{ floor((float)$item->nilai_AKHIR_angka) == (float)$item->nilai_AKHIR_angka ? (int)$item->nilai_AKHIR_angka : round((float)$item->nilai_AKHIR_angka, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->nilai_AKHIR == 'A')
                                            <span class="label label-success" style="font-size: 12px;">A</span>
                                        @elseif ($item->nilai_AKHIR == 'B+' || $item->nilai_AKHIR == 'B')
                                            <span class="label label-primary" style="font-size: 12px;">{{ $item->nilai_AKHIR }}</span>
                                        @elseif ($item->nilai_AKHIR == 'C+' || $item->nilai_AKHIR == 'C')
                                            <span class="label label-warning" style="font-size: 12px;">{{ $item->nilai_AKHIR }}</span>
                                        @elseif ($item->nilai_AKHIR == 'D' || $item->nilai_AKHIR == 'E')
                                            <span class="label label-danger" style="font-size: 12px;">{{ $item->nilai_AKHIR }}</span>
                                        @else
                                            <span class="label label-default" style="font-size: 12px;">{{ $item->nilai_AKHIR ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateFormTotal(form) {
                var inputs = form.querySelectorAll('.bobot-input');
                var total = 0;
                inputs.forEach(function (input) {
                    var val = parseFloat(input.value) || 0;
                    total += val;
                });

                var badge = form.querySelector('.total-bobot-badge');
                var submitBtn = form.querySelector('.btn-submit-setting');

                total = Math.round(total * 100) / 100;
                if (total === 100) {
                    badge.className = 'total-bobot-badge label label-success';
                    badge.style.fontSize = '13px';
                    badge.textContent = '100% (Sesuai)';
                    if (submitBtn) submitBtn.disabled = false;
                } else {
                    badge.className = 'total-bobot-badge label label-danger';
                    badge.style.fontSize = '13px';
                    badge.textContent = total + '% (Harus 100%)';
                }
            }

            var forms = document.querySelectorAll('.form-setting-nilai');
            forms.forEach(function (form) {
                var inputs = form.querySelectorAll('.bobot-input');
                inputs.forEach(function (input) {
                    input.addEventListener('input', function () {
                        updateFormTotal(form);
                    });
                });
                updateFormTotal(form);

                form.addEventListener('submit', function (e) {
                    var inputs = form.querySelectorAll('.bobot-input');
                    var total = 0;
                    inputs.forEach(function (input) {
                        total += parseFloat(input.value) || 0;
                    });
                    total = Math.round(total * 100) / 100;
                    if (total !== 100) {
                        e.preventDefault();
                        alert('Total bobot harus tepat 100%! Total saat ini: ' + total + '%');
                    }
                });
            });
        });
    </script>
@endsection
