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
                <a href="/data_val_pkl_mahasiswa" class="btn btn-info">Data Validasi PKL</a>
                <a href="/data_val_magang_mahasiswa" class="btn btn-success">Data Validasi Magang 1</a>
                <a href="/data_val_magang2_mahasiswa" class="btn btn-warning">Data Validasi Magang 2</a>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Validasi Magang</h3>
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
                                <center>Validasi</center>
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
                                <td align="center">
                                    @if ($item->file_laporan_revisi != null)
                                        <a href="{{ url("/File Laporan Revisi/{$item->idstudent}/{$item->file_laporan_revisi}") }}" target="_blank">File Laporan</a>
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        @if ($item->validasi_baak == 'BELUM' && $item->nilai_huruf != null)
                                            <a href="validasi_akhir_prausta/{{ $item->id_settingrelasi_prausta }}"
                                                class="btn btn-info btn-xs">Validasi</a>
                                        @elseif($item->validasi_baak == 'SUDAH')
                                            <a href="batal_validasi_akhir_prausta/{{ $item->id_settingrelasi_prausta }}"
                                                class="btn btn-danger btn-xs">Batal</a>
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
