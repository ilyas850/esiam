@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Setting Dosen Pembimbing Seminar Proposal, Tugas Akhir & Skripsi</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('view_mhs_bim_sempro_ta') }}" method="POST">
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
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Data Dosen Pembimbing Seminar Proposal & Tugas Akhir</h3>
            </div>
            <div class="box-body">
                <table class="table table-condensed" id="example1">
                    <thead>
                        <tr>
                            <th width="1%">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM </center>
                            </th>
                            <th>
                                <center>Mahasiswa</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Dosen Sempro</center>
                            </th>
                            <th>
                                <center>Dosen TA</center>
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
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->dospem_sempro }}</td>
                                <td>{{ $item->dospem_ta }}</td>
                                <td>
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateDospemSempro{{ $item->idstudent }}"
                                        title="klik untuk edit">Edit dosen</button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateDospemSempro{{ $item->idstudent }}" tabindex="-1"
                                aria-labelledby="modalUpdateDospemSempro" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Dosen Pembimbing Seminar Proposal dan Tugas
                                                Akhir</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('edit_dospem_sempro_ta') }}" method="post">
                                                @csrf

                                                <div class="form-group">
                                                    <label>Nama Dosen Pembimbing Sempro</label>
                                                    <select class="form-control" name="id_dosen_pembimbing_sempro">
                                                        <option
                                                            value="{{ $item->id_dsn_sempro }},{{ $item->dospem_sempro }}">
                                                            {{ $item->dospem_sempro }}
                                                        </option>
                                                        @foreach ($dosen as $dsn)
                                                            <option value="{{ $dsn->iddosen }},{{ $dsn->nama }}">
                                                                {{ $dsn->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Nama Dosen Pembimbing Tugas Akhir</label>
                                                    <select class="form-control" name="id_dosen_pembimbing_ta">
                                                        <option value="{{ $item->id_dsn_ta }},{{ $item->dospem_ta }}">
                                                            {{ $item->dospem_ta }}
                                                        </option>
                                                        @foreach ($dosen as $dsn)
                                                            <option value="{{ $dsn->iddosen }},{{ $dsn->nama }}">
                                                                {{ $dsn->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                                <input type="hidden" name="id_sempro" value="{{ $item->id_sempro }}">
                                                <input type="hidden" name="id_ta" value="{{ $item->id_ta }}">
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
