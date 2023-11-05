@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Setting Dosen Pembimbing Magang</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('view_mhs_bim_magang') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Angkatan</label>
                            <select class="form-control" name="idangkatan" required>
                                <option></option>
                                @foreach ($angkatan as $keyangk)
                                    <option value="{{ $keyangk->idangkatan }}">{{ $keyangk->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Prodi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $keyprd)
                                    <option value="{{ $keyprd->kodeprodi }}">
                                        {{ $keyprd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success ">Lihat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Data Dosen Pembimbing Magang</h3>
            </div>
            <div class="box-body">
                <table class="table table-condensed" id="example1">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM / Nama Mahasiswa</center>
                            </th>

                            <th rowspan="2">
                                <center>Prodi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th rowspan="2">
                                <center>Magang 1</center>
                            </th>
                            <th rowspan="2">
                                <center>Magang 2</center>
                            </th>
                            <th colspan="2">
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>Magang 1</th>
                            <th>Magang 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $keydsn)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $keydsn->nim }} / {{ $keydsn->nama }}</td>
                                <td align="center">{{ $keydsn->prodi }}</td>
                                <td align="center">{{ $keydsn->kelas }}</td>
                                <td align="center">{{ $keydsn->nama_magang1 }}</td>
                                <td align="center">{{ $keydsn->nama_magang2 }}</td>
                                <td>{{ $keydsn->dsn_magang1 }}</td>
                                <td>{{ $keydsn->dsn_magang2 }}</td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateDospemPkl{{ $keydsn->id_settingrelasi_prausta1 }}"
                                            title="klik untuk edit">Edit dosen</button>
                                    </center>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateDospemPkl{{ $keydsn->id_settingrelasi_prausta1 }}"
                                tabindex="-1" aria-labelledby="modalUpdateDospemPkl" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Dosen Pembimbing Magang</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_dospem_magang/{{ $keydsn->id_settingrelasi_prausta1 }}"
                                                method="post">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nama Dosen</label>
                                                    <select class="form-control" name="id_dosen_pembimbing">
                                                        <option
                                                            value="{{ $keydsn->id_dsn_magang1 }},{{ $keydsn->dsn_magang1 }}">
                                                            {{ $keydsn->dsn_magang1 }}
                                                        </option>
                                                        @foreach ($dosen as $dsn)
                                                            <option value="{{ $dsn->iddosen }},{{ $dsn->nama }}">
                                                                {{ $dsn->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
