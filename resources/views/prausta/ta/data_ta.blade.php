@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Tugas Akhir</h3>
            </div>
            <div class="box-body">
                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-size:85%">No</th>
                            <th rowspan="2" style="font-size:85%">Jenis PraUSTA</th>
                            <th rowspan="2" style="font-size:85%">Mahasiswa/NIM</th>
                            <th colspan="3" style="font-size:85%">
                                <center>Dosen</center>
                            </th>
                            <th colspan="2" style="font-size:85%">
                                <center>Tanggal</center>
                            </th>
                            <th colspan="2" style="font-size:85%">
                                <center>Jam</center>
                            </th>
                            <th rowspan="2" style="font-size:85%">
                                <center>Acc. Sempro</center>
                            </th>
                            <th rowspan="2" style="font-size:85%">Aksi</th>
                            <th rowspan="2" style="font-size:85%">Status</th>
                        </tr>
                        <tr>
                            <th style="font-size:85%">
                                <center>Pembimbing</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Penguji 1</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Penguji 2</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Mulai</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Selesai</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Mulai</center>
                            </th>
                            <th style="font-size:85%">
                                <center>Selesai</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td style="font-size:85%">{{ $key->kode_prausta }}/{{ $key->nama_prausta }}</td>
                                <td style="font-size:85%">{{ $key->nama }}/{{ $key->nim }}</td>
                                <td style="font-size:85%">{{ $key->dosen_pembimbing }}</td>
                                <td style="font-size:85%">{{ $key->dosen_penguji_1 }}</td>
                                <td style="font-size:85%">{{ $key->dosen_penguji_2 }}</td>
                                <td style="font-size:85%">
                                    <center>{{ $key->tanggal_mulai }}</center>
                                </td>
                                <td style="font-size:85%">
                                    <center>{{ $key->tanggal_selesai }}</center>
                                </td>
                                <td style="font-size:85%">
                                    <center>{{ $key->jam_mulai_sidang }}</center>
                                </td>
                                <td style="font-size:85%">
                                    <center>{{ $key->jam_selesai_sidang }}</center>
                                </td>
                                <td style="font-size:85%">
                                    <center>{{ $key->acc_seminar_sidang }}</center>
                                </td>
                                <td>
                                    <center> <a href="atur_ta/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs">
                                            Setting </a></center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->status == 'ACTIVE')
                                            <a href="nonatifkan_prausta/{{ $key->id_settingrelasi_prausta }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('anda yakin akan menonaktifkan?')">Nonaktifkan</a>
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
