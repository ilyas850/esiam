@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Nilai Prakerin Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="3%">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="6%">
                                <center>NIM</center>
                            </th>
                            <th width="11%">
                                <center>Program Studi</center>
                            </th>
                            <th width="8%">
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Nilai 1</center>
                            </th>
                            <th>
                                <center>Nilai 2</center>
                            </th>
                            <th>
                                <center>Nilai 3</center>
                            </th>
                            <th>
                                <center>Nilai Huruf</center>
                            </th>
                            <th>
                                <center>Unduh Form</center>
                            </th>
                            <th>
<<<<<<< HEAD
                                <center>Aksi</center>
=======
                                <center>Edit</center>
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
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
                                        <a href="/unduh_nilai_prakerin_b/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs">Pembimbing</a>
                                        <a href="/unduh_nilai_prakerin_c/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-success btn-xs">Seminar</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="edit_nilai_prakerin/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-warning btn-xs">Edit</a>
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
