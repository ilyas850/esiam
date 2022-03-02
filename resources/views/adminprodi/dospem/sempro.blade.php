@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Setting Dosen Pembimbing Seminar Proposal</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('view_mhs_bim_sempro') }}" method="POST">
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
                                    <option value="{{ $keyprd->kodeprodi }},{{ $keyprd->id_masterkode_prausta }}">
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
                <h3 class="box-title">Data Dosen Pembimbing Seminar Proposal</h3>
            </div>
            <div class="box-body">
                <table class="table table-condensed" id="example1">
                    <thead>
                        <tr>
                            <th width="1%">
                                <center>No</center>
                            </th>
                            <th width="10%">
                                <center>NIM </center>
                            </th>
                            <th width="30%">
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="25%">
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th width="15%">
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $keydsn)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $keydsn->nim }}</center>
                                </td>
                                <td>{{ $keydsn->nama }}</td>
                                <td>{{ $keydsn->dosen_pembimbing }}</td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateDospemSempro{{ $keydsn->id_settingrelasi_prausta }}"
                                            title="klik untuk edit">Edit dosen</button>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade"
                                id="modalUpdateDospemSempro{{ $keydsn->id_settingrelasi_prausta }}" tabindex="-1"
                                aria-labelledby="modalUpdateDospemSempro" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Dosen Pembimbing Seminar Proposal</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_dospem_sempro/{{ $keydsn->id_settingrelasi_prausta }}"
                                                method="post">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nama Dosen</label>
                                                    <select class="form-control" name="id_dosen_pembimbing">
                                                        <option
                                                            value="{{ $keydsn->id_dosen_pembimbing }},{{ $keydsn->dosen_pembimbing }}">
                                                            {{ $keydsn->dosen_pembimbing }}
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
