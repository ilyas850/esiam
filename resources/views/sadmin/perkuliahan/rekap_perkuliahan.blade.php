@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <!-- Filter Section -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode Tahun Akademik - Semester</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_rekap_perkuliahan') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Periode Tahun</label>
                                <select class="form-control" name="id_periodetahun" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($tahun as $thn)
                                        <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control" name="id_periodetipe" required>
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach ($tipe as $tipee)
                                        <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>



        <!-- Data Table Section -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Rekap Perkuliahan <b>{{ $namaperiodetahun }} - {{ $namaperiodetipe }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 40px;">No</th>
                            <th>Kode/Matakuliah</th>
                            <th class="text-center" style="width: 70px;">SKS (T/P)</th>
                            <th>Prodi</th>
                            <th class="text-center" style="width: 60px;">Kelas</th>
                            <th>Dosen</th>
                            <th class="text-center" style="width: 120px;">Jumlah Pertemuan</th>
                            <th class="text-center" style="width: 130px;">Online / Offline</th>
                            <th class="text-center" style="width: 100px;">Status</th>
                            <th class="text-center" style="width: 60px;">BAP</th>
                            <th class="text-center" style="width: 140px;">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            @php
                                $jmlPer = $key->jml_per ?? 0;
                                $jmlOnline = $key->jml_online ?? 0;
                                $jmlOffline = $key->jml_offline ?? 0;
                                $percentage = min(($jmlPer / 16) * 100, 100);
                                $tercapai = $jmlPer >= 16;
                                
                                // Progress bar color based on AdminLTE
                                if ($percentage >= 100) {
                                    $progressColor = 'progress-bar-success';
                                } elseif ($percentage >= 75) {
                                    $progressColor = 'progress-bar-info';
                                } elseif ($percentage >= 50) {
                                    $progressColor = 'progress-bar-warning';
                                } else {
                                    $progressColor = 'progress-bar-danger';
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $key->makul }}</td>
                                <td class="text-center">{{ $key->sks }}</td>
                                <td>{{ $key->prodi }}</td>
                                <td class="text-center">{{ $key->kelas }}</td>
                                <td>{{ $key->nama ?? '-' }}</td>
                                <td>
                                    <div class="progress progress-xs progress-striped active" style="margin-bottom: 0;">
                                        <div class="progress-bar {{ $progressColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $jmlPer }} dari 16 pertemuan</small>
                                </td>
                                <td class="text-center">
                                    <span class="label label-info" title="Online">
                                        <i class="fa fa-wifi"></i> {{ $jmlOnline }}
                                    </span>
                                    <span class="label label-success" title="Offline">
                                        <i class="fa fa-users"></i> {{ $jmlOffline }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($tercapai)
                                        <span class="label label-success">
                                            <i class="fa fa-check"></i> Tercapai
                                        </span>
                                    @else
                                        <span class="label label-danger">
                                            <i class="fa fa-times"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="cek_rekapan/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs" title="Lihat Detail BAP">
                                        <i class="fa fa-eye"></i> Cek
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="/download_bap_dosen/{{ $key->id_kurperiode }}" class="btn btn-danger btn-xs" title="Download BAP">
                                        <i class="fa fa-download"></i> BAP
                                    </a>
                                    <a href="/download_absensi_mhs/{{ $key->id_kurperiode }}" class="btn btn-warning btn-xs" title="Download Absensi">
                                        <i class="fa fa-download"></i> Absen
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
