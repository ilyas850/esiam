@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Standar Kurikulum</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('lihat_kurikulum_standar') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-2">
                            <label>Kurikulum</label>
                            <select class="form-control" name="id_kurikulum" required>
                                <option value="{{ $krlm->id_kurikulum }}">{{ $krlm->nama_kurikulum }}</option>
                                @foreach ($kurikulum as $kuri)
                                    <option value="{{ $kuri->id_kurikulum }}">
                                        {{ $kuri->nama_kurikulum }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-7">
                            <label>Prodi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option value="{{ $prd->id_prodi }}">{{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                </option>
                                @foreach ($prodi as $keyprd)
                                    <option value="{{ $keyprd->id_prodi }}">
                                        {{ $keyprd->prodi }} - {{ $keyprd->konsentrasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <label>Angkatan</label>
                            <select class="form-control" name="id_angkatan" required>
                                <option value="{{ $angk->idangkatan }}">{{ $angk->angkatan }}</option>
                                @foreach ($angkatan as $keyangk)
                                    <option value="{{ $keyangk->idangkatan }}">{{ $keyangk->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Semester</label>
                            <select class="form-control" name="id_semester">
                                @if ($smtr == null)
                                    <option></option>
                                @else
                                    <option value="{{ $smtr->idsemester }}">{{ $smtr->semester }}</option>
                                @endif
                                @foreach ($semester as $smt)
                                    <option value="{{ $smt->idsemester }}">
                                        {{ $smt->semester }}</option>
                                @endforeach
                                <option></option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="{{ $status }}">{{ $status }}</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NOT ACTIVE">NOT ACTIVE</option>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <label>Paket</label>
                            <select class="form-control" name="pelaksanaan_paket" required>
                                <option value="{{ $paket }}">{{ $paket }}</option>
                                <option value="OPEN">OPEN</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success">Lihat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Data Kurikulum</h3>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed" id="example9">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>Kurikulum</center>
                                    </th>
                                    <th>
                                        <center>Prodi</center>
                                    </th>
                                    <th>
                                        <center>Semester</center>
                                    </th>
                                    <th>
                                        <center>Angkatan</center>
                                    </th>
                                    <th>
                                        <center>Kode/Matakuliah</center>
                                    </th>
                                    <th>
                                        <center>SKS (Teori/Praktek)</center>
                                    </th>
                                    <th>
                                        <center>Paket</center>
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td align="center">{{ $no++ }}</td>
                                        <td align="center">{{ $item->nama_kurikulum }}</td>
                                        <td align="center">{{ $item->prodi }}</td>
                                        <td align="center">{{ $item->semester }}</td>
                                        <td align="center">{{ $item->angkatan }}</td>
                                        <td>{{ $item->kode }}/{{ $item->makul }}</td>
                                        <td align="center"> {{ $item->akt_sks_teori }}/{{ $item->akt_sks_praktek }}
                                        </td>
                                        <td align="center"> {{ $item->pelaksanaan_paket }}</td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" align="right"> <b>Total</b></td>
                                    <td align="center"> <b> {{ $sks }} SKS</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
