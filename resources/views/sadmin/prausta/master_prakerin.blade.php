@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Data Prakerin Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_master_prakerin') }}" method="POST">
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

                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Prakerin <b> {{ $namaperiodetahun }} - {{ $namaperiodetipe }} </b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Mahasiswa/NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Prodi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th rowspan="2">
                                <center>Angkatan</center>
                            </th>
                            <th rowspan="2">
                                <center>Jumlah Bimbingan</center>
                            </th>
                            <th colspan="2">
                                <center>Nilai</center>
                            </th>
                            <th rowspan="2">
                                <center>Laporan</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>Angka</th>
                            <th>Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data_krs as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $item->nama }}/{{ $item->nim }}</td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->angkatan }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jml_bim }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->nilai_1 == null)
                                            {{ number_format(($item->nilai_1 + $item->nilai_2 + $item->nilai_3) / 2, 0) }}
                                        @else
                                            {{ number_format(($item->nilai_1 + $item->nilai_2 + $item->nilai_3) / 3, 0) }}
                                        @endif

                                    </center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_huruf }}</center>
                                </td>
                                <td>
                                    <center><a
                                            href="/File Laporan Revisi/{{ $item->idstudent }}/{{ $item->file_laporan_revisi }}"
                                            target="_blank" style="font: white"> File</a></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_master_prakerin/{{ $item->id_settingrelasi_prausta }}"
                                            class="btn btn-success btn-xs"> View</a>
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
