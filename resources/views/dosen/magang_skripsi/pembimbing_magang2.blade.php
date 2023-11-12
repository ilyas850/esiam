@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa bimbingan Magang 2</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Jml Bimbingan</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                            <th>
                                <center>BAP</center>
                            </th>
                            <th>
                                <center>Validasi BAAK</center>
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
                                    <center>{{ $key->angkatan }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->jml_bim }}</center>
                                </td>
                                <td>
                                    <center>
                                        <a class="btn btn-info btn-xs"
                                            href="/record_bim_magang2/{{ $key->id_settingrelasi_prausta }}">Cek Bimbingan</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/download_bap_magang_dsn_dlm/{{ $key->id_settingrelasi_prausta }}"
                                            class="btn btn-danger btn-xs">Download</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->validasi_baak == 'BELUM')
                                            <span class="badge bg-yellow">Belum</span>
                                        @elseif($key->validasi_baak == 'SUDAH')
                                            <span class="badge bg-green">Sudah</span>
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
