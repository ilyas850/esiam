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
                <a href="/data_nilai_pkl_mahasiswa" class="btn btn-info">Data Nilai PKL</a>
                <a href="/data_nilai_magang_mahasiswa" class="btn btn-success">Data Nilai Magang</a>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Nilai Magang Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_nilai_magang_use_prodi') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $key)
                                    <option value="{{ $key->kodeprodi }}">
                                        {{ $key->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Filter Prodi</button>
                    </form>
                </div>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tanggal Seminar</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama Mahasiswa</center>
                            </th>

                            <th rowspan="2">
                                <center>Program Studi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th colspan="4">
                                <center>Nilai</center>
                            </th>
                            <th rowspan="2">
                                <center>Unduh Form</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>1</center>
                            </th>
                            <th>
                                <center>2</center>
                            </th>
                            <th>
                                <center>3</center>
                            </th>
                            <th>
                                <center>Huruf</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->tanggal_selesai }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_1, 0) }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_2, 0) }}</center>
                                </td>
                                <td>
                                    <center>{{ number_format($key->nilai_3, 0) }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_huruf }}</center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/unduh_nilai_prakerin_b/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs">Pembimbing</a>
                                        <a href="/unduh_nilai_prakerin_c/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-success btn-xs">Seminar</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="edit_nilai_magang/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-warning btn-xs" title="klik untuk edit"><i
                                                class="fa fa-edit"></i></a>
                                        @if ($key->validasi == 0)
                                            <a href="validate_nilai_magang/{{ $key->id_settingrelasi_prausta }}"
                                                class="btn btn-primary btn-xs" title="klik untuk validasi"><i
                                                    class="fa fa-check"></i></a>
                                        @else
                                            <a href="unvalidate_nilai_magang/{{ $key->id_settingrelasi_prausta }}"
                                                class="btn btn-danger btn-xs" title="klik untuk batal validasi"><i
                                                    class="fa fa-close"></i></a>
                                        @endif
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
