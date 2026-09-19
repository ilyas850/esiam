@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Hasil Rincian AKM Mahasiswa
            <small>{{ $namaprodi }} - {{ $namaperiodetahun }} {{ $namaperiodetipe }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('rincian_data_akm') }}">Rincian AKM</a></li>
            <li class="active">Hasil Filter</li>
        </ol>
    </section>

    <section class="content">
        <!-- Box Aksi & Informasi Filter -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Export Excel Multi-Sheet</h3>
                <div class="box-tools pull-right">
                    <a href="{{ url('rincian_data_akm') }}" class="btn btn-default btn-sm btn-flat">
                        <i class="fa fa-arrow-left"></i> Kembali ke Filter
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <table class="table table-condensed table-bordered" style="margin-bottom: 10px;">
                            <tbody>
                                <tr>
                                    <th width="25%" class="bg-gray-light">Program Studi</th>
                                    <td><b>{{ $namaprodi }}</b></td>
                                    <th width="25%" class="bg-gray-light">Batas Semester</th>
                                    <td><b>{{ $namaperiodetahun }} - {{ $namaperiodetipe }}</b></td>
                                </tr>
                                <tr>
                                    <th class="bg-gray-light">Angkatan Terpilih</th>
                                    <td>
                                        @if (!empty($idangkatan))
                                            @foreach ($idangkatan as $akt)
                                                <span class="label label-primary">Angkatan {{ $akt }}</span>
                                            @endforeach
                                        @else
                                            <span class="label label-default">Semua Angkatan</span>
                                        @endif
                                    </td>
                                    <th class="bg-gray-light">Total Mahasiswa</th>
                                    <td><span class="badge bg-green">{{ count($studentsData) }} Mahasiswa</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                        @if (count($studentsData) > 0)
                            <form action="{{ url('export_rincian_akm_xls') }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="id_prodi" value="{{ $idprodi }}">
                                <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                @foreach ($idangkatan as $akt)
                                    <input type="hidden" name="id_angkatan[]" value="{{ $akt }}">
                                @endforeach
                                <button type="submit" class="btn btn-success btn-lg btn-flat">
                                    <i class="fa fa-download"></i> Unduh Excel Multi-Sheet ({{ count($studentsData) }} Sheet)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rekap Mahasiswa -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> Rekap Nilai & Aktivitas Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="bg-primary">
                                <th style="width: 40px; text-align: center;">No</th>
                                <th style="text-align: center;">NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th style="text-align: center;">Angkatan</th>
                                <th style="text-align: center;">SKS Smt</th>
                                <th style="text-align: center;">IPS</th>
                                <th style="text-align: center;" title="Total SKS yang diakui untuk IPK (hanya nilai lulus >= C, nilai D dan E tidak dihitung)">Total SKS (Lulus)</th>
                                <th style="text-align: center;">IPK</th>
                                <th style="text-align: center; width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($studentsData as $item)
                                @php
                                    $mhs = $item['student'];
                                    $summary = $item['summary'];
                                    $courses = $item['courses'];
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $no++ }}</td>
                                    <td style="text-align: center;"><b>{{ $mhs->nim }}</b></td>
                                    <td>{{ $mhs->nama }}</td>
                                    <td style="text-align: center;"><span class="label label-info">{{ $mhs->angkatan }}</span></td>
                                    <td style="text-align: center;"><b>{{ $summary['sks_semester'] }}</b> SKS</td>
                                    <td style="text-align: center;">
                                        <span class="label {{ $summary['ips'] >= 3.0 ? 'label-success' : ($summary['ips'] >= 2.0 ? 'label-warning' : 'label-danger') }}" style="font-size: 13px;">
                                            {{ number_format($summary['ips'], 2) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-green" style="font-size: 12px;" title="Hanya mata kuliah dengan nilai >= C yang dihitung ke IPK">
                                            <b>{{ $summary['total_sks_ipk'] }}</b> SKS
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="label {{ $summary['ipk'] >= 3.0 ? 'label-success' : ($summary['ipk'] >= 2.0 ? 'label-warning' : 'label-danger') }}" style="font-size: 13px;">
                                            {{ number_format($summary['ipk'], 2) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- Tombol Modal Detail -->
                                        <button type="button" class="btn btn-info btn-xs btn-flat" data-toggle="modal" data-target="#modal-detail-{{ $mhs->idstudent }}">
                                            <i class="fa fa-eye"></i> Rincian
                                        </button>

                                        <!-- Tombol Export Single Excel -->
                                        <form action="{{ url('export_rincian_akm_single_xls') }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <input type="hidden" name="id_prodi" value="{{ $idprodi }}">
                                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                            <input type="hidden" name="id_student" value="{{ $mhs->idstudent }}">
                                            <button type="submit" class="btn btn-success btn-xs btn-flat" title="Export Excel Mahasiswa Ini">
                                                <i class="fa fa-file-excel-o"></i> Unduh
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <em>Tidak ada data mahasiswa yang memenuhi kriteria filter.</em>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modals Rincian Mata Kuliah Per Mahasiswa -->
        @foreach ($studentsData as $item)
            @php
                $mhs = $item['student'];
                $summary = $item['summary'];
                $courses = $item['courses'];
            @endphp
            <div class="modal fade" id="modal-detail-{{ $mhs->idstudent }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1100px;">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.9;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">
                                <i class="fa fa-user"></i> Rincian AKM Mahasiswa: <b>{{ $mhs->nama }}</b> ({{ $mhs->nim }})
                            </h4>
                        </div>
                        <div class="modal-body">
                            <!-- Profil Mahasiswa -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-6">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th width="30%" class="bg-gray-light">NIM</th>
                                            <td><b>{{ $mhs->nim }}</b></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-light">Nama</th>
                                            <td>{{ $mhs->nama }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <th width="30%" class="bg-gray-light">Program Studi</th>
                                            <td>{{ $mhs->prodi }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-light">Angkatan</th>
                                            <td>{{ $mhs->angkatan }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Tabel Rincian Mata Kuliah -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th style="width: 35px; text-align: center;">No</th>
                                            <th style="text-align: center;">Semester</th>
                                            <th style="text-align: center;">Kode MK</th>
                                            <th>Mata Kuliah</th>
                                            <th style="text-align: center;" title="SKS yang diambil pada semester tersebut">SKS Ambil</th>
                                            <th style="text-align: center;" title="SKS yang diakui untuk IPK (hanya nilai >= C, D dan E bernilai 0)">SKS Diakui (IPK)</th>
                                            <th style="text-align: center;">Nilai Angka</th>
                                            <th style="text-align: center;">Nilai Huruf</th>
                                            <th style="text-align: center;">Bobot</th>
                                            <th style="text-align: center;">Nilai Mutu (IPK)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $noMk = 1; @endphp
                                        @forelse ($courses as $c)
                                            <tr class="{{ !empty($c->is_current_semester) ? 'info' : '' }}">
                                                <td style="text-align: center;">{{ $noMk++ }}</td>
                                                <td style="text-align: center;">
                                                    <span class="label {{ !empty($c->is_current_semester) ? 'label-primary' : 'label-default' }}">
                                                        {{ $c->semester_label }}
                                                    </span>
                                                    @if (!empty($c->is_current_semester))
                                                        <br><small class="text-primary"><b>(Smt Berjalan)</b></small>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;"><code>{{ $c->kode }}</code></td>
                                                <td>{{ $c->makul }}</td>
                                                <td style="text-align: center;"><b>{{ $c->sks }}</b></td>
                                                <td style="text-align: center;">
                                                    @if ($c->is_lulus)
                                                        <span class="badge bg-green"><b>{{ $c->sks_diakui }}</b></span>
                                                    @else
                                                        <span class="badge bg-red" title="Nilai {{ $c->nilai_AKHIR ?: '-' }} tidak dihitung ke IPK">0</span>
                                                        <br><small class="text-danger" style="font-size: 10px;"><b>(Tidak Lulus)</b></small>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">{{ $c->nilai_AKHIR_angka !== null ? $c->nilai_AKHIR_angka : '-' }}</td>
                                                <td style="text-align: center;">
                                                    <span class="label {{ in_array($c->nilai_AKHIR, ['A', 'B+', 'B', 'C+', 'C']) ? 'label-success' : ($c->nilai_AKHIR == 'D' ? 'label-warning' : 'label-danger') }}">
                                                        {{ $c->nilai_AKHIR ?: '-' }}
                                                    </span>
                                                </td>
                                                <td style="text-align: center;">{{ number_format($c->bobot, 1) }}</td>
                                                <td style="text-align: center;">
                                                    @if ($c->is_lulus)
                                                        <b>{{ number_format($c->mutu_diakui, 2) }}</b>
                                                    @else
                                                        <span class="text-muted">0.00</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">
                                                    <em>Belum ada riwayat mata kuliah untuk mahasiswa ini.</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <!-- Baris 1: IPK Kumulatif (hanya nilai lulus >= C) -->
                                        <tr class="bg-gray-light" style="font-size: 13px; font-weight: bold;">
                                            <td colspan="4" style="text-align: right;">
                                                IPK (KUMULATIF - NILAI LULUS &ge; C):
                                                <br><small class="text-muted" style="font-weight: normal;">*Nilai D & E tidak dihitung ke SKS Diakui maupun Mutu IPK</small>
                                            </td>
                                            <td style="text-align: center; color: #777;">
                                                <small>Total Ambil:</small><br>
                                                {{ collect($courses)->sum('sks') }} SKS
                                            </td>
                                            <td style="text-align: center; color: #0073b7; font-size: 14px;">
                                                <small>Total Diakui:</small><br>
                                                <b>{{ $summary['total_sks_ipk'] }} SKS</b>
                                            </td>
                                            <td colspan="3" style="text-align: right;">
                                                TOTAL MUTU IPK: <b>{{ number_format($summary['total_mutu_ipk'], 2) }}</b>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="label {{ $summary['ipk'] >= 3.0 ? 'label-success' : ($summary['ipk'] >= 2.0 ? 'label-warning' : 'label-danger') }}" style="font-size: 14px; padding: 4px 8px; display: inline-block;">
                                                    IPK: {{ number_format($summary['ipk'], 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <!-- Baris 2: IPS Semester Berjalan (termasuk nilai D & E jika ada) -->
                                        <tr class="bg-info" style="font-size: 13px; font-weight: bold;">
                                            <td colspan="4" style="text-align: right;">
                                                IPS (SEMESTER BERJALAN: {{ $namaperiodetahun }} {{ $namaperiodetipe }}):
                                                <br><small class="text-muted" style="font-weight: normal;">*Mata kuliah semester berjalan tetap dihitung ke IPS</small>
                                            </td>
                                            <td style="text-align: center; color: #0073b7; font-size: 14px;">
                                                <small>SKS Smt:</small><br>
                                                <b>{{ $summary['sks_semester'] }} SKS</b>
                                            </td>
                                            <td style="text-align: center; color: #777;">
                                                -
                                            </td>
                                            <td colspan="3" style="text-align: right;">
                                                TOTAL MUTU IPS: <b>{{ number_format($summary['mutu_semester'], 2) }}</b>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="label {{ $summary['ips'] >= 3.0 ? 'label-success' : ($summary['ips'] >= 2.0 ? 'label-warning' : 'label-danger') }}" style="font-size: 14px; padding: 4px 8px; display: inline-block;">
                                                    IPS: {{ number_format($summary['ips'], 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ url('export_rincian_akm_single_xls') }}" method="POST" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="id_prodi" value="{{ $idprodi }}">
                                <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                <input type="hidden" name="id_student" value="{{ $mhs->idstudent }}">
                                <button type="submit" class="btn btn-success btn-flat">
                                    <i class="fa fa-file-excel-o"></i> Unduh Excel Mahasiswa Ini
                                </button>
                            </form>
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
