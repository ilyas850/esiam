@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Nilai Sempro Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_nilai_sempro_use_prodi') }}"
                        method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $key)
                                    <option value="{{ $key->id_prodi }}">
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
                            <th width="3%" rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tanggal Seminar</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="6%" rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th width="11%" rowspan="2">
                                <center>Program Studi</center>
                            </th>
                            <th width="8%" rowspan="2">
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
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_1 }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_2 }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_3 }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_huruf }}</center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/unduh_nilai_sempro_a/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs">P</a>
                                        <a href="/unduh_nilai_sempro_b/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-success btn-xs">P I</a>
                                        <a href="/unduh_nilai_sempro_c/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-warning btn-xs">P II</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="edit_nilai_sempro_bim/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs">EP</a>
                                        <a href="edit_nilai_sempro_p1/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-success btn-xs">EP I</a>
                                        <a href="edit_nilai_sempro_p2/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-warning btn-xs">EP II</a>
                                        @if ($key->validasi == 0)
                                            <a href="validate_nilai_sempro/{{ $key->id_settingrelasi_prausta }}"
                                                class="btn btn-primary btn-xs">Validate</a>
                                        @else
                                            <a href="unvalidate_nilai_sempro/{{ $key->id_settingrelasi_prausta }}"
                                                class="btn btn-danger btn-xs">Unvalidate</a>
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
