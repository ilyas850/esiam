@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Prakerin</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered">
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
                                <center>Nilai</center>
                            </th>
                            <th>
                                <center>Laporan</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
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
                                    <center>{{ $item->nilai_huruf }}</center>
                                </td>
                                <td>
                                    <center><a
                                            href="/File Laporan Revisi/{{ $item->idstudent }}/{{ $item->file_laporan_revisi }}"
                                            target="_blank" style="font: white"> File Laporan</a></center>
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
