@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data BAP Sempro Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_bap_sempro_use_prodi') }}"
                        method="POST">
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
                                        <form action="{{ url('download_bap_sempro') }}" method="post">
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
