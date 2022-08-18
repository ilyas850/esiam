@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Data TA Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_ta_use_prodi') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($prd_thn as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($prd_tp as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-6">
                            <label for="">Prodi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $key)
                                    <option value="{{ $key->id_prodi }}">
                                        {{ $key->prodi }} - {{ $key->konsentrasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Tugas Akhir <b> {{ $namaperiodetahun }} - {{ $namaperiodetipe }} </b></h3>
            </div>
            <div class="box-body">

                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Mahasiswa/NIM</th>
                            <th colspan="3">
                                <center>Dosen</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal PraUSTA</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal Sidang</center>
                            </th>
                            <th rowspan="2">Due Date</th>
                            <th rowspan="2">Durasi</th>
                            <th rowspan="2">
                                <center>Jam Sidang</center>
                            </th>
                            <th rowspan="2">
                                <center>Acc. Sidang</center>
                            </th>
                            <th rowspan="2" width="6%">Aksi</th>
                        </tr>
                        <tr>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji 1</center>
                            </th>
                            <th>
                                <center>Penguji 2</center>
                            </th>
                            <th>
                                <center>Mulai</center>
                            </th>
                            <th>
                                <center>Selesai</center>
                            </th>
                            <th>
                                <center>Mulai</center>
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
                                <td>{{ $no++ }}</td>
                                <td>{{ $key->nama }}/{{ $key->nim }}</td>
                                <td>{{ $key->dosen_pembimbing }}</td>
                                <td>{{ $key->dosen_penguji_1 }}</td>
                                <td>{{ $key->dosen_penguji_2 }}</td>
                                <td>{{ $key->set_waktu_awal }}</td>
                                <td>{{ $key->set_waktu_akhir }}</td>
                                <td>
                                    <center>{{ $key->tanggal_mulai }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->tanggal_selesai }}</center>
                                </td>
                                <td>
                                    @if ($key->tanggal_selesai == null)
                                        @if (floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24)) > 0)
                                            <span
                                                class="label label-info">{{ floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24)) }}
                                                hari lagi</span>
                                        @else
                                            <span class="label label-danger">Expired (
                                                {{ floor(($akhir - strtotime($key->set_waktu_akhir)) / (60 * 60 * 24)) }}
                                                hari
                                                )</span>
                                        @endif
                                    @elseif(strtotime($key->tanggal_selesai) > strtotime($key->set_waktu_akhir))
                                        <span class="label label-warning">Terlambat</span>
                                    @elseif(strtotime($key->tanggal_selesai) < strtotime($key->set_waktu_akhir))
                                        <span class="label label-success">Tepat waktu</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($key->tanggal_selesai == null)
                                        0
                                    @else
                                        {{ floor((strtotime($key->tanggal_selesai) - strtotime($key->tanggal_mulai)) / (60 * 60 * 24)) }}
                                    @endif
                                    hari
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
                                                <li><a href="/atur_ta/{{ $key->id_settingrelasi_prausta }}">Setting</a>
                                                </li>
                                                @if ($key->status == 'ACTIVE')
                                                    <li><a href="/nonatifkan_prausta_ta/{{ $key->id_settingrelasi_prausta }}"
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
