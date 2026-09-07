@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Nilai Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('data_nilai') }}"> Data Nilai Mahasiswa</a></li>
            <li class="active">Cek Nilai</li>
        </ol>
    </section>
@endsection
@section('content')
    @php
        $konversiNilai = [
            'A' => '4',
            'B+' => '3.5',
            'B' => '3',
            'C+' => '2.5',
            'C' => '2',
            'D' => '1',
            'E' => '0',
        ];
        $totalMatakuliah = $data->count();
        $totalAktif = $data->where('status', 'TAKEN')->count();
        $totalBelumDikonversi = $data->filter(function ($record) use ($konversiNilai) {
            return is_null($record->nilai_ANGKA) && array_key_exists($record->nilai_AKHIR, $konversiNilai);
        })->count();
    @endphp

    <section class="content">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Mata Kuliah</span>
                        <span class="info-box-number">{{ $totalMatakuliah }} <small>MK</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">KRS Aktif</span>
                        <span class="info-box-number">{{ $totalAktif }} <small>MK</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-refresh"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Siap Dikonversi</span>
                        <span class="info-box-number">{{ $totalBelumDikonversi }} <small>nilai</small></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Riwayat Nilai Mahasiswa</h3>
                <div class="box-tools pull-right">
                    <a class="btn btn-default btn-sm" href="{{ url('data_nilai') }}">
                        <i class="fa fa-arrow-left"></i> Kembali ke daftar
                    </a>
                </div>
            </div>
            <div class="box-body bg-gray-light">
                <div class="row">
                    <div class="col-sm-6"><strong>Nama</strong><br>{{ $mhs->nama }}</div>
                    <div class="col-sm-6"><strong>NIM</strong><br>{{ $mhs->nim }}</div>
                    <div class="col-sm-6"><strong>Program Studi</strong><br>{{ $mhs->prodi ?: '-' }}</div>
                    <div class="col-sm-6"><strong>Kelas</strong><br>{{ $mhs->kelas }}</div>
                </div>
            </div>
            <form action="{{ url('save_nilai_angka') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    @if ($data->isEmpty())
                        <div class="callout callout-info">
                            <h4><i class="fa fa-info-circle"></i> Belum ada riwayat nilai</h4>
                            <p>Mahasiswa ini belum memiliki mata kuliah pada kurikulum periode yang aktif.</p>
                        </div>
                    @else
                    <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">SKS (T/P)</th>
                                <th class="text-center">Tahun Akademik</th>
                                <th class="text-center">Semester</th>
                                <th class="text-center">Nilai Huruf</th>
                                <th class="text-center">Nilai Angka</th>
                                <th class="text-center">Konversi</th>
                                <th class="text-center">Status KRS</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key)
                                @php
                                    $kurperiode = $key->kurperiode;
                                    $makul = optional($kurperiode)->makul;
                                    $tahun = optional($kurperiode)->tahun;
                                    $tipe = optional($kurperiode)->tipe;
                                    $semester = optional($kurperiode)->semester;
                                    $nilaiHuruf = $key->nilai_AKHIR;
                                    $bisaDikonversi = is_null($key->nilai_ANGKA) && array_key_exists($nilaiHuruf, $konversiNilai);
                                @endphp
                                <tr>
                                    <td data-label="No" class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Mata Kuliah">
                                        <strong>{{ optional($makul)->kode ?: '-' }}</strong><br>
                                        <span class="text-muted">{{ optional($makul)->makul ?: 'Mata kuliah tidak tersedia' }}</span>
                                    </td>
                                    <td data-label="SKS (T/P)" class="text-center">{{ optional($makul)->akt_sks_teori ?: 0 }}/{{ optional($makul)->akt_sks_praktek ?: 0 }}</td>
                                    <td data-label="Tahun Akademik" class="text-center">{{ optional($tahun)->periode_tahun ?: '-' }}<br><small class="text-muted">{{ optional($tipe)->periode_tipe ?: '-' }}</small></td>
                                    <td data-label="Semester" class="text-center">{{ optional($semester)->semester ?: '-' }}</td>
                                    <td data-label="Nilai Huruf" class="text-center">
                                        @if ($nilaiHuruf)
                                            <span class="label label-primary">{{ $nilaiHuruf }}</span>
                                        @else
                                            <span class="label label-default">Belum tersedia</span>
                                        @endif
                                    </td>
                                    <td data-label="Nilai Angka" class="text-center">{{ is_null($key->nilai_ANGKA) ? '-' : $key->nilai_ANGKA }}</td>
                                    <td data-label="Konversi" class="text-center">
                                        @if ($bisaDikonversi)
                                            <input class="nilai-checkbox" type="checkbox" name="nilai_ANGKA[]" value="{{ $key->id_studentrecord }},{{ $konversiNilai[$nilaiHuruf] }}" aria-label="Pilih nilai {{ optional($makul)->makul }} untuk dikonversi">
                                        @elseif (is_null($key->nilai_ANGKA))
                                            <span class="text-muted">Nilai huruf belum tersedia</span>
                                        @else
                                            <span class="label label-success"><i class="fa fa-check"></i> Selesai</span>
                                        @endif
                                    </td>
                                    <td data-label="Status KRS" class="text-center">
                                        @if ($key->status == 'TAKEN')
                                            <span class="label label-success">Aktif</span>
                                        @else
                                            <span class="label label-default">{{ $key->status }}</span>
                                        @endif
                                    </td>
                                    <td data-label="Aksi" class="text-center">
                                        @if ($key->status == 'TAKEN')
                                            <a href="{{ url('nonaktifkan_krs_mhs/' . $key->id_studentrecord) }}" class="btn btn-danger btn-xs" title="Nonaktifkan KRS" onclick="return confirm('Nonaktifkan mata kuliah ini dari KRS mahasiswa?')">
                                                <i class="fa fa-ban"></i> Nonaktifkan
                                            </a>
                                        @elseif ($key->status == 'DROPPED')
                                            <a href="{{ url('aktifkan_krs_mhs/' . $key->id_studentrecord) }}" class="btn btn-success btn-xs" title="Aktifkan KRS" onclick="return confirm('Aktifkan kembali mata kuliah ini ke KRS mahasiswa?')">
                                                <i class="fa fa-check"></i> Aktifkan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endif
                    <hr>
                    <input type="hidden" name="id_student" value="{{ $id }}">
                    @if ($totalBelumDikonversi > 0)
                        <button id="select-all-pending" type="button" class="btn btn-default"><i class="fa fa-check-square-o"></i> Tandai semua</button>
                        <button id="clear-all-pending" type="button" class="btn btn-default"><i class="fa fa-square-o"></i> Hilangkan tanda</button>
                        <button class="btn btn-info" type="submit"><i class="fa fa-refresh"></i> Konversi nilai terpilih</button>
                    @endif
                    <a class="btn btn-success" href="{{ url('data_nilai') }}"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </section>
    <script>
        (function () {
            var selectAll = document.getElementById('select-all-pending');
            var clearAll = document.getElementById('clear-all-pending');
            var checkboxes = document.querySelectorAll('.nilai-checkbox');

            if (selectAll) {
                selectAll.addEventListener('click', function () {
                    checkboxes.forEach(function (checkbox) { checkbox.checked = true; });
                });
            }

            if (clearAll) {
                clearAll.addEventListener('click', function () {
                    checkboxes.forEach(function (checkbox) { checkbox.checked = false; });
                });
            }
        }());
    </script>
@endsection
