@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Bimbingan Prakerin</h3>
            </div>
            <div class="box-body">

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Mahasiswa/NIM</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Jumlah Bimbingan</center>
                            </th>
                            <th>
                                <center>Cek Bimbingan</center>
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
                                <td>{{ $key->nama }}/{{ $key->nim }}</td>
                                <td>{{ $key->prodi }}</td>
                                <td>
                                    <center>{{ $key->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->angkatan }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->jml_bim }}</center>
                                </td>

                                <td>
                                    <center> <a href="cek_bim_prakerin_wadir/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-info btn-xs"> lihat </a></center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
