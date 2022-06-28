@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Settingan Kurikulum</h3>
            </div>
            <div class="box-body">
                <form action="/put_setting_kurikulum_kprd/{{ $id }}" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Kurikulum</label>
                            <select class="form-control" name="id_kurikulum" required>
                                <option value="{{ $data->id_kurikulum }}">{{ $data->nama_kurikulum }}</option>
                                @foreach ($kurikulum as $kuri)
                                    <option value="{{ $kuri->id_kurikulum }}">
                                        {{ $kuri->nama_kurikulum }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-7">
                            <label>Prodi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option value="{{ $data->id_prodi }}">{{ $data->prodi }}</option>
                                @foreach ($prodi as $keyprd)
                                    <option value="{{ $keyprd->id_prodi }}">
                                        {{ $keyprd->prodi }} - {{ $keyprd->konsentrasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <label>Angkatan</label>
                            <select class="form-control" name="id_angkatan" required>
                                <option value="{{ $data->id_angkatan }}">{{ $data->angkatan }}</option>
                                @foreach ($angkatan as $keyangk)
                                    <option value="{{ $keyangk->idangkatan }}">{{ $keyangk->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Semester</label>
                            <select class="form-control" name="id_semester" required>
                                <option value="{{ $data->id_semester }}">{{ $data->semester }}</option>
                                @foreach ($semester as $smt)
                                    <option value="{{ $smt->idsemester }}">
                                        {{ $smt->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="{{ $data->status }}">{{ $data->status }}</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NOT ACTIVE">NOT ACTIVE</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Paket</label>
                            <select class="form-control" name="pelaksanaan_paket" required>
                                <option value="{{ $data->pelaksanaan_paket }}">{{ $data->pelaksanaan_paket }}</option>
                                <option value="OPEN">OPEN</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <label>Matakuliah</label>
                            <select class="form-control select2" name="id_makul" required>
                                <option value="{{ $data->id_makul }}">{{ $data->kode }} / {{ $data->makul }}
                                </option>
                                @foreach ($matakuliah as $mk)
                                    <option value="{{ $mk->idmakul }}">
                                        {{ $mk->kode }} / {{ $mk->makul }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        $(function() {
            $('.select2').select2()
        })
    </script>
@endsection
