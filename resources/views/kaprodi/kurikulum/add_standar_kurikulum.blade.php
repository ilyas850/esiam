@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Settingan</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('save_setting_kurikulum_kprd') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Kurikulum</label>
                            <select class="form-control" name="id_kurikulum" required>
                                <option></option>
                                @foreach ($kurikulum as $kuri)
                                    <option value="{{ $kuri->id_kurikulum }}">
                                        {{ $kuri->nama_kurikulum }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-7">
                            <label>Prodi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $keyprd)
                                    <option value="{{ $keyprd->id_prodi }}">
                                        {{ $keyprd->prodi }} - {{ $keyprd->konsentrasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <label>Angkatan</label>
                            <select class="form-control" name="id_angkatan" required>
                                <option></option>
                                @foreach ($angkatan as $keyangk)
                                    <option value="{{ $keyangk->idangkatan }}">{{ $keyangk->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Semester</label>
                            <select class="form-control" name="id_semester" required>
                                <option></option>
                                @foreach ($semester as $smt)
                                    <option value="{{ $smt->idsemester }}">
                                        {{ $smt->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NOT ACTIVE">NOT ACTIVE</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Paket</label>
                            <select class="form-control" name="pelaksanaan_paket" required>
                                <option value="OPEN">OPEN</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>No</center>
                                        </th>

                                        <th>
                                            <center>Kode/Matakuliah</center>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @for ($i = 0; $i < 10; $i++)
                                        <tr>
                                            <td align="center">{{ $no++ }}</td>
                                            <td>
                                                <select name="id_makul[]" class="form-control select2">
                                                    <option></option>
                                                    @foreach ($matakuliah as $mk)
                                                        <option value="{{ $mk->idmakul }}">{{ $mk->kode }} /
                                                            {{ $mk->makul }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </section>
    <script>
        $(function() {
            $('.select2').select2()
        })
    </script>
@endsection
