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
                <a href="/data_bap_pkl_mahasiswa" class="btn btn-info">Data BAP PKL</a>
                <a href="/data_bap_magang_mahasiswa" class="btn btn-success">Data BAP Magang 1</a>
                <a href="/data_bap_magang2_mahasiswa" class="btn btn-warning">Data BAP Magang 2</a>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Data BAP Magang 1 Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
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
                            <th width="11%">
                                <center>Kelas</center>
                            </th>
                            <th width="11%">
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Aksi</center>
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
                                    <center>
                                        <form action="{{ url('download_bap_prakerin') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_settingrelasi_prausta"
                                                value="{{ $key->id_settingrelasi_prausta }}">
                                            <button class="btn btn-danger btn-xs"> Download BAP</button>
                                        </form>
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
