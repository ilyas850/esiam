@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Pilih Tipe</h3>
            </div>
            <div class="box-body">
                <a href="/data_pkl_mahasiswa" class="btn btn-info">Data PKL</a>
                <a href="/data_magang_mahasiswa" class="btn btn-success">Data Magang</a>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Data Magang</h3>
            </div>
            <div class="box-body">
                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Mahasiswa/NIM</th>
                            <th colspan="2">
                                <center>Dosen</center>
                            </th>
                            <th colspan="3">
                                <center>Tanggal Aktual</center>
                            </th>
                            <th rowspan="2">Batas Waktu</th>
                            <th rowspan="2">Due Date</th>
                            <th rowspan="2">
                                <center>Jam Seminar</center>
                            </th>
                            <th rowspan="2">
                                <center>Acc. PraUSTA</center>
                            </th>
                            <th rowspan="2" width="6%">Aksi</th>
                        </tr>
                        <tr>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji</center>
                            </th>
                            <th>
                                <center>Mulai</center>
                            </th>
                            <th>
                                <center>Pengajuan</center>
                            </th>
                            <th>
                                <center>Selesai</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $key->nama }}/{{ $key->nim }}</td>
                                <td>{{ $key->dosen_pembimbing }}</td>
                                <td>{{ $key->dosen_penguji_1 }}</td>
                                <td>
                                    <center>{{ $key->tanggal_mulai }}</center>
                                </td>
                                <td align="center">
                                    {{ $key->tgl_pengajuan }}
                                </td>
                                <td>
                                    <center>{{ $key->tanggal_selesai }}</center>
                                </td>
                                <td align="center">
                                    {{ $key->batas_waktu }} hari
                                </td>
                                <td>
                                    @if ($key->tgl_pengajuan == null)
                                        @if (floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24)) > 0)
                                            <span
                                                class="label label-info">{{ floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24)) }}
                                                hari lagi</span>
                                        @else
                                            <span class="label label-danger">EXP. (
                                                {{ floor(($akhir - strtotime($key->set_waktu_akhir)) / (60 * 60 * 24)) }}
                                                hari
                                                )</span>
                                        @endif
                                    @elseif(strtotime($key->tgl_pengajuan) > strtotime($key->set_waktu_akhir))
                                        <span class="label label-warning">Terlambat</span>
                                    @elseif(strtotime($key->tgl_pengajuan) < strtotime($key->set_waktu_akhir))
                                        <span class="label label-success">Tepat waktu</span>
                                    @endif
                                </td>
                                <td>
                                    <center>{{ $key->jam_mulai_sidang }} - {{ $key->jam_selesai_sidang }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->file_laporan_revisi == null)
                                            {{ $key->acc_seminar_sidang }}
                                        @elseif($key->file_laporan_revisi != null)
                                            Selesai
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-xs">Pilih</button>
                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a
                                                        href="/atur_prakerin/{{ $key->id_settingrelasi_prausta }}">Setting</a>
                                                </li>
                                                @if ($key->status == 'ACTIVE')
                                                    <li><a href="/nonatifkan_prausta_prakerin/{{ $key->id_settingrelasi_prausta }}"
                                                            onclick="return confirm('anda yakin akan menonaktifkan?')">Nonaktifkan</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
