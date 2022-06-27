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
                <form class="form" role="form" action="{{ url('view_kurikulum_standar') }}" method="POST">
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
                <a href="{{ url('add_setting_kurikulum') }}" class="btn btn-info">Tambah</a>
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
                                    <th>
                                        <center>Aksi</center>
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
                                        <td align="center">
                                            @if ($item->status == 'ACTIVE')
                                                <a href="/edit_setting_kurikulum/{{ $item->idkurtrans }}"
                                                    class="btn btn-info btn-xs">Edit</a>
                                                <a href="/hapus_setting_kurikulum/{{ $item->idkurtrans }}"
                                                    class="btn btn-danger btn-xs">hapus</a>
                                                @if ($item->pelaksanaan_paket == 'OPEN')
                                                    <a href="/closed_setting_kurikulum/{{ $item->idkurtrans }}"
                                                        class="btn btn-warning btn-xs">Closed</a>
                                                @else
                                                    <a href="/open_setting_kurikulum/{{ $item->idkurtrans }}"
                                                        class="btn btn-success btn-xs">Open</a>
                                                @endif
                                            @elseif ($item->status == 'NOT ACTIVE')
                                                <a href="/aktif_setting_kurikulum/{{ $item->idkurtrans }}"
                                                    class="btn btn-warning btn-xs">Aktifkan</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modalUpdateKurikulum{{ $item->idkurtrans }}"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Setting Kurikulum</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/put_setting_kurikulum/{{ $item->idkurtrans }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <div class="row">
                                                            <div class="col-xs-4">
                                                                <div class="form-group">
                                                                    <label>Kurikulum</label>
                                                                    <select class="form-control" name="id_kurikulum">
                                                                        <option value="{{ $item->id_kurikulum }}">
                                                                            {{ $item->nama_kurikulum }}
                                                                        </option>
                                                                        @foreach ($kurikulum as $kuri)
                                                                            <option value="{{ $kuri->id_kurikulum }}">
                                                                                {{ $kuri->nama_kurikulum }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="form-group">
                                                                    <label>Angkatan</label>
                                                                    <select class="form-control" name="id_angkatan">
                                                                        <option value="{{ $item->id_angkatan }}">
                                                                            {{ $item->angkatan }}
                                                                        </option>
                                                                        @foreach ($angkatan as $keyangk)
                                                                            <option value="{{ $keyangk->idangkatan }}">
                                                                                {{ $keyangk->angkatan }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="form-group">
                                                                    <label>Semester</label>
                                                                    <select class="form-control" name="id_semester">
                                                                        <option value="{{ $item->id_semester }}">
                                                                            {{ $item->semester }}
                                                                        </option>
                                                                        @foreach ($semester as $smt)
                                                                            <option value="{{ $smt->idsemester }}">
                                                                                {{ $smt->semester }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Prodi</label>
                                                                    <select class="form-control" name="id_prodi">
                                                                        <option value="{{ $item->id_prodi }}">
                                                                            {{ $item->prodi }}
                                                                        </option>
                                                                        @foreach ($prodi as $keyprd)
                                                                            <option value="{{ $keyprd->id_prodi }}">
                                                                                {{ $keyprd->prodi }} -
                                                                                {{ $keyprd->konsentrasi }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Matakuliah </label> <br>
                                                                    <select id="mySelect2" class="form-control"
                                                                        name="id_makul">
                                                                        <option value="{{ $item->id_makul }}">
                                                                            {{ $item->kode }} / {{ $item->makul }}
                                                                        </option>
                                                                        @foreach ($mk as $mkl)
                                                                            <option value="{{ $mkl->idmakul }}">
                                                                                {{ $mkl->kode }} /
                                                                                {{ $mkl->makul }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <select class="form-control" name="status">
                                                                        <option value="{{ $item->status }}">
                                                                            {{ $item->status }}
                                                                        </option>
                                                                        <option value="ACTIVE">ACTIVE</option>
                                                                        <option value="NOT ACTIVE">NOT ACTIVE</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Paket</label>
                                                                    <select class="form-control" name="pelaksanaan_paket">
                                                                        <option value="{{ $item->pelaksanaan_paket }}">
                                                                            {{ $item->pelaksanaan_paket }}
                                                                        </option>
                                                                        <option value="OPEN">OPEN</option>
                                                                        <option value="CLOSED">CLOSED</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Perbarui
                                                            Data</button>
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
            </div>
        </div>
    </section>
    {{-- <script>
        $(function() {
            $('.select2').select2()
        })
    </script> --}}
@endsection
